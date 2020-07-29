<?php

//
// Copyright (C) 2006-2011 Next Generation CMS (http://ngcms.ru/)
// Name: extrainst.inc.php
// Description: Functions required for plugin managment scripts
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

// automatic config screen generator

/*
params:
 array of arrays with variables:
    name = parameter name
    title = parameter title (showed in html)
    descr = description (small symbols show)
    type  = input / select / text
    value = default filled value
    values = array of possible values (for select)
    html_flags = additional html flags for parameter
    validate = array with validation parameters, several lines may be applied
        : type = int
            : min, max = define minimum and maximum values
        : type = regex
            : match = define regex that shoud be matched

        : type = integer
        :

*/

function generate_config_page($module, $params, $values = [])
{
    global $tpl, $lang, $main_admin, $PHP_SELF;

    function mkParamLine($param)
    {
        global $tpl, $lang;

        if ($param['type'] == 'flat') {
            return $param['input'];
        }

        $tvars['vars'] = [
            'name'  => $param['name'],
            'title' => $param['title'],
            'descr' => $param['descr'],
            'error' => '',
            'input' => '',
        ];

        if ($param['descr']) {
            $tvars['vars']['[descr]'] = '';
            $tvars['vars']['[/descr]'] = '';
        } else {
            $tvars['regx']["'\\[descr\\].*?\\[/descr\\]'si"] = '';
        }

        if ($param['error']) {
            $tvars['vars']['error'] = str_replace('%error%', $param['error'], $lang['param_error']);
        }
        if ($values[$param['name']]) {
            $param['value'] = $values[$param['name']];
        }

        if ($param['type'] == 'text') {
            $tvars['vars']['input'] = '<textarea name="'.$param['name'].'" title="'.$param['title'].'" '.$param['html_flags'].'>'.htmlspecialchars($param['value'], ENT_COMPAT | ENT_HTML401, 'UTF-8').'</textarea>';
        } elseif ($param['type'] == 'input') {
            $tvars['vars']['input'] = '<input name="'.$param['name'].'" type="text" title="'.$param['title'].'" '.$param['html_flags'].' value="'.htmlspecialchars($param['value'], ENT_COMPAT | ENT_HTML401, 'UTF-8').'" />';
        } elseif ($param['type'] == 'checkbox') {
            $tvars['vars']['input'] = '<input name="'.$param['name'].'" type="checkbox" title="'.$param['title'].'" '.$param['html_flags'].' value="1"'.($param['value'] ? ' checked' : '').' />';
        } elseif ($param['type'] == 'hidden') {
            $tvars['vars']['input'] = '<input name="'.$param['name'].'" type=hidden value="'.htmlspecialchars($param['value'], ENT_COMPAT | ENT_HTML401, 'UTF-8').'" />';
        } elseif ($param['type'] == 'select') {
            $tvars['vars']['input'] = '<select name="'.$param['name'].'" '.$param['html_flags'].'>';
            foreach ($param['values'] as $oid => $oval) {
                $tvars['vars']['input'] .= '<option value="'.htmlspecialchars($oid, ENT_COMPAT | ENT_HTML401, 'UTF-8').'"'.($param['value'] == $oid ? ' selected' : '').'>'.$oval.'</option>';
            }
            $tvars['vars']['input'] .= '</select>';
        } elseif ($param['type'] == 'manual') {
            $tvars['vars']['input'] = $param['input'];
        }

        $tpl->vars('entries', $tvars);

        return $tpl->show('entries');
    }

    // Prepare
    $tpl->template('group', tpl_actions.'extra-config');
    $tpl->template('entries', tpl_actions.'extra-config');

    // For each param do
    foreach ($params as $param) {
        if ($param['mode'] == 'group') {
            $line = '';
            // Lets' group parameters into one block
            foreach ($param['entries'] as $entry) {
                $line .= mkParamLine($entry);
            }
            $tvars['vars'] = ['title' => $param['title'], 'entries' => $line];
            if (isset($param['toggle']) && $param['toggle']) {
                $tvars['regx']['#\[toggle\](.+?)\[\/toggle\]#is'] = '$1';
                $tvars['vars']['toggle_mode'] = (isset($param['toggle.mode']) && ($param['toggle.mode'] == 'hide')) ? 'none' : '';
            } else {
                $tvars['regx']['#\[toggle\](.+?)\[\/toggle\]#is'] = '';
            }
            $tpl->vars('group', $tvars);
            $entries .= $tpl->show('group', $tvars);
        //$entries .= $line;
        } else {
            $entries .= mkParamLine($param);
        }
    }

    $tpl->template('table', tpl_actions.'extra-config');
    $tvars['vars'] = ['entries' => $entries, 'plugin' => $module, 'php_self' => $PHP_SELF, 'token' => genUToken('admin.extra-config')];
    $tpl->vars('table', $tvars);
    $main_admin = $tpl->show('table');
}

