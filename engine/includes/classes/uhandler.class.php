<?php

//
// Copyright (C) 2009-2012 Next Generation CMS (http://ngcms.ru/)
// Name: uhandler.class.php
// Description: URL handler class
// Author: Vitaly Ponomarev
//

/*
 First: each plugin registers it's list of supported commands and
 list & types of accepted commands.

 Each plugin may register a list of it's supported commands with specified params


 params = array with supported params
        'vars'			=> array ( <VARIABLES> )
            '<NAME>'	=> array ( <PARAMS> )
                'matchRegex'	= matching REGEX
                'descr'			= description
        'descr'			= description

*/

class urlLibrary
{
    // Constructor
    public function __construct()
    {
        global $config;

        $this->CMD = [];
        $this->configLoaded = false;
        $this->fatalError = false;
        $this->configFileName = confroot.'urlconf.php';
    }

    // Load config from DISK
    public function loadConfig()
    {

        // Check if config already loaded
        if ($this->configLoaded) {
            return true;
        }

        // Try to read config file
        if (is_file($this->configFileName)) {
            // Include REC
            include $this->configFileName;
            if (!isset($urlLibrary)) {
                $this->fatalError = 1;

                return false;
            }
            $this->CMD = $urlLibrary;
        }
        $this->configLoaded = true;

        return true;
    }

    // Save config to DISK
    public function saveConfig()
    {

        // No save if config file is not loaded
        if (!$this->configLoaded) {
            return false;
        }

        // Try to write config file
        if (($f = fopen($this->configFileName, 'w')) === false) {
            // Error
            $this->fatalError = true;

            return false;
        }

        fwrite($f, '<?php'."\n".'$urlLibrary = '.var_export($this->CMD, true).';');
        fclose($f);

        return true;
    }

    // Register supported commands
    public function registerCommand($plugin, $cmd, $params)
    {
        if (!$this->loadConfig()) {
            return false;
        }

        $this->CMD[$plugin][$cmd] = $params;

        return true;
    }

    // Remove recently registered command
    public function removeCommand($plugin, $cmd)
    {
        if (!$this->loadConfig()) {
            return false;
        }

        // Check if command exists
        if (isset($this->CMD[$plugin][$cmd])) {
            unset($this->CMD[$plugin][$cmd]);

            // Check if there're no more commands for this plugin
            if (is_array($this->CMD[$plugin]) && (!count($this->CMD[$plugin]))) {
                unset($this->CMD[$plugin]);
            }
        }

        return true;
    }

    // Fetch command data
    public function fetchCommand($plugin, $cmd)
    {
        return isset($this->CMD[$plugin][$cmd]) ? $this->CMD[$plugin][$cmd] : false;
    }

    // Extract line with most matching language
    public function extractLangRec($data, $pl = '')
    {
        global $config;

        if (!is_array($data)) {
            return false;
        }

        if ($pl == '') {
            $pl = $config['default_lang'];
        }

        if (isset($data[$pl])) {
            return $data[$pl];
        }

        return isset($data['english']) ? $data['english'] : $data[0];
    }
}

