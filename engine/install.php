<?php

//
// Copyright (C) 2006-2011 Next Generation CMS (http://ngcms.ru)
// Name: install.php
// Description: System installer
// Author: Vitaly Ponomarev
//

@error_reporting (E_ALL ^ E_NOTICE);
define('NGCMS', 1);

// Basic variables
@define('root', dirname(__FILE__).'/');
@include_once root.'includes/inc/multimaster.php';

multi_multisites();
@define( 'confroot', root.'conf/'.($multiDomainName && $multimaster && ($multiDomainName != $multimaster) ? 'multi/'.$multiDomainName.'/' : '') );

// Check if config file already exists
if ( ( @fopen(confroot.'config.php','r') ) && ( filesize(confroot.'config.php') ) ) {
        //printHeader();
	echo "<font color=red><b>Error: configuration file already exists!</b></font><br />Delete it and continue.<br />\n";
	return;
}

// =============================================================
// Fine, we're ready to start installation
// =============================================================

@include_once 'includes/classes/templates.class.php';
$tpl	=	new tpl;

// Determine current admin working directory
list($adminDirName) = array_slice($ADN = preg_split('/(\\\|\/)/',root, -1, PREG_SPLIT_NO_EMPTY), -1, 1);
$installDir   = ((substr(root,0,1) == '/')?'/':'').join("/", array_slice($ADN, 0, -1));
$templateDir  = root. 'skins/default/install';

// Determine installation URL
$homeURL     = 'http://'.$_SERVER['HTTP_HOST'].'/'.($a=join("/", array_slice(preg_split('/(\\\|\/)/',$_SERVER['REQUEST_URI'], -1, PREG_SPLIT_NO_EMPTY),0, -2))). ($a?'/':'');
$templateURL = $homeURL . $adminDirName . '/skins/default/install';
$ERR               = array();

$tvars = array ( 'vars' => array( 'templateURL' => $templateURL, 'homeURL' => $homeURL ));
foreach (array('begin', 'db', 'plugins', 'template', 'perm', 'common', 'install') as $v) {
	$tvars['vars']['menu_'.$v] = '';
}

// If action is specified, but license is not accepted - stop installation
if ($_POST['action'] && !$_POST['agree']) {
        $tvars['vars']['menu_begin'] = ' class="hover"';
	printHeader();
	$tpl -> template('notagree', $templateDir);
	$tpl -> vars('notagree', $tvars);
	echo $tpl -> show('notagree');
	exit;
}

// Flag if we need to do some configuration actions after install
$flagPendingChanges = false;

//
// Determine required action
//

switch ($_POST['action']) {
	case 'config':
		doConfig();
		break;
	case 'install':
		$flagPendingChanges = doInstall();
		break;
	default:
		doWelcome();
		break;
}

// If we made installations and have some pending changes
if ($flagPendingChanges) {

include_once root."core.php";
include_once root."includes/inc/extraconf.inc.php";
include_once root."includes/inc/extrainst.inc.php";

	$LOG = array();
	$ERROR = array();
	$error = 0;

	// Now let's install plugins
	// First: Load informational `version` files
	$list = get_extras_list();
	foreach ($pluginInstallList as $pName) {
		if ($list[$pName]['install']) {
			include_once root."plugins/".$pName."/".$list[$pName]['install'];
			$res = call_user_func('plugin_'.$pName.'_install', 'autoapply');

			if ($res) {
				array_push($LOG, "Установка плагина <b>".$pName."</b> ... OK");
			} else {
				array_push($ERROR, "Установка плагина <b>".$pName."</b> ... ERROR");
				$error = 1;
				break;
			}
		}
		array_push($LOG, "Активация плагина <b>".$pName."</b> ... ".(pluginSwitch($pName, 'on')?'OK':'ERROR'));
	}

	print '<div class="body"><p style="width: 99%;">';
	foreach ($LOG as $line) { print $line."<br />\n"; }
	if ($error) {
		foreach ($ERROR as $errText) {
			print '<div class="errorDiv"><b><u>Ошибка</u>!</b><br/>'.$errText.'</div>';
		}

		print '<div class="warningDiv">Неожиданная ошибка установки. Пожалуйста, обратитесь к разработчикам</div>';
	} else {
		print '<br/><br/><b>Установка успешно заверешена!</b><br/>Для проведения дальнейших настроек перейдите, пожалуйста, <a href="'.$homeURL.$adminDirName.'/">по этой ссылке</a>.';
	}
	print '</p></div>';
}



// SQL Quoting routine
function dbsq($string) { return "'".mysql_real_escape_string($string)."'"; }

//
//
//
function printHeader() {
	global $tpl, $templateDir, $tvars;

	// Print installation header
	$tpl -> template('header', $templateDir);
	$tpl -> vars('header', $tvars);
	echo $tpl -> show('header');
}