// Automatic save values into module parameters DB
function commit_plugin_config_changes($module, $params)
{
    // Load cofig
    pluginsLoadConfig();

    $cfgUpdate = [];

    // For each param do save data
    foreach ($params as $param) {
        // Validate parameter if needed
        if ($param['mode'] == 'group') {
            if (is_array($param['entries'])) {
                foreach ($param['entries'] as $gparam) {
                    if ($gparam['name'] && (!$gparam['nosave'])) {
                        pluginSetVariable($module, $gparam['name'], $_POST[$gparam['name']].'');
                        $cfgUpdate[$gparam['name']] = $_POST[$gparam['name']].'';
                    }
                }
            }
        } elseif ($param['name'] && (!$param['nosave'])) {
            pluginSetVariable($module, $param['name'], $_POST[$param['name']].'');
        }
    }

    // Save config
    pluginsSaveConfig();

    // Generate log
    ngSYSLOG(['plugin' => '#admin', 'item' => 'config#'.$module], ['action' => 'update', 'list' => $cfgUpdate], null, [1]);
}

// Load params sent by POST request in plugin configuration
function load_commit_params($cfg, $outparams)
{
    foreach ($cfg as $param) {
        if ($param['name']) {
            $outparams[$param['name']] = $_POST[$param['name']];
        }
    }

    return $outparams;
}

// Priint page with config change complition notification
function print_commit_complete($plugin)
{
    global $tpl, $PHP_SELF, $main_admin;

    $tpl->template('done', tpl_actions.'extra-config');
    $tvars['vars'] = ['plugin' => $plugin, 'php_self' => $PHP_SELF];
    $tpl->vars('done', $tvars);
    $main_admin = $tpl->show('done');
}

// check if table exists
function mysql_table_exists($table)
{
    global $config, $mysql;

    if (is_array($mysql->record('show tables like '.db_squote($table)))) {
        return 1;
    }

    return 0;
}

// check field params
function get_mysql_field_type($table, $field)
{
    global $mysql;

    foreach ($mysql->select('describe '.$table) as $l) {
        if ($l['Field'] == $field) {
            return $l['Type'];
        }
    }

    return false;
}