/*
 urlLibrary - manages a list of possible actions that are supported by different plugins
 urlHandler - manages a configuration that will be used for URL matching/catching


 Supported function:
  * registerHandler() - register new handler in internal library
    pluginName			- name of the plugin
    pluginHandler		- name of the handler
    flagPrimary			- FLAG: primary record for this type of handlerName/pluginName. Will be used
                          for generation
    flagFailContinue	- FLAG: continue scanning if called function returned 'NOT FOUND'/'FAIL'.
                        May be used for merging static & news - try to show static, if no page
                        is found - try to show news.
    callbackFunc		- function to call in case of successfull CATCH (it's return value is checked if
                        variable flagFailContinue is set



 Format of handlers:

 pluginName			- name of plugin
 handlerName		- name of handler in plugin
 flagPrimary		- FLAG: primary record for this type of handlerName/pluginName. Will be used
                      for generation
 flagFailContinue	- FLAG: continue scanning if called function returned 'NOT FOUND'/'FAIL'.
                      May be used for merging static & news - try to show static, if no page
                      is found - try to show news.
 callbackFunc		- function to call in case of successfull CATCH (it's return value is checked if
                      variable flagFailContinue is set
 rstyle				- variables to REWRITE STYLE URL's [ Regex STYLE ]
  regex				- REGEX to catch URL
  regexMap			- MAPPING for converting URL with REGEX to variables
  reqCheck			- array with param check. In case if not very strict regex is used
  setVars			- array with defining additional variables
  genrMAP			- generation _rewrite_ MAP
 ostyle				- variables to OLD STYLE URL's [ Old STYLE ]
  reqCheck			- array with param check.
  setVars			- array with defining additional variables
  genoMap			- generation _old_ MAP

## regexMap - array:
    regexVarID => varName
   example:
 * regexMap => array (
    1 => 'id',
    2 => 'name',
   )

## reqCheck - array:
    varName => varRequiredValue
   example:
*   reqCheck => array (
     'mode' => 'sync'
    )

## setVars - array:
    varName => array( TYPE, VALUE )
     TYPE = 0, VALUE = static text
     TYPE = 1, VALUE = name of already defined variable to use

   example:
*  setVars => array (
    'action' => array ( 0, 'plugin' ),
    'plugin' => array ( 0, 'sync' ),
    'pcmd'   => array ( 1, 'name' ),
   )

## genrMAP - array of concatinated values:
    row: array ( TYPE, VALUE )
     TYPE = 0, VALUE = static text
     TYPE = 1, VALUE = name of variable

   example:
*  genrMAP => array (
    array ( 0, 'sync/' ),
    array ( 1, 'name'  )
   )
   // will generate URL like 'sync/HERE_IS_A_NAME'

## genoMAP - array of params to set
    varName => array ( TYPE, VALUE )
     TYPE = 0, VALUE = static text
     TYPE = 1, VALUE = name of variable

   example:
*  genrMAP => array (
    'action' => array ( 0, 'sync' ),
    'name' => array ( 1, 'name'  )
   )
   // will generate URL like '?action=sync&name=HERE_IS_A_NAME'


   // // // FLAGS PARAMS // // //
   debug		-	flag if debug mode should be activated
   localPrefix	-	set if system is installed in subdirectory
   domainPrefix	-	prefix with full domain name. used for generation of ABSOLUTE links

*/

class urlHandler
{
    // constructor
    public function __construct($options = [])
    {
        global $config;

        $this->hList = [];
        $this->configLoaded = false;
        $this->configFileName = confroot.'rewrite.php';

        $this->options = $options;
    }