function doWelcome() {
 global $tpl, $tvars, $templateDir;

 // Print header
 $tvars['vars']['menu_begin'] = ' class="hover"';
 printHeader();


 // Load license
 $license = @file_get_contents(root.'../license.html');
 if (!$license) {
 	$license = '<b>Ошибка!</b><br/>Не удалось загрузить лицензионный файл!';
 	$tvars['vars']['ad'] = 'disabled="disabled" ';
 } else {
 	$tvars['vars']['ad'] = '';
 }

 $tvars['vars']['license'] = $license;
 $tpl -> template('welcome', $templateDir);
 $tpl -> vars('welcome', $tvars);
 echo $tpl ->show('welcome');
}

// Вывод формы для ввода параметров установки
function doConfig() {
	global $home_url, $ERR, $tvars, $tpl, $templateDir;

	switch ($_POST['stage']) {
		default:
			doConfig_db(0);
		 	break;
		 case '1':
		 	if (!doConfig_db(1))
		 		break;

		 	doConfig_perm();
		 	break;
		 case '2':
		 	doConfig_plugins();
		 	break;
		 case '3':
		 	doConfig_templates();
		 	break;
		 case '4':
		 	doConfig_common();
		 	break;
	}
}

function doConfig_db($check) {
        global $tvars, $tpl, $templateDir, $SQL_VERSION;

        $myparams = array('action', 'stage', 'reg_dbhost', 'reg_dbname', 'reg_dbuser', 'reg_dbpass', 'reg_dbprefix', 'reg_autocreate', 'reg_dbadminuser', 'reg_dbadminpass');
        $DEFAULT = array( 'reg_dbhost' => 'localhost', 'reg_dbprefix' => 'ng' );

        // Show form
	$hinput = array();
	foreach ($_POST as $k => $v)
		if (array_search($k, $myparams) === FALSE)
			$hinput[] = '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars ($v).'"/>';
	$tvars['vars']['hinput'] = join("\n", $hinput);
	$tvars['vars']['error_message'] = '';
    if ($check) {
    	// Check passed parameters. Check for required params
    	$error = 0;
    	foreach (array('reg_dbhost', 'reg_dbname', 'reg_dbuser') as $k) {
    		if (!strlen($_POST[$k])) {
    			$tvars['vars']['err:'.$k] = '<font color="red">НЕОБХОДИМО ЗАПОЛНИТЬ</font>';
    			$error++;
    		}
		}


		// Check for autocreate mode
		if ($_POST['reg_autocreate']) {
			// Check for user filled
			if (!strlen($_POST['reg_dbadminuser'])) {
				$tvars['vars']['err:reg_dbadminuser'] = '<font color="red">При выборе режима `автосоздание...` Вам необходимо заполнить это поле</font>';
				$error++;
			}
			$ac = 1;
		}

		// Try to connect
		if (!$error) {
			if (($link = @mysql_connect($_POST['reg_dbhost'], $_POST['reg_db'.($ac?'admin':'').'user'], $_POST['reg_db'.($ac?'admin':'').'pass'])) === FALSE) {
				$tvars['vars']['error_message'] = '<div class="errorDiv">Ошибка подключения к серверу БД "'.$_POST['reg_dbhost'].'":<br/> ('.mysql_errno().') '.mysql_error().'</div>';
				$error = 1;
			}
		}
		// Try to fetch SQL version
		if (!$error) {
			if (($sqlf = @mysql_query("show variables like 'version'", $link)) === FALSE) {
				$tvars['vars']['error_message'] = '<div class="errorDiv">Ошибка определения версии сервера БД "'.$_POST['reg_dbhost'].'":<br/> ('.mysql_errno().') '.mysql_error().'</div>';
				$error = 1;
			} else {
				$sqlr = mysql_fetch_array($sqlf);
				if (preg_match('/^(\d+)\.(\d+)/', $sqlr[1], $regex)) {
					$SQL_VERSION = array ($sqlr[1], intval($regex[1]), intval($regex[2]));
				} else {
					$SQL_VERSION = $sqlr[1];
				}
			}
		}

		@mysql_close($link);

    	if (!$error)
    		return true;

    }

	foreach (array(	'reg_dbhost', 'reg_dbuser', 'reg_dbpass', 'reg_dbname', 'reg_dbprefix',
			'reg_autocreate', 'reg_dbadminuser', 'reg_dbadminpass') as $k) {
		$tvars['vars'][$k] = htmlspecialchars(isset($_POST[$k])?$_POST[$k]:$DEFAULT[$k]);
		if (!isset($tvars['vars']['err:'.$k])) $tvars['vars']['err:'.$k] = '';
	}
	if ($_POST['reg_autocreate'])
		$tvars['vars']['reg_autocreate'] = 'checked="checked"';

	$tvars['vars']['menu_db'] = ' class="hover"';
	printHeader();

	// Выводим форму проверки
	$tpl -> template('config_db', $templateDir);
	$tpl -> vars('config_db', $tvars);
	echo $tpl -> show('config_db');

	return false;
}