// Database update during install
function fixdb_plugin_install($module, $params, $mode = 'install', $silent = false, &$is_error = 0)
{
    global $lang, $tpl, $mysql, $main_admin, $PHP_SELF;

    // Load config
    pluginsLoadConfig();

    $publish = [];
    if ($mode == 'install') {
        array_push($publish, ['title' => '<b>'.$lang['idbc_process'].'</b>', 'descr' => '', 'result' => '']);
    } else {
        array_push($publish, ['title' => '<b>'.$lang['ddbc_process'].'</b>', 'descr' => '', 'result' => '']);
    }
    // For each params do update DB
    foreach ($params as $table) {
        $error = 0;
        $publish_title = '';
        $publish_descr = '';
        $publish_result = '';
        $publish_error = 0;

        $create_mode = 0;

        if (!$table['table']) {
            $publish_result = 'No table name specified';
            $publish_error = 1;
            break;
        }

        $chgTableName = (($table['table'] == 'users') ? uprefix : prefix).'_'.$table['table'];

        if (($table['action'] != 'create') &&
            ($table['action'] != 'cmodify') &&
            ($table['action'] != 'modify') &&
            ($table['action'] != 'drop')
        ) {
            $publish_title = 'Table operations';
            $publish_result = 'Unknown action type specified ['.$table['action'].']';
            $publish_error = 1;
            break;
        }

        if ($table['action'] == 'drop') {
            $publish_title = $lang['idbc_tdrop'];
            $publish_title = str_replace('%table%', $table['table'], $publish_title);

            if (!mysql_table_exists($chgTableName)) {
                $publish_result = $lang['idbc_tnoexists'];
                $publish_error = 1;
                break;
            }

            $query = 'drop table '.$chgTableName;
            $mysql->query($query);

            array_push($publish, ['title' => $publish_title, 'descr' => "SQL: [$query]", 'result' => ($publish_result ? $publish_result : ($error ? $lang['idbc_fail'] : $lang['idbc_ok']))]);
            continue;
        }

        if (!is_array($table['fields'])) {
            $publish_result = 'Field list should be specified';
            $publish_error = 1;
            break;
        }

        if ($table['action'] == 'modify') {
            $publish_title = $lang['idbc_tmodify'];
            $publish_title = str_replace('%table%', $table['table'], $publish_title);

            if (!mysql_table_exists($chgTableName)) {
                $publish_result = $lang['idbc_tnoexists'];
                $publish_error = 1;
                break;
            }
        }

        if ($table['action'] == 'create') {
            $publish_title = $lang['idbc_tcreate'];
            $publish_title = str_replace('%table%', $table['table'], $publish_title);

            if (mysql_table_exists($chgTableName)) {
                $publish_result = $lang['idbc_t_alreadyexists'];
                $publish_error = 1;
                break;
            }
            $create_mode = 1;
        }

        if ($table['action'] == 'cmodify') {
            $publish_title = $lang['idbc_tcmodify'];
            $publish_title = str_replace('%table%', $table['table'], $publish_title);
            if (!mysql_table_exists($chgTableName)) {
                $create_mode = 1;
            }
        }

        // Now we can perform field creation
        if ($create_mode) {
            $fieldlist = [];
            foreach ($table['fields'] as $field) {
                if (!$field['name']) {
                    $publish_result = 'Field name should be specified';
                    $publish_error = 1;
                    break;
                }
                if (($field['action'] == 'create') || ($field['action'] == 'cmodify') || ($field['action'] == 'cleave')) {
                    if (!$field['type']) {
                        $publish_result = 'Field type should be specified';
                        $publish_error = 1;
                        break;
                    }
                    array_push($fieldlist, $field['name'].' '.$field['type'].' '.$field['params']);
                } elseif ($field['action'] != 'drop') {
                    $publish_result = 'Unknown action';
                    $publish_error = 1;
                    break;
                }
            }

            // Check if different character set are supported [ version >= 4.1.1 ]
            $charset = is_array($mysql->record("show variables like 'character_set_client'")) ? (' DEFAULT CHARSET='.($table['charset'] ? $table['charset'] : 'utf8')) : '';

            $query = 'create table '.$chgTableName.' ('.implode(', ', $fieldlist).($table['key'] ? ', '.$table['key'] : '').')'.$charset.($table['engine'] ? ' engine='.$table['engine'] : '');
            $mysql->query($query);
            array_push($publish, ['title' => $publish_title, 'descr' => "SQL: [$query]", 'result' => ($publish_result ? $publish_result : ($error ? $lang['idbc_fail'] : $lang['idbc_ok']))]);
        } else {
            foreach ($table['fields'] as $field) {
                if (!$field['name']) {
                    $publish_result = 'Field name should be specified';
                    $publish_error = 1;
                    break;
                }
                if (($field['action'] == 'create') || ($field['action'] == 'cmodify') || ($field['action'] == 'cleave')) {
                    if (!$field['type']) {
                        $publish_result = 'Field type should be specified';
                        $publish_error = 1;
                        break;
                    }
                } elseif ($field['action'] != 'drop') {
                    $publish_result = 'Unknown action';
                    $publish_error = 1;
                    break;
                }

                $ft = get_mysql_field_type($chgTableName, $field['name']);

                if ($field['action'] == 'drop') {
                    $publish_title = $lang['idbc_drfield'];
                    $publish_title = str_replace('%field%', $field['name'], $publish_title);
                    $publish_title = str_replace('%table%', $table['table'], $publish_title);
                    if (!$ft) {
                        $publish_result = $lang['idbc_fnoexists'];
                        $publish_error = 1;
                        break;
                    }
                    $query = 'alter table '.$chgTableName.' drop column `'.$field['name'].'`';
                    $mysql->query($query);
                    array_push($publish, ['title' => $publish_title, 'descr' => "SQL: [$query]", 'result' => ($publish_result ? $publish_result : ($error ? $lang['idbc_fail'] : $lang['idbc_ok']))]);
                }
                if ($field['action'] == 'create') {
                    $publish_title = $lang['idbc_amfield'];
                    $publish_title = str_replace('%field%', $field['name'], $publish_title);
                    $publish_title = str_replace('%type%', $field['type'], $publish_title);
                    $publish_title = str_replace('%table%', $table['table'], $publish_title);
                    if ($ft) {
                        $publish_result = $lang['idbc_f_alreadyexists'];
                        $publish_error = 1;
                        break;
                    }
                    $query = 'alter table '.$chgTableName.' add column `'.$field['name'].'` '.$field['type'].' '.$field['params'];
                    $mysql->query($query);
                    array_push($publish, ['title' => $publish_title, 'descr' => "SQL: [$query]", 'result' => ($publish_result ? $publish_result : ($error ? $lang['idbc_fail'] : $lang['idbc_ok']))]);
                    continue;
                }
                if ($field['action'] == 'cmodify') {
                    if (!$ft) {
                        $query = 'alter table '.$chgTableName.' add column `'.$field['name'].'` '.$field['type'].' '.$field['params'];
                    } else {
                        $query = 'alter table '.$chgTableName.' change column `'.$field['name'].'` `'.$field['name'].'` '.$field['type'].' '.$field['params'];
                    }
                    $mysql->query($query);
                    array_push($publish, ['title' => $publish_title, 'descr' => "SQL: [$query]", 'result' => ($publish_result ? $publish_result : ($error ? $lang['idbc_fail'] : $lang['idbc_ok']))]);
                    continue;
                }
            }
            if ($publish_error) {
                break;
            }
            $publish_title = '';
        }
    }

    // Scan for messages
    if ($publish_title && $publish_error) {
        array_push($publish, ['title' => $publish_title, 'descr' => $publish_descr, 'error' => $publish_error, 'result' => ($publish_result ? $publish_result : ($publish_error ? $lang['idbc_fail'] : $lang['idbc_ok']))]);
    }

    $tpl->template('install-entries', tpl_actions.'extra-config');

    // Write an info
    foreach ($publish as $v) {
        $tvars['vars'] = $v;
        if ($tvars['vars']['error']) {
            $tvars['vars']['result'] = '<font color="red">'.$tvars['vars']['result'].'</font>';
        }
        $tpl->vars('install-entries', $tvars);
        $entries .= $tpl->show('install-entries');
    }

    if ($publish_error) {
        $is_error = 0;
    }

    $is_error = 1;

    $tpl->template('install-process', tpl_actions.'extra-config');
    $tvars['vars'] = [
        'entries'   => $entries,
        'plugin'    => $module,
        'php_self'  => $PHP_SELF,
        'mode_text' => ($mode == 'install') ? $lang['install_text'] : $lang['deinstall_text'],
        'msg'       => ($mode == 'install' ? ($publish_error ? $lang['ibdc_ifail'] : $lang['idbc_iok']) : ($publish_error ? $lang['dbdc_ifail'] : $lang['ddbc_iok'])),
    ];
    $tpl->vars('install-process', $tvars);
    if (!$silent) {
        $main_admin = $tpl->show('install-process');
    }

    return $is_error;
}