    // Populate handler record from HTTP submit interface
    public function populateHandler($ULIB, $data)
    {

        //
        // First - find references from URL library
        if (!isset($data['pluginName']) || !isset($data['handlerName']) || !isset($ULIB->CMD[$data['pluginName']][$data['handlerName']])) {
            return [[1, 'No match with URL library'.var_export($data, true)], false];
        }

        // Command catched
        $cmd = $ULIB->CMD[$data['pluginName']][$data['handlerName']];

        // Scan 'regex' line & prepare output regex

        // Stateful machine status
        // 0 - scanning text
        // 1 - scanning tag name
        $state = 0;
        $dataStartPos = 0;

        $pos = 0;
        $rcmd = $data['regex'];
        $len = strlen($rcmd);

        $variativeID = 0;
        $variative = 0;
        $closeit = false;

        $genmap = [];

        while ($pos < $len) {
            switch ($state) {
                case '0':
                    if ($rcmd[$pos] == '[') {
                        // Begin of variative block
                        $newVariative = ++$variativeID;
                        $closeit = true;
                    }
                    if ($rcmd[$pos] == ']') {
                        $newVariative = 0;
                        $closeit = true;
                    }

                    if ($rcmd[$pos] == '{') {
                        $state = 1;
                        $closeit = true;
                        $newVariative = $variative;
                    }
                    if ($closeit) {
                        $closeit = false;

                        if ($pos > $dataStartPos) {
                            $text = mb_substr($rcmd, $dataStartPos, $pos - $dataStartPos);
                            $genmap[] = [0, $text, $variative];
                        }
                        $variative = $newVariative;
                        $dataStartPos = $pos + 1;
                        break;
                    }
                    break;
                case '1':
                    if ($rcmd[$pos] == '}') {
                        // End of the variable
                        $text = mb_substr($rcmd, $dataStartPos, $pos - $dataStartPos);
                        $genmap[] = [getIsSet($cmd['vars'][$text]['isSecure']) ? 2 : 1, $text, $variative];
                        $dataStartPos = $pos + 1;
                        $state = 0;
                        break;
                    }
                    break;
            }
            $pos++;
        }

        // Check for last block
        if ($state) {
            // ERROR - not finished variable !!
            return [[2, 'Variable is not closed'], false];
        } elseif ($variative) {
            // ERROR - not closed variative block !!
            return [[3, 'Variative block is not closed'], false];
        } else {
            if ($dataStartPos < $pos) {
                $text = mb_substr($rcmd, $dataStartPos, $pos - $dataStartPos);
                $genmap[] = [0, $text, 0];
            }
        }

        //print "Incoming cmd: [".$rcmd."]<br/><pre>";
        //print var_export($genmap, true);
        //print "</pre>";

        // Now we can generate processing REGEX
        $regex = '#^';
        $regexMAP = [];
        $paramNum = 1;
        $vmatch = 0;
        foreach ($genmap as $rec) {
            if ($rec[2] != $vmatch) {
                if (!$vmatch) {
                    $vmatch = $rec[2];
                    $regex .= '(?:';
                } elseif (!$rec[2]) {
                    $vmatch = 0;
                    $regex .= '){0,1}';
                } else {
                    $vmatch = $rec[2];
                    $regex .= '){0,1}(?:';
                }
            }
            if ($rec[0]) {
                if (!isset($cmd['vars'][$rec[1]])) {
                    return [[4, 'Variable "'.$rec[1].'" is unknown'], false];
                }
                $regex .= '('.$cmd['vars'][$rec[1]]['matchRegex'].')';
                $regexMAP[$paramNum++] = $rec[1];
            } else {
                $regex .= $rec[1];
            }
        }

        // If we have closing variative block at the end - process it
        if ($vmatch) {
            $vmatch = 0;
            $regex .= '){0,1}';
        }
        // Closing REGEX tag
        $regex .= '$#';

        //print "Outgoing regex: [".$regex."]<br/><pre>";
        //print var_export($regexMAP, true);
        //print "</pre>";

        // Prepare outgoing structure
        $dcfg = [
            'pluginName'       => $data['pluginName'],
            'handlerName'      => $data['handlerName'],
            'flagPrimary'      => $data['flagPrimary'] ? true : false,
            'flagFailContinue' => $data['flagFailContinue'] ? true : false,
            'flagDisabled'     => $data['flagDisabled'] ? true : false,
            'rstyle'           => [
                'rcmd'     => $rcmd,
                'regex'    => $regex,
                'regexMap' => $regexMAP,
                'reqCheck' => [],
                'setVars'  => [],
                'genrMAP'  => $genmap,
            ],
        ];

        //print "<b><u>Output data struct:</u></b> <br/><pre>\n";
        //print var_export($dcfg, true);
        //print "</pre>";

        return [[0, 0], $dcfg];
    }

    // register handler
    public function registerHandler($position, $handler)
    {
        if (!$this->configLoaded) {
            return false;
        }

        if ($position == 0) {
            array_unshift($this->hList, $handler);
        } elseif ($position == -1) {
            array_push($this->hList, $handler);
        } else {
            $tdata = array_slice($this->hList, 0, $position) + [$handler] + array_slice($this->hList, $position + 1);
            $this->hList = $tdata;
        }

        return true;
    }

    // remove handler from specific position
    // All parameters are mondatory! $pluginName and $handlerName are added to avoid mistakes
    // $position - position of handler in current list of handlers
    // $pluginName - 'pluginName' value for deleted handler
    // $handlerName - 'handlerName' value for deleted handler
    public function removeHandler($position, $pluginName, $handlerName)
    {
        if (!$this->configLoaded) {
            return false;
        }

        if (isset($this->hList[$position])) {
            // Position found. Check parameters
            $h = $this->hList[$position];
            if (isset($h['pluginName']) && ($h['pluginName'] == $pluginName) && isset($h['handlerName']) && ($h['handlerName'] == $handlerName)) {
                // Yes, we can delete
                array_splice($this->hList, $position, 1);

                // Confirm success deletion
                return true;
            }
        }

        return false;
    }

    // Remove handlers for specific plugin
    // $pluginName - name of plugin
    // $handlerName - name of specific handler or '*' if you need to delete all handlers of this plugin
    public function removePluginHandlers($pluginName, $handlerName = '*')
    {
        if (!$this->configLoaded) {
            return false;
        }

        $position = count($this->hList);
        while ($position >= 0) {
            $h = $this->hList[$position];
            if ((isset($h['pluginName'])) && ($h['pluginName'] == $pluginName) && (isset($h['handlerName'])) && (($handlerName == '*') || ($h['handlerName'] == $handlerName))) {
                array_splice($this->hList, $position, 1);
            }
            $position--;
        }

        return true;
    }