function doConfig_perm() {
        global $tvars, $tpl, $templateDir, $installDir, $adminDirName, $SQL_VERSION;
	$tvars['vars']['menu_perm'] = ' class="hover"';
	printHeader();

	// Error flag
	$error = 0;
	$warning = 0;

	$chmod = '';
	// Check file permissions
	$permList = array ( '.htaccess', 'uploads/', 'uploads/avatars/', 'uploads/files/',
		'uploads/images/', 'uploads/photos/', 'uploads/dsn/', $adminDirName.'/backups/',
		$adminDirName.'/cache/', $adminDirName.'/conf/');
	foreach ($permList as $dir) {
		$perms = (($x=@fileperms($installDir.'/'.$dir))===FALSE)?'n/a':(decoct($x) % 1000);
		$chmod .= '<tr><td>./'.$dir.'</td><td>'.$perms.'</td><td>'.(is_writable($installDir.'/'.$dir)?'Разрешен':'<font color="red"><b>Нет доступа</b></font>').'</td></tr>';
		if (!is_writable($installDir.'/'.$dir))
			$error++;
	}

	$tvars['vars']['chmod'] = $chmod;

	// PHP Version
	if (version_compare(phpversion(), '4.3.2') < 0) {
		$tvars['vars']['php_version'] = '<font color="red">'.phpversion().'</font>';
		$error = 1;
	} else {
		$tvars['vars']['php_version'] = phpversion();
	}

	// SQL Version
	if (!is_array($SQL_VERSION)) {
		$tvars['vars']['sql_version'] = '<font color="red">unknown</font>';
		$error = 1;
	} else {
		if (($SQL_VERSION[1] < 3) || (($SQL_VERSION[1] == 3)&&($SQL_VERSION[2] < 23))) {
			$tvars['vars']['sql_version'] = '<font color="red">'.$SQL_VERSION[0].'</font>';
			$error = 1;
		} else {
			$tvars['vars']['sql_version'] = $SQL_VERSION[0];
		}
	}

	// GZIP support
	if (extension_loaded('zlib') && function_exists('ob_gzhandler')) {
		$tvars['vars']['gzip'] = 'Да';
	} else {
		$tvars['vars']['gzip'] = '<font color="red">Нет</font>';
		$error = 1;
	}

	// XML support
	if (function_exists('xml_parser_create')) {
		$tvars['vars']['xml'] = 'Да';
	} else {
		$tvars['vars']['xml'] = '<font color="red">Нет</font>';
		$error = 1;
	}

	// GD support
	if (function_exists('imagecreatetruecolor')) {
		$tvars['vars']['gdlib'] = 'Да';
	} else {
		$tvars['vars']['gdlib'] = '<font color="red">Нет</font>';
		$error = 1;
	}


	//
	// PHP features configuraton
	//

	// * flags that should be turned off
	foreach (array('register_globals', 'magic_quotes_gpc', 'magic_quotes_runtime', 'magic_quotes_sybase') as $flag) {
		$tvars['vars']['flag:'.$flag]     = ini_get($flag)?'<font color="red">Включено</font>':'Отключено';
		if (ini_get($flag)) { $warning++; }
	}
	// * flags that should be turned on
	foreach (array('allow_call_time_pass_reference') as $flag) {
		$tvars['vars']['flag:'.$flag]     = ini_get($flag)?'Включено':'<font color="red">Отключено</font>';
		if (!ini_get($flag)) { $warning++; }
	}

	if ($error) {
		$tvars['vars']['error_message'] .= '<div class="errorDiv"><b><u>Ошибка</u>!</b><br/>Ваш хостинг не соответствует минимальным требованиям системы.<br/>Вы можете продолжить инсталляцию на свой страх и риск, но следует учитывать, что велика вероятность неверной работы. Попробуйте изменить настройки Вашего хостинга или сменить хостинг-провайдера.</div>';
	}
	if ($warning) {
		$tvars['vars']['error_message'] .= '<div class="warningDiv"><b><u>Внимание</u>!</b><br/>Некоторые настройки Вашего хостинг-провайдера отличаются от рекомендованных.<br/>Система сможет самостоятельно решить проблемы, но эффективность работы будет снижена.<br/>Рекомендуем по возможности установить параметры в соответствии с требованиями системы.</div>';
	}

	$tvars['regx']["'\[error_button\](.*?)\[/error_button\]'si"] = ($error || $warning)?'$1':'';

        $myparams = array('action', 'stage');

        // Show form
	$hinput = array();
	foreach ($_POST as $k => $v)
		if (array_search($k, $myparams) === FALSE)
			$hinput[] = '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars ($v).'"/>';
	$tvars['vars']['hinput'] = join("\n", $hinput);

	// Выводим форму проверки
	$tpl -> template('config_perm', $templateDir);
	$tpl -> vars('config_perm', $tvars);
	echo $tpl -> show('config_perm');
}

