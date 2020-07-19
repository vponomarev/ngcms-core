<?php

class tpl
{
    public $data = [];
    public $root = '.';
    public $ext = '.tpl';
    public $da_vr = [];
    public $execTime = 0;
    public $execCount = 0;

    // $name   - name of template
    // $dir    - directory where to search template
    // $file   - file name for template [use $name if not specified]
    // $params - advanced params:
    //	includeAllowed        - flag: if includes are allowed
    //	includeDisableChroot  - flag: to allow to include files beyond $dir
    //	includeAllowRecursive - flag: to allow recursive includes
    public function template($name, $dir, $file = '', $params = [])
    {
        global $lang;

        // Prepare to calculate exec time
        list($usec, $sec) = explode(' ', microtime());
        $timeStart = (float) $sec + (float) $usec;

        if (is_dir($dir)) {
            $this->root = $dir;
        } else {
            ngFatalError(sprintf($lang['msge_no_tpldir'], $dir));
        }

        $nn = $name;

        $fname = $dir.($file ? $file : ((mb_substr($dir, -1) != '/' ? '/' : '').$name.$this->ext));

        if (!is_file($fname)) {
            $this->data[$nn] = '[<b>TEMPLATE NOT FOUND</b> ('.$fname.')]';

            return;
            ngFatalError(sprintf(str_replace('{fname}', $fname, $lang['fatal.tpl.lost'], $fname)));
        }

        $fp = fopen($fname, 'r');
        $data = filesize($fname) ? fread($fp, filesize($fname)) : '';
        fclose($fp);

        // Check if includes feature is activated
        if (isset($params['includeAllowed']) && $params['includeAllowed']) {
            // Check include working mode: recursive or normal
            if (isset($params['includeAllowRecursive']) && $params['includeAllowRecursive']) {
                // Recursive mode: ON
                while (preg_match('#\[:include (.+?)\]#isu', $data, $iM)) {
                    $incName = $iM[1];
                    if (!(isset($params['includeDisableChroot']) && $params['includeDisableChroot'])) {
                        $incName = str_replace(['/../', '/./'], '', $incName);
                        if (preg_match('#^\.\.\/(.+)$#isu', $incName, $mt)) {
                            $incName = $mt[1];
                        }
                    }
                    $incFile = $dir.$incName;
                    if (is_file($incFile) && is_readable($incFile)) {
                        $fI = fopen($incFile, 'r');
                        $incData = filesize($incFile) ? fread($fI, filesize($incFile)) : '';
                        $data = str_replace($iM[0], $incData, $data);
                    }
                }
            } else {
                // Recursive mode: OFF
                if (preg_match_all('#\[:include (.+?)\]#isu', $data, $iMList, PREG_SET_ORDER)) {
                    $pMatchString = [];
                    $pMatchData = [];

                    foreach ($iMList as $iMNo => $iM) {
                        $incName = $iM[1];
                        if (!(isset($params['includeDisableChroot']) && $params['includeDisableChroot'])) {
                            $incName = str_replace(['/../', '/./'], '', $incName);
                            if (preg_match('#^\.\.\/(.+)$#isu', $incName, $mt)) {
                                $incName = $mt[1];
                            }
                        }
                        $incFile = $dir.$incName;
                        if (is_file($incFile) && is_readable($incFile)) {
                            $fI = fopen($incFile, 'r');
                            $incData = filesize($incFile) ? fread($fI, filesize($incFile)) : '';
                            $pMatchString[] = $iM[0];
                            $pMatchData[] = $incData;
                        }
                        $data = str_replace($pMatchString, $pMatchData, $data);
                    }
                }
            }
        }

        $this->data[$nn] = $data;

        // Save calculate exec time
        list($usec, $sec) = explode(' ', microtime());
        $timeStop = (float) $sec + (float) $usec;

        $this->execTime += ($timeStop - $timeStart);
    }