    // Return current list of handlers
    public function listHandlers()
    {
        if (!$this->configLoaded) {
            return false;
        }

        return $this->hList;
    }

    // Load config from DISK
    public function loadConfig()
    {

        // Try to read config file
        if (is_file($this->configFileName)) {
            // Include REC
            include $this->configFileName;
            if (!isset($handlerList) || !isset($handlerPrimary)) {
                $this->fatalError = 1;

                return false;
            }
            $this->hList = $handlerList;
            $this->hPrimary = $handlerPrimary;
        }
        $this->configLoaded = true;

        return true;
    }

    // Save config to DISK
    public function saveConfig()
    {

        // No save if config file is not loaded
        if (!$this->configLoaded) {
            return false;
        }

        // Try to write config file
        if (($f = fopen($this->configFileName, 'w')) === false) {
            // Error
            $this->fatalError = true;

            return false;
        }

        // Generate helper $handlerPrimary table
        $hPrimary = [];
        foreach ($this->hList as $hId => $hData) {
            // Skip disabled records
            if ($hData['flagDisabled']) {
                continue;
            }
            if (isset($hPrimary[$hData['pluginName']][$hData['handlerName']])) {
                if (!$hPrimary[$hData['pluginName']][$hData['handlerName']][1] && ($hData['flagPrimary'])) {
                    $hPrimary[$hData['pluginName']][$hData['handlerName']] = [$hId, $hData['flagPrimary']];
                }
            } else {
                $hPrimary[$hData['pluginName']][$hData['handlerName']] = [$hId, $hData['flagPrimary']];
            }
        }

        fwrite($f, '<?php'."\n".'$handlerList = '.var_export($this->hList, true).";\n".'$handlerPrimary = '.var_export($hPrimary, true).';');
        fclose($f);

        return true;
    }

    // Set configuration options
    public function setOptions($options = [])
    {
        foreach ($options as $k => $v) {
            $this->options[$k] = $v;
        }
    }

    // RUN callback functions
    public function run($url = null, $flags = [])
    {

        // Init URL if it's not passed in params
        if ($url == null) {
            $url = $_SERVER['REQUEST_URI'];
            if (($tmp_pos = mb_strpos($url, '?')) !== false) {
                $url = mb_substr($url, 0, $tmp_pos);
            }
        }

        // Merge flags with default options
        foreach ($this->options as $optName => $optValue) {
            if (!isset($flags[$optName])) {
                $flags[$optName] = $optValue;
            }
        }

        if ($flags['debug']) {
            echo 'urlHandler :: RUN('.$url.")<br>\n";
        }

        // Modity calling URL if localPrefix is defined
        if (isset($flags['localPrefix']) && ($flags['localPrefix'] != '')) {
            if (mb_substr($url, 0, mb_strlen($flags['localPrefix'])) == $flags['localPrefix']) {
                // Catched prefix
                $url = mb_substr($url, mb_strlen($flags['localPrefix']));
                if ($flags['debug']) {
                    echo "urlHandler :: RUN [<font color='red'><b>LOCAL PREFIX</b></font>: `".$flags['localPrefix'].'`] ('.$url.")<br/>\n";
                }
            } else {
                // URL doesn't correspond to LOCAL PREFIX
                if ($flags['debug']) {
                    echo "urlHandler :: RUN [<font color='red'><b>LOCAL PREFIX</b></font>: `".$flags['localPrefix']."`] - <i><b>ERROR: URL DOES NOT CORRESPOND TO PREFIX</b></i><br/>\n";
                }

                return 0;
            }
        }

        $catchCount = 0;

        foreach ($this->hList as $hNum => $h) {
            if ($flags['debug']) {
                echo '&raquo; '.($h['flagDisabled'] ? '<b><font color="red">DISABLED</font></b> ' : '').'Scan ('.$hNum.')['.$h['pluginName'].']['.$h['handlerName'].'] ReGEX check [ <b><font color=blue>'.$h['rstyle']['regex']." </font></b>]<br>\n";
            }

            // Skip disabled records
            if ($h['flagDisabled']) {
                continue;
            }

            if (preg_match($h['rstyle']['regex'], $url, $scan)) {
                $result = ['0' => $scan[0]];
                $handlerParams = ['num' => $hNum, 'value' => $h];

                foreach ($scan as $k => $v) {
                    if (isset($h['rstyle']['regexMap'][$k])) {
                        $result[$h['rstyle']['regexMap'][$k]] = urldecode($v);
                    }
                }

                if ($flags['debug']) {
                    echo 'Find match [plugin: <b>'.$h['pluginName'].'</b>, handler: <b>'.$h['handlerName'].'</b>] with REGex <b><font color=blue>'.$h['rstyle']['regex'].'</font></b>, params: <pre>'.var_export($result, true)."</pre><br>\n";
                }

                if (!isset($h['callback'])) {
                    $h['callback'] = '_MASTER_URL_PROCESSOR';
                }

                // Set configured vars
                if (isset($h['rstyle']['setVars']) && is_array($h['rstyle']['setVars'])) {
                    foreach ($h['rstyle']['setVars'] as $k => $v) {
                        if (is_array($v)) {
                            if ($v[0] == 0) {
                                $result[$k] = $v[1];
                            } elseif (($v[0] == 1) && isset($result[$v[1]])) {
                                $result[$k] = $result[$v[1]];
                            }
                        }
                    }
                }

                $skip = ['FFC' => $h['flagFailContinue'] ? true : false];
                $res = call_user_func($h['callback'], $h['pluginName'], $h['handlerName'], $result, $skip, $handlerParams);
                if (is_array($res) && isset($res['fail']) && $res['fail']) {
                    continue;
                }

                return ++$catchCount;
            }
        }

        return $catchCount;
    }