function doConfig_plugins() {
        global $tvars, $tpl, $templateDir, $installDir, $adminDirName, $SQL_VERSION;
	$tvars['vars']['menu_plugins'] = ' class="hover"';
	printHeader();

	// Now we should scan plugins for preinstall configuration
	$pluglist = array();

	$pluginsDir = root.'plugins';
	if ($dRec = opendir($pluginsDir)) {
		while (($dName = readdir($dRec)) !== false) {
			if (($dName == '.')||($dName == '..'))
				continue;

			if (is_dir($pluginsDir.'/'.$dName) && file_exists($vfn = $pluginsDir.'/'.$dName.'/version') && (filesize($vfn)) && ($vf = @fopen($vfn, 'r'))) {

				$pluginRec = array();
				while (!feof($vf)) {
					$line = fgets($vf);
					if (preg_match("/^(.+?) *\: *(.+?) *$/i", trim($line), $m)) {
						if (in_array(strtolower($m[1]), array('id', 'title', 'information', 'preinstall', 'preinstall_vars', 'install')))
							$pluginRec[strtolower($m[1])] = $m[2];
					}
			        }
			        fclose($vf);
			        if (isset($pluginRec['id']) && isset($pluginRec['title']))
			        	array_push($pluglist, $pluginRec);
			}
		}
		closedir($dRec);
	}

	// Prepare array for input list
	$hinput = array();
	// Collect data for all plugins

	$ouput = '';
	$tpl -> template('config_prow', $templateDir);
	foreach ($pluglist as $plugin) {
		$tv = array (	'id' => $plugin['id'],
				'title' => $plugin['title'],
				'information' => $plugin['information'],
				'enable' => (in_array(strtolower($plugin['preinstall']), array('yes', 'no')))?' disabled="disabled"':'',
			);
		// Add hidden field for DISABLED plugins
		if (strtolower($plugin['preinstall']) == 'yes') {
			$output .= '<input type="hidden" name="plugin:'.$plugin['id'].'" value="1"/>'."\n";
		}

		if (isset($_POST['plugin:'.$plugin['id']])) {
			$tv['check'] = $_POST['plugin:'.$plugin['id']]?' checked="checked"':'';
		} else {
			$tv['check'] = (in_array(strtolower($plugin['preinstall']), array('default_yes', 'yes')))?' checked="checked"':'';
		}

		//$hinput[] = '<input type="hidden" name="plugin:'.$plugin['id'].'" value="0"/>';

		$tpl -> vars('config_prow', array('vars' => $tv));
		$output .= $tpl -> show('config_prow');
	}
	$tvars['vars']['plugins'] = $output;

        // Show form
        $myparams = array('action', 'stage');
	foreach ($_POST as $k => $v)
		if ((array_search($k, $myparams) === FALSE) && (!preg_match('/^plugin\:/', $k)))
			$hinput[] = '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars ($v).'"/>';
	$tvars['vars']['hinput'] = join("\n", $hinput);

	// Выводим форму проверки
	$tpl -> template('config_plugins', $templateDir);
	$tpl -> vars('config_plugins', $tvars);
	echo $tpl -> show('config_plugins');
}