    // Params [array]:
    // codeExec	- flag to execute ( php eval() ) code
    // inline	- flag: inline data. If set $nn is treated as data, not a template file name
    public function vars($nn, $vars = [], $params = [])
    {
        global $lang, $userROW, $CurrentHandler, $config, $PHP_SELF, $twig;

        // Prepare to calculate exec time
        list($usec, $sec) = explode(' ', microtime());
        $timeStart = (float) $sec + (float) $usec;

        $data = (isset($params['inline']) && $params['inline']) ? $nn : $this->data[$nn];
        if (isset($params['codeExec']) && $params['codeExec']) {
            $data = (eval(' ?>'.$this->data[$nn].'<?php '));
        }

        if (preg_match_all('/(?<=\{)l_(.*?)(?=\})/iu', $data, $larr)) {
            // Show language variables
            foreach ($larr[0] as $k => $v) {
                $name_larr = mb_substr($v, 2);
                $data = str_replace('{'.$v.'}', isset($lang[$name_larr]) ? $lang[$name_larr] : '[LANG_LOST:'.$name_larr.']', $data);
            }
        }

        // [TWIG]..[/TWIG]
        if (preg_match_all('/\[TWIG\](.+?)\[\/TWIG\]/isu', $data, $parr)) {
            foreach ($parr[0] as $k => $v) {
                $scode = $parr[1][$k];
                $cacheFileName = md5($scode).'.txt';
                $cacheFile = cacheRetrieveFile($cacheFileName, 3600, '_templates');
                if ($cacheFile === false) {
                    cacheStoreFile($cacheFileName, $scode, '_templates');
                }
                $tx = $twig->loadTemplate(get_plugcache_dir('_templates').$cacheFileName);
                $result = $tx->render($vars['vars']);
                $data = str_replace($v, $result, $data);
            }
        }

        // LOGIC processing
        // [isplugin <NAME>] .. [/isplugin] - content will be shown only if plugin <NAME> is active
        if (preg_match_all('/\[isplugin (.+?)\](.+?)\[\/isplugin\]/isu', $data, $parr)) {
            foreach ($parr[0] as $k => $v) {
                $data = str_replace($v, getPluginStatusActive($parr[1][$k]) ? $parr[2][$k] : '', $data);
            }
        }

        // [isntplugin <NAME>] .. [/ispntlugin] - content will be shown only if plugin <NAME> is NOT active
        if (preg_match_all('/\[isnplugin (.+?)\](.+?)\[\/isnplugin\]/isu', $data, $parr)) {
            foreach ($parr[0] as $k => $v) {
                $data = str_replace($v, getPluginStatusActive($parr[1][$k]) ? '' : $parr[2][$k], $data);
            }
        }

        // Special variable {plugin_<NAME>...} ({plugin_ads}, {plugin_ads_var1} for plugin ads) - will be showed only if plugin <NAME> is active
        if (preg_match_all('/(?<=\{)plugin_(.*?)(?=\})/iu', $data, $parr)) {
            foreach ($parr[0] as $k => $v) {
                $name_parr = mb_substr($v, 7);
                if (preg_match('/^(.+)\_/u', $name_parr, $match)) {
                    $name_parr = $match[1];
                }

                if (!getPluginStatusActive($name_parr)) {
                    $data = str_replace('{'.$v.'}', '', $data);
                }
            }
        }

        // - Special display for different handlers
        // [ifhandler:<RULE>] .. [/ifhandler]
        // [ifnhandler:<RULe>] .. [/ifhandler]
        // RULE is: <ENTRY1>[|<ENTRY2>[|<ENTRY3>...]]
        // ENTRY1,2,.. is: <PLUGIN>[:<HANDLER>]
        if (preg_match_all('#\[if(n){0,1}handler\:(.+?)](.+?)\[\/ifhandler\]#isu', $data, $parr, PREG_SET_ORDER)) {
            //print "<pre>PMC:".var_export($parr, true)."</pre><br/>\n";
            //print "<pre>CH:".var_export($CurrentHandler, true)."</pre><br/>\n";

            foreach ($parr as $k => $v) {
                // 0 - catched text
                // 1 - 'n' for NOT expression, else - ''
                // 2 - Plugin/Handler list string
                // 3 - content of the block
                $filterNegativeFlag = ($v[1] == 'n') ? 1 : 0;

                $ruleCatched = false;
                foreach (preg_split("#\|#", $v[2]) as $rule) {
                    if (preg_match("#^(.+?)\:(.+?)$#u", $rule, $pt)) {
                        // Specified: Plugin + Handler
                        if (($pt[1] == $CurrentHandler['pluginName']) && ($pt[2] == $CurrentHandler['handlerName'])) {
                            $ruleCatched = true;
                            break;
                        }
                    } elseif ($rule == $CurrentHandler['pluginName']) {
                        $ruleCatched = true;
                        break;
                    }
                }
                $bShowFlag = (($ruleCatched && !$filterNegativeFlag) || (!$ruleCatched && $filterNegativeFlag));
                $data = str_replace($v[0], $bShowFlag ? $v[3] : '', $data);
            }
        }

        // - Special display for languages
        // [iflang:<Language>] .. [/iflang]
        if (preg_match_all('/\[iflang\:(.+?)\](.+?)\[\/iflang\]/isu', $data, $parr)) {
            foreach ($parr[0] as $k => $v) {
                $data = str_replace($v, ($config['default_lang'] == $parr[1][$k]) ? '$1' : '', $data);
            }
        }
        // [ifnlang:<Language>] .. [/ifnlang]
        if (preg_match_all('/\[ifnlang\:(.+?)\](.+?)\[\/ifnlang\]/isu', $data, $parr)) {
            foreach ($parr[0] as $k => $v) {
                $data = str_replace($v, ($config['default_lang'] == $parr[1][$k]) ? '' : '$1', $data);
            }
        }

        if ($PHP_SELF && $PHP_SELF == 'admin.php') {
            preg_match_all('/(?<=\{)c_(.*?)(?=\})/iu', $data, $carr);

            foreach ($carr[0] as $k => $v) {
                $name_carr = mb_substr($v, 2);
                $data = str_replace('{'.$v.'}', $config[$name_carr], $data);
            }
        }

        // Process variables
        if (isset($vars['vars']) && is_array($vars['vars'])) {
            foreach ($vars['vars'] as $id => $var) {
                if (mb_substr($id, 0, 1) == '[') {
                    $data = str_replace($id, $var, $data);
                } else {
                    if (!is_array($var)) {
                        $data = str_replace('{'.$id.'}', $var, $data);
                    }
                }
            }
        }

        // Process regular expressions
        if (isset($vars['regx']) && is_array($vars['regx'])) {
            foreach ($vars['regx'] as $id => $var) {
                $data = preg_replace($id, $var, $data);
            }
        }

        if (defined('skins_url')) {
            $data = str_replace('{skins_url}', skins_url, $data);
        }

        if (defined('tpl_url')) {
            $data = str_replace('{tpl_url}', tpl_url, $data);
        }

        if (defined('admin_url')) {
            $data = str_replace('{admin_url}', admin_url, $data);
        }

        if (defined('scriptLibrary')) {
            $data = str_replace('{scriptLibrary}', scriptLibrary, $data);
        }

        if (isset($params['inline']) && $params['inline']) {
            return $data;
        }

        $this->da_vr[$nn] = $data;

        // Save calculate exec time
        list($usec, $sec) = explode(' ', microtime());
        $timeStop = (float) $sec + (float) $usec;

        $this->execTime += ($timeStop - $timeStart);
        $this->execCount++;
    }

    public function show($name)
    {
        $ret = $this->da_vr[$name];
        $this->da_vr[$name] = $this->data[$name];

        return $ret;
    }
}