    //
    // Generate link
    // Params:
    // $pluginName	- ID of plugin
    // $handlerName	- Handler name
    // $params	- Params to pass to processor
    // $xparams	- External params to pass as "?param1=value1&...&paramX=valueX"
    // $intLink	- Flag if links should be treated as `internal` (i.e. all '&' should be displayed as '&amp;'
    // $absoluteLink - Flag if absolute link (including http:// ... ) should be generated
    public function generateLink($pluginName, $handlerName, $params = [], $xparams = [], $intLink = false, $absoluteLink = false)
    {
        $flagCommon = false;

        // Check if we have handler for requested plugin
        if (!isset($this->hPrimary[$pluginName][$handlerName])) {
            // No handler. Let's use "COMMON WAY"
            $params['plugin'] = $pluginName;
            $params['handler'] = $handlerName;

            $pluginName = 'core';
            $handlerName = 'plugin';
            $flagCommon = true;
        }

        // Fetch identity [ array( recNo, primaryFlagStatus ) ]
        $hId = $this->hPrimary[$pluginName][$handlerName];
        // Fetch record
        $hRec = $this->hList[$hId[0]];

        // Check integrity
        if (!is_array($hRec) || !is_array($hRec['rstyle']['genrMAP'])) {
            return false;
        }

        // First: find block dependency
        $depMAP = [];
        foreach ($hRec['rstyle']['genrMAP'] as $rec) {
            // If dependent block & this is variable & no rec in $depMAP - save
            if ($rec[2] && $rec[0] && !isset($depMAP[$rec[2]])) {
                $depMAP[$rec[2]] = $rec[1];
            }
        }

        // Now we can generate URL
        $url = [];
        foreach ($hRec['rstyle']['genrMAP'] as $rec) {
            if (!$rec[2] || ($rec[2] && isset($params[$depMAP[$rec[2]]]))) {
                switch ($rec[0]) {
                    case 0:
                        $url[] = $rec[1];
                        break;
                    case 1:
                        $url[] = urlencode($params[$rec[1]]);
                        break;
                    case 2:
                        $url[] = $params[$rec[1]];
                        break;
                }
            }
        }

        // Add params in case of common mode
        $uparams = [];
        if ($flagCommon) {
            unset($params['plugin']);
            unset($params['handler']);
            $xparams = array_merge($params, $xparams);
        }

        foreach ($xparams as $k => $v) {
            if (($k != 'plugin') && ($k != 'handler')) {
                $uparams[] = $k.'='.urlencode($v);
            }
        }

        $linkPrefix = ($absoluteLink && isset($this->options['domainPrefix']) && ($this->options['domainPrefix'] != '')) ?
            $this->options['domainPrefix'] :
            ((isset($this->options['localPrefix']) && ($this->options['localPrefix'] != '')) ? $this->options['localPrefix'] : '');

        return $linkPrefix.
            implode('', $url).
            (count($uparams) ? '?'.implode('&'.($intLink ? 'amp;' : ''), $uparams) : '');
    }
}