function doConfig_templates() {
        global $tvars, $tpl, $templateDir, $installDir, $adminDirName, $homeURL, $SQL_VERSION;
	$tvars['vars']['menu_template'] = ' class="hover"';
	printHeader();


	// Now we should scan templates for version information
	$tlist = array();

	$tDir = $installDir.'/templates';
	if ($dRec = opendir($tDir)) {
		while (($dName = readdir($dRec)) !== false) {
			if (($dName == '.')||($dName == '..'))
				continue;

			if (is_dir($tDir.'/'.$dName) && file_exists($vfn = $tDir.'/'.$dName.'/version') && (filesize($vfn)) && ($vf = @fopen($vfn, 'r'))) {

				$tRec = array('name' => $dName);
				while (!feof($vf)) {
					$line = fgets($vf);
					if (preg_match("/^(.+?) *\: *(.+?) *$/i", trim($line), $m)) {
						if (in_array(strtolower($m[1]), array('id', 'title', 'author', 'version', 'reldate', 'plugins', 'image', 'imagepreview')))
							$tRec[strtolower($m[1])] = $m[2];
					}
			        }
			        fclose($vf);
			        if (isset($tRec['id']) && isset($tRec['title']))
			        	array_push($tlist, $tRec);
			}
		}
		closedir($dRec);
	}

	// Set default template name
	if (!isset($_POST['template']))
		$_POST['template'] = 'default';

	$output = '';

        foreach ($tlist as $trec) {
        	$trvars = array( 'vars' => $trec );
        	$trvars['vars']['checked'] = ($_POST['template'] == $trec['name'])?' checked="checked"':'';
        	$trvars['vars']['templateURL'] = $homeURL.'/templates';

		$tpl -> template('config_templates_rec', $templateDir);
		$tpl -> vars('config_templates_rec', $trvars);
		$output .= $tpl -> show('config_templates_rec');
        }
	$tvars['vars']['templates'] = $output;

        $myparams = array('action', 'stage', 'template');
        // Show form
	$hinput = array();
	foreach ($_POST as $k => $v)
		if (array_search($k, $myparams) === FALSE)
			$hinput[] = '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars ($v).'"/>';
	$tvars['vars']['hinput'] = join("\n", $hinput);

	// Выводим форму проверки
	$tpl -> template('config_templates', $templateDir);
	$tpl -> vars('config_templates', $tvars);
	echo $tpl -> show('config_templates');


}

function doConfig_common() {
        global $tvars, $tpl, $templateDir, $installDir, $adminDirName, $SQL_VERSION, $homeURL;
	$tvars['vars']['menu_common'] = ' class="hover"';
	printHeader();

    $myparams = array('action', 'stage', 'admin_login', 'admin_password', 'admin_email', 'autodata', 'home_url', 'home_title');
    // Show form
	$hinput = array();
	foreach ($_POST as $k => $v)
		if (array_search($k, $myparams) === FALSE)
			$hinput[] = '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars ($v).'"/>';
	$tvars['vars']['hinput'] = join("\n", $hinput);

	// Preconfigure some paratemers
	if (!isset($_POST['home_url']))		$_POST['home_url'] = $homeURL;
	if (!isset($_POST['home_title']))	$_POST['home_title'] = 'Заголовок вашего сайта';

	foreach (array('admin_login', 'admin_password', 'admin_email', 'home_url', 'home_title') as $k) {
		$tvars['vars'][$k] = isset($_POST[$k])?htmlspecialchars($_POST[$k]):'';
	}

	$tvars['vars']['autodata_checked'] = (isset($_POST['autodata']) && ($_POST['autodata'] == '1'))?' checked="checked"':'';

	// Выводим форму проверки
	$tpl -> template('config_common', $templateDir);
	$tpl -> vars('config_common', $tvars);
	echo $tpl -> show('config_common');
}