// Create install page
function generate_install_page($plugin, $text, $stype = 'install')
{
    global $tpl, $lang, $main_admin, $PHP_SELF;

    $tpl->template('install', tpl_actions.'extra-config');
    $tvars['vars'] = [
        'plugin'       => $plugin,
        'stype'        => $stype,
        'install_text' => $text,
        'mode_text'    => ($stype == 'install') ? $lang['install_text'] : $lang['deinstall_text'],
        'mode_commit'  => ($stype == 'install') ? $lang['commit_install'] : $lang['commit_deinstall'],
        'php_self'     => $PHP_SELF,
    ];
    $tpl->vars('install', $tvars);
    $main_admin = $tpl->show('install');
}

//
class permissionRuleManager
{
    private $isLoaded = false;
    private $rules = [];

    public function load()
    {
        if (is_file(confroot.'perm.rules.php')) {
            // Try to load it
            include confroot.'perm.rules.php';

            // Update GLOBAL variable $PERM
            if (isset($permRules)) {
                $this->rules = $permRules;
                $this->isLoaded = true;

                return true;
            }
        }

        return false;
    }

    public function save()
    {
        if (!$this->isLoaded) {
            return false;
        }

        $prData = "<?php\n".'$permRules = '.var_export($this->$rules, true)."\n;?>";

        // Try to save config
        $fcHandler = @fopen(confroot.'perm.rules.php', 'w');
        if ($fcHandler) {
            fwrite($fcHandler, $prData);
            fclose($fcHandler);

            return true;
        }

        return false;
    }

    public function getPluginTree($plugin)
    {
        if (!$this->isLoaded) {
            return false;
        }

        return $this->rules[$plugin];
    }

    public function setPluginTree($plugin, $tree)
    {
        if (!$this->isLoaded) {
            return false;
        }

        $this->rules[$plugin] = $tree;

        return true;
    }

    public function removePlugin($plugin)
    {
        if (!$this->isLoaded || ($plugin == '#admin')) {
            return false;
        }
        if (isset($this->rules[$plugin])) {
            unset($this->rules[$plugin]);
        }

        return true;
    }

    public function listPlugins()
    {
        if (!$this->isLoaded) {
            return false;
        }

        $x = [];
        foreach ($this->rules as $k => $v) {
            if ($k != '#admin') {
                $x[] = $k;
            }
        }

        return $x;
    }

    public function getList()
    {
        if (!$this->isLoaded) {
            return false;
        }

        return $this->rules;
    }
}