// Генерация конфигурационного файла
function doInstall() {
	global $tvars, $tpl, $templateDir, $installDir, $adminDirName, $pluginInstallList;
	$tvars['vars']['menu_install'] = ' class="hover"';
	printHeader();

    $myparams = array('action', 'stage');
    // Show form
	$hinput = array();
	foreach ($_POST as $k => $v)
		if (array_search($k, $myparams) === FALSE)
			$hinput[] = '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars ($v).'"/>';
	$tvars['vars']['hinput'] = join("\n", $hinput);

	// Error indicator
	$frec  = array();
	$error = 0;
	$LOG   = array();
	$ERROR = array();
	do {

		// Stage #01 - Try to create config files
		foreach ( array('config.php', 'plugins.php', 'plugdata.php') as $k) {
			if (($frec[$k] = fopen(confroot.$k, 'w')) == NULL) {
				array_push($ERROR, 'Не удалось создать конфигурационный файл <b>'.$k.'</b><br/>Возможно этот файл уже существует (в этом случае Вам необходимо его удалить) или скрипту не хватает прав доступа.');
				$error = 1;
				break;
			}
			array_push($LOG, 'Создание файла "<b>'.$k.'</b>" ... OK');
		}
		array_push($LOG, '');

		if ($error) break;

		// Stage #02 - Connect to DB
		// Если заказали автосоздание, то подключаемся рутом
		if ($_POST['reg_autocreate']) {
			if (@mysql_connect($_POST['reg_dbhost'], $_POST['reg_dbadminuser'], $_POST['reg_dbadminpass'])) {
				// Успешно подключились
				array_push($LOG,'Подключение к серверу БД "'.$_POST['reg_dbhost'].'" используя административный логин "'.$_POST['reg_dbadminuser'].'" ... OK');

				// 1. Создание БД
				if (!@mysql_select_db($_POST['reg_dbname'])) {
					// БД нет. Пытаемся создать
					if (!@mysql_query('CREATE DATABASE '.$_POST['reg_dbname'])) {
						// Не удалось создать. Фатально.
						array_push($ERROR, 'Не удалось создать БД "'.$_POST['reg_dbname'].'" используя административную учётную запись. Скорее всего у данной учётной записи нет прав на создание баз данных.');
						$error = 1;
						break;
					} else {
						array_push($LOG,'Создание БД "'.$_POST['reg_dbname'].'" ... OK');
					}
				} else {
					array_push($LOG,'БД "'.$_POST['reg_dbname'].'" уже существует ... OK');
				}

				// 2. Предоставление доступа к БД
				if (!@mysql_query("grant all privileges on ".$_POST['reg_dbname'].".* to '".$_POST['reg_dbuser']."'@'".$_POST['reg_dbhost']."' identified by '".$_POST['reg_dbpass']."'")) {
					array_push($ERROR, 'Невозможно обеспечить доступ пользователя "'.$_POST['reg_dbuser'].'" к БД "'.$_POST['reg_dbname'].'" используя административные права.');
					$error = 1;
					break;
				}	else {
					array_push($LOG,'Предоставление доступа пользователю "'.$_POST['reg_dbuser'].'" к БД "'.$_POST['reg_dbname'].'" ... OK');
				}
			} else {
				array_push($ERROR, 'Невозможно подключиться к серверу БД "'.$_POST['reg_dbhost'].'" используя административный логин "'.$_POST['reg_dbadminuser'].'"');
				$error = 1;
				break;
			}
			// Отключаемся от сервера
			mysql_close();
		}

		// Подключаемся к серверу используя права пользователя
		if (!@mysql_connect($_POST['reg_dbhost'], $_POST['reg_dbuser'], $_POST['reg_dbpass'])) {
			array_push($ERROR, 'Невозможно подключиться к серверу БД "'.$_POST['reg_dbhost'].'" используя логин "'.$_POST['reg_dbuser'].'" (пароль: "'.$_POST['reg_dbpass'].'")');
			$error = 1;
			break;
		}
		array_push($LOG,'Подключение к серверу БД "'.$_POST['reg_dbhost'].'" используя логин "'.$_POST['reg_dbuser'].'" ... OK');

		// Открываем нужную БД
		if (!@mysql_select_db($_POST['reg_dbname'])) {
			array_push($ERROR, 'Невозможно открыть БД "'.$_POST['reg_dbname'].'"<br/>Вам необходимо создать эту БД самостоятельно.');
			$error = 1;
			break;
		}

		// Check if different character set are supported [ version >= 4.1.1 ]
		$charsetEngine = 0;

		if (($msq = mysql_query("show variables like 'character_set_client'"))&&(mysql_num_rows($msq))) {
			$charsetEngine = 1;
		}
		$charset = $charsetEngine?' default charset=cp1251':'';

		array_push($LOG, 'Ваша версия сервера БД mySQL '.((!$charsetEngine)?'не':'').'поддерживает множественные кодировки.');


		// Создаём таблицы в mySQL
		// 1. Проверяем наличие пересекающихся таблиц
		// 1.1. Загружаем список таблиц из БД

		$list = array();

		if (!($query = @mysql_query("show tables"))) {
			array_push($ERROR, 'Внутренняя ошибка SQL при получении списка таблиц БД. Обратитесь к автору проект за разъяснениями.');
			$error = 1;
			break;
		}

		$SQL_table = array();
		while($item = mysql_fetch_array($query, MYSQL_NUM)) {
			$SQL_table[$item[0]] = 1;
		}


		// 1.2. Парсим список таблиц
		$dbsql = explode(';',file_get_contents('trash/tables.sql'));

		// 1.3. Проверяем пересечения
		foreach ($dbsql as $dbCreateString) {
			if (!trim($dbCreateString)) { continue; }

			// Добавляем кодировку (если поддерживается)
			$dbCreateString .= $charset;

			// Получаем имя таблицы
			if (preg_match('/CREATE TABLE `(.+?)`/',$dbCreateString,$match)) {
				$tname = str_replace('XPREFIX_',$_POST['reg_dbprefix'].'_',$match[1]);
				if ($SQL_table[$tname]) {
					array_push($ERROR, 'В БД "'.$_POST['reg_dbname'].'" уже существует таблица "'.$tname.'"<br/>Используйте другой префикс для создания таблиц!');
					$error = 1;
					break;
				}
			} else {
				array_push($ERROR, 'Внутренняя ошибка парсера SQL. Обратитесь к автору проект за разъяснениями ['.$dbCreateString.']');
				$error = 1;
				break;
			}
		}
		if ($error) break;

		array_push($LOG,'Проверка наличия дублирующихся таблиц ... OK');
		array_push($LOG, '');

		$SUPRESS_CHARSET = 0;
		$SUPRESS_ENGINE  = 0;

		// 1.4. Создаём таблицы
		for ($i=0; $i<count($dbsql);$i++) {
			$dbCreateString = str_replace('XPREFIX_',$_POST['reg_dbprefix'].'_',$dbsql[$i]).$charset;

			if ($SUPRESS_CHARSET) { $dbCreateString = str_replace('default charset=cp1251','',$dbCreateString); }
			if ($SUPRESS_ENGINE) { $dbCreateString = str_replace('ENGINE=MyISAM','',$dbCreateString); }

			if (preg_match('/CREATE TABLE `(.+?)`/',$dbCreateString,$match)) {
				$tname = $match[1];
				$err = 0;
				mysql_query($dbCreateString);

				if (mysql_errno()) {
					if (!$SUPRESS_CHARSET) {
						$SUPRESS_CHARSET=1;
						array_push($LOG,'Внимание! Попытка отключить настройки кодовой страницы');
						$i--;
						continue;
					}
					if (!$SUPRESS_ENGINE) {
						$SUPRESS_ENGINE=1;
						array_push($LOG,'Внимание! Попытка отключить настройки формата хранения данных');
						$i--;
						continue;
					}
					array_push($ERROR, 'Не могу создать таблицу "'.$tname.'"!<br>Обратитесь к автору проекта за разъяснениями<br>Код SQL запроса:<br>'.$dbCreateString);
					$error = 1;
					break;
				}
				array_push($LOG,'Создание таблицы "<b>'.$tname.'</b>" ... OK');
			}
		}
		array_push($LOG,'Все таблицы успешно созданы ... OK');
		array_push($LOG, '');

		// 1.5 Создание пользователя-администратора
		$query = "insert into `".$_POST['reg_dbprefix']."_users` (`name`, `pass`, `mail`, `status`, `reg`) VALUES (".dbsq($_POST['admin_login']).", ".dbsq(md5(md5($_POST['admin_password']))).", ".dbsq($_POST['admin_email']).", '1', unix_timestamp(now()))";
		if (!@mysql_query($query)) {
			array_push($LOG,'Активация пользователя-администратора ... <font color="red">FAIL</font>');
		} else {
			array_push($LOG,'Активация пользователя-администратора ... OK');
		}
		// 1.6 Сохраняем конфигурационную переменную database.engine.version
		@mysql_query("insert into `".$_POST['reg_dbprefix']."_config` (name, value) values ('database.engine.version', '0.9.2 Release+SVN')");

		// Вычищаем лишний перевод строки из 'home_url'
		if (substr($_POST['home_url'], -1, 1) == '/')
			$_POST['home_url'] = substr($_POST['home_url'], 0, -1);

		// 1.7. Формируем конфигурационный файл
		$newconf = array(
			'dbhost'		=> $_POST['reg_dbhost'],
			'dbname'		=> $_POST['reg_dbname'],
			'dbuser'		=> $_POST['reg_dbuser'],
			'dbpasswd'		=> $_POST['reg_dbpass'],
			'prefix'		=> $_POST['reg_dbprefix'],
			'home_url'		=> $_POST['home_url'],
			'admin_url'		=> $_POST['home_url'].'/'.$adminDirName,
			'images_dir'	=> $installDir.'/uploads/images/',
			'files_dir'		=> $installDir.'/uploads/files/',
			'attach_dir'		=> $installDir.'/uploads/dsn/',
			'avatars_dir'	=> $installDir.'/uploads/avatars/',
			'photos_dir'	=> $installDir.'/uploads/photos/',
			'images_url'	=> $_POST['home_url'].'/uploads/images',
			'files_url'		=> $_POST['home_url'].'/uploads/files',
			'attach_url'		=> $_POST['home_url'].'/uploads/dsn',
			'avatars_url'	=> $_POST['home_url'].'/uploads/avatars',
			'photos_url'	=> $_POST['home_url'].'/uploads/photos',
			'home_title'	=> $_POST['home_title'],
			'admin_mail'	=> $_POST['admin_email'],
			'lock'			=> '0',
			'lock_reason'	=> 'Сайт на реконструкции!',
			'meta'			=> '1',
			'description'	=> 'Здесь описание вашего сайта',
			'keywords'		=> 'Здесь ключевые слова, через запятую (,)',
			'skin'			=> 'default',
			'theme'			=> $_POST['template'],
			'default_lang'	=> 'russian',
			'auto_backup'	=> '1',
			'auto_backup_time' => '48',
			'use_gzip'		=> '0',
			'use_captcha'	=> '1',
			'captcha_font'	=> 'verdana',
			'use_cookies'	=> '0',
			'use_sessions'	=> '1',
			'number'		=> '5',
			'category_link' => '1',
			'add_onsite' => '1',
			'add_onsite_guests' => '0',
			'date_adjust' => '0',
			'timestamp_active' => 'j Q Y',
			'timestamp_updated' => 'j.m.Y - H:i',
			'smilies' => 'smile, biggrin, tongue, wink, cool, angry, sad, cry, upset, tired, blush, surprise, thinking, shhh, kiss, crazy, undecide, confused, down, up',
			'blocks_for_reg' => '1',
			'use_smilies' => '1',
			'use_bbcodes' => '1',
			'use_htmlformatter' => '1',
			'forbid_comments' => '0',
			'reverse_comments' => '0',
			'auto_wrap' => '50',
			'flood_time' => '20',
			'timestamp_comment' => 'j.m.Y - H:i',
			'users_selfregister' => '1',
			'register_type' => '4',
			'use_avatars' => '1',
			'avatar_wh' => '65',
			'avatar_max_size' => '16',
			'use_photos' => '1',
			'photos_max_size' => '256',
			'photos_thumb_size_x' => '80',
			'photos_thumb_size_y' => '80',
			'images_ext' => 'gif, jpg, jpeg, png',
			'images_max_size' => '512',
			'thumb_size_x' => '150',
			'thumb_size_y' => '150',
			'thumb_quality' => '80',
			'wm_image' => 'stamp',
			'wm_image_transition' => '50',
			'files_ext' => 'zip, rar, gz, tgz, bz2',
			'files_max_size' => '128',
			'auth_module' => 'basic',
			'auth_db' => 'basic',
			'crypto_salt' => substr(md5(uniqid(rand(),1)),0,8),
			'404_mode' => 0,
			'debug' => 1,
			'UUID' => md5(mt_rand().mt_rand()).md5(mt_rand().mt_rand()),
		);

		array_push($LOG,"Подготовка параметров конфигурационного файла ... OK");

		// Записываем конфиг
		$confData	=	"<?php\n".'$config = '. var_export($newconf, true).";\n";

		if (!fwrite($frec['config.php'], $confData)) {
			array_push($ERROR, 'Ошибка записи конфигурационного файла!');
			$error = 1;
			break;
		}

		// Активируем плагин auth_basic
		$plugConf = array(
			'active' => array(
				'auth_basic' => 'auth_basic'
			),
			'actions' => array (
		    	'auth' => array (
					'auth_basic' => 'auth_basic/auth_basic.php',
				),
		    ),
		);

		$plugData = "<?php\n".'$array = '. var_export($plugConf, true). ";\n";
		if (!fwrite($frec['plugins.php'], $plugData)) {
			array_push($ERROR, 'Ошибка записи конфигурационного файла [список активных плагинов]!');
			$error = 1;
			break;
		}

		// А теперь - включаем необходимые плагины
		$pluginInstallList = array();
		foreach ($_POST as $k => $v) {
			if (preg_match('/^plugin\:(.+?)$/', $k, $m) && ($v == 1)) {
				array_push($pluginInstallList, $m[1]);
			}
		}

		// Закрываем все файлы
		foreach (array_keys($frec) as $k)
			fclose($frec[$k]);

		array_push($LOG,'Сохранение конфигурационного файла ... OK');

		// А теперь - включаем необходимые плагины
		include_once root."core.php";
		include_once root."includes/inc/extraconf.inc.php";
		include_once root."includes/inc/extrainst.inc.php";

		// Now let's install plugins
		// First: Load informational `version` files
		$list = get_extras_list();
		// Подготавливаем список плагинов для установки
		$pluginInstallList = array();
		foreach ($_POST as $k => $v) {
			if (preg_match('/^plugin\:(.+?)$/', $k, $m) && ($v == 1)) {
				array_push($pluginInstallList, $m[1]);
			}
		}
	} while (0);

	$output = '';
	foreach ($LOG as $line) { $output .= $line."<br />\n"; }

	if ($error) {
		$output .= "<br/>\n";
		foreach ($ERROR as $errText) {
			$output .= '<div class="errorDiv"><b><u>Ошибка</u>!</b><br/>'.$errText.'</div>';
		}

		// Make navigation menu
		$output .= '<div class="warningDiv">';
		$output .= '<input type="button" style="width: 230px;" value="Вернуться к настройке БД" onclick="document.getElementById(\'stage\').value=\'0\'; form.submit();"/> - если Вы что-то неверно ввели в настройках БД, то Вы можете исправить ошибку.<br/>';
		$output .= '<input type="button" style="width: 230px;" value="Попробовать ещё раз" onclick="document.getElementById(\'action\').value=\'install\'; form.submit();"/> - если Вы самостоятельно устранили ошибку, то нажмите сюда.';
		$output .= '</div>';
	}

	$tvars['vars']['actions'] = $output;


	// Выводим форму проверки
	$tpl -> template('config_process', $templateDir);
	$tpl -> vars('config_process', $tvars);
	print $tpl -> show('config_process');

	return $error?false:true;
}
