<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: upgrade.php
// Description: upgrading 2z project
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

@define('root', dirname(__FILE__).'/');
@error_reporting (E_ALL ^ E_NOTICE);

//
// Check if we have upgrademe.txt file
//
if (!is_file(root.'upgrademe.txt')) {
	echo "������! ��� ������� ������� ���������� ��� ���������� � �������� engine/ ������� ���� <b>upgrademe.txt</b> � ������� ��� ����� ����������.";
	return;
}

// �������� ���������� ���������
if (!$_REQUEST['doupgrade']) {
	questionare_097();
	exit;
}

if ($_REQUEST['config_move']) {

	//
	// Check if we have config dir
	//
	if (!is_dir(root.'conf')) {
		if (!mkdir(root.'conf',0775)) {
			echo "������! �� ���� ������� ������� ��� ���������������� ������. ��� ���������� ������� ������� ������� '".root."conf' � ���������� �� ���� ����� 0777";
			return;
		}
	}

	//
	// Check if we have config file
	//
	if (!is_file(root.'conf/config.inc.php')) {
		// Check if we have an old config-file
		if (is_file(root.'includes/inc/config.inc.php')) {
			if (!copy(root.'includes/inc/config.inc.php', root.'conf/config.inc.php')) {
				echo "������! �� ���� ����������� ������-���� �� ����� �����! ���������� ����� ������� 0777 �� ������� '".root."/conf'";
				return;
			}
		}
	}

	//
	// Check for plugin-activation file
	//
	if (!is_file(root.'conf/plugins.php')) {
		// Check if we have an old plugins activation file
		if (is_file(root.'includes/inc/extras-on.inc.php')) {
			if (!copy(root.'includes/inc/extras-on.inc.php', root.'conf/plugins.php')) {
				echo "������! �� ���� ����������� ���� '".root."includes/inc/extras-on.inc.php' �� ����� ����� ('".root."conf/plugins.php'). ��������� ��� �������� ������� � ���������� ����� 0777 �� ����� ����";
				return;
			}
		}
	}

	//
	// Check for plugin-data file
	//
	if (!is_file(root.'conf/plugdata.php')) {
		// Check if we have an old plugins activation file
		if (is_file(root.'includes/inc/modules_config.inc.txt')) {
			if (!copy(root.'includes/inc/modules_config.inc.txt', root.'conf/plugdata.php')) {
				echo "������! �� ���� ����������� ���� '".root."includes/inc/modules_config.inc.txt' �� ����� ����� ('".root."conf/plugdata.php'). ��������� ��� �������� ������� � ���������� ����� 0777 �� ����� ����";
				return;
			}
		}
	}

	//
	// Check for links.inc.php file
	//
	if (!is_file(root.'conf/links.inc.php')) {
		// Check if we have an old links file
		if (is_file(root.'includes/inc/links.inc.php')) {
			if (!copy(root.'includes/inc/links.inc.php', root.'conf/links.inc.php')) {
				echo "������! �� ���� ����������� ���� '".root."includes/inc/links.inc.php' �� ����� ����� ('".root."conf/links.inc.php'). ��������� ��� �������� ������� � ���������� ����� 0777 �� ����� ����";
				return;
			}
		}
	}

}

//
// Check for DISABLING .htaccess file
//
if (!is_file(root.'conf/.htaccess')) {
	if (!($fa = fopen(root.'conf/.htaccess', 'w'))) {
		echo "������! �� ���� ������� ���� '".root."conf/.htaccess'. ���������� ����� 0777 �� ������� '".root."/conf'";
		return;
	}
	fwrite($fa,"<files *>\n Order Deny,Allow\n Deny from All\n</files>\n\n");
	fclose($fa);
}

@include_once 'core.php';
@include_once root.'includes/inc/extraconf.inc.php';
@include_once root.'includes/inc/extrainst.inc.php';

@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Pragma: no-cache");
$PHP_SELF = "admin.php";

if (($config['skin'] && $config['skin'] != "") && file_exists("./skins/$config[skin]/index.php")) {
	require_once("./skins/$config[skin]/index.php");
}
else { require_once("./skins/default/index.php"); }
echo $skin_header;

//
// Create required fields in DB
//
$db_update = array(
	array(
		'table' => 'users',
		'action' => 'modify',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'ip',  'type' => 'char(15)', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'authcookie', 'type' => 'char(50)'),
			array('action' => 'cmodify', 'name' => 'newpw', 'type' => 'char(32)'),
			array('action' => 'cmodify', 'name' => 'posts', 'type' => 'int', 'params' => 'default 0'),
		)
	),
	array(
		'table' => 'category',
		'action' => 'modify',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'alt_url',  'type' => 'text'),
			array('action' => 'cmodify', 'name' => 'orderby', 'type' => 'char(30)', 'params' => "default 'id desc'"),
			array('action' => 'cmodify', 'name' => 'posts', 'type' => 'int', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'posorder', 'type' => 'int', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'poslevel', 'type' => 'int', 'params' => 'default 0'),
		)
	),
	array(
		'table' => 'news',
		'action' => 'modify',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'author_id', 'type' => 'int', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'flags', 'type' => 'int(1)', 'params' => 'default 0'),
		)
	),
	array(
		'table' => 'comments',
		'action' => 'modify',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'author_id', 'type' => 'int', 'params' => 'default 0'),
		)
	),
	array(
		'table' => 'static',
		'action' => 'cmodify',
		'key' => 'primary key(id)',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'id',  'type' => 'int', 'params' => 'not null auto_increment'),
			array('action' => 'cmodify', 'name' => 'title', 'type' => 'char(255)'),
			array('action' => 'cmodify', 'name' => 'alt_name', 'type' => 'char(255)'),
			array('action' => 'cmodify', 'name' => 'template', 'type' => 'char(100)', 'params' => 'not null'),
			array('action' => 'cmodify', 'name' => 'content', 'type' => 'text'),
			array('action' => 'cmodify', 'name' => 'description', 'type' => 'text'),
			array('action' => 'cmodify', 'name' => 'keywords', 'type' => 'text'),
			array('action' => 'cmodify', 'name' => 'approve',  'type' => 'tinyint(1)', 'params' => 'not null'),
			array('action' => 'cmodify', 'name' => 'flags', 'type' => 'int(1)', 'params' => 'default 0'),
		)
	),
	array(
		'table' => 'images',
		'action' => 'modify',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'owner_id', 'type' => 'int', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'stamp', 'type' => 'int', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'category', 'type' => 'int', 'params' => 'default 0'),
		)
	),
	array(
		'table' => 'files',
		'action' => 'modify',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'owner_id', 'type' => 'int', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'category', 'type' => 'int', 'params' => 'default 0'),
		)
	),
);

// Load plugin list
$extras	=	get_extras_list();
// Load lang files
$lang	=	LoadLang('extra-config', 'admin');

if ($_REQUEST['db_update']) {
	if (fixdb_plugin_install('upgrade', $db_update)) {
		echo "<br /><br />���������� �� ��������� �������<br /><br />�������������� ��������� ������...\n";

		// news
		foreach ($mysql->select("select n.*, u.id as uid from ".prefix."_news as n left join ".uprefix."_users u on n.author=u.name where (n.author_id is NULL) or (n.author_id < 1)") as $row) {
			$mysql->query("update ".prefix."_news set author_id = '".$row['uid']."' where id = ".$row['id']);
		}

		// comments
		foreach ($mysql->select("select c.*, u.id as uid from ".prefix."_comments as c left join ".uprefix."_users u on c.author=u.name where ((c.author_id is NULL) or (c.author_id < 1)) and (c.reg = 1)") as $row) {
			$mysql->query("update ".prefix."_comments set author_id = '".$row['uid']."' where id = ".$row['id']);
		}

		echo "OK<br /><br />\n";
		echo "�������� ����������� �������� (���� ��������� ������ - �� ��������� �� ��� ��������, ��� ������� ��������)...";
		$indexlist = array(	"create index c_post on ".prefix."_comments (post)",
					"create index alt_name on ".prefix."_news (alt_name)",
					"create index news_title on ".prefix."_news (title)",
					"create index news_postdate on ".prefix."_news (postdate)",
					"create index news_catid on ".prefix."_news (catid)",
					"create index news_view on ".prefix."_news (views)",
					"create index static_title on ".prefix."_static (title)",
					"create index users_name on ".prefix."_users (name)",
					"create index users_auth on ".prefix."_users (authcookie)");

		foreach ($indexlist as $i) {
			$mysql->query($i);
		}
		echo "DONE <br><br>\n";
	} else {
		echo "������: �� ������� �������� ��<br />\n";
	}
}

// Now, let's check for auth_basic module
if (is_array($extras['auth_basic'])) {
	// Found
	if (!getPluginStatusActive('auth_basic')) {
		if (pluginSwitch('auth_basic', 'on')) {
			echo "������ 'auth_basic' �����������!<br />\n";
		} else {
			echo "������: �� ���� ������������ ������ 'auth_basic'<br />\n";
		}
	}
} else {
	echo "������: ������ 'auth_basic' �� ������! ��� ���� ���������� �������������� �������. ���������� ������ ������ � ��������� ������ ������ ��� ���.<br />\n";
}



if ($_REQUEST['news_html']) {
	$mysql->query("update ".prefix."_news set flags=2 where flags=0");
	$mysql->query("update ".prefix."_static set flags=2 where flags=0");
	print "<font color='blue'><b>*</b></font> ���� '��������� HTML ��� � ��������' ���������� ��� ���� ��������/������ �������<br/>";
}

if ($_REQUEST['news_br']) {
	$mysql->query("update ".prefix."_news set content=replace(content,'<br />', '\\n')");
	$mysql->query("update ".prefix."_static set content=replace(content,'<br />', '\\n')");
	print "<font color='blue'><b>*</b></font> ������ ���� '&lt;br /&gt;' �� ���� ��������/������ ��������� ���������!<br/>";
}

if ($_REQUEST['avatar_names']) {
	$mysql->query("update ".uprefix."_users set avatar=concat(id,'.',avatar) where (avatar <> '') and (avatar not like concat(id,'.%'))");
	print "<font color='blue'><b>*</b></font> �������������� ��� �������� ���������!<br/>";
}

if ($_REQUEST['photos_thumbs']) {
        $dir = root.'../uploads/photos';
        mkdir($dir.'/thumb');
        $DIR = opendir($dir);
        while (($fn = readdir($DIR)) !== false) {
        	if (preg_match('/^\d+\.thumb\./', $fn)) {
        		rename($dir.'/'.$fn, $dir.'/thumb/'.str_replace('.thumb','',$fn));
        	}	
        }
        closedir($DIR);

	print "<font color='blue'><b>*</b></font> ����������� ����������� ����� ���������� ���������!<br/>";
}

print "��� �������� ���������.<br/><a href='?'>�����</a>";

function questionare_097() {
 print "
 <style>BODY {PADDING-RIGHT: 8px; PADDING-LEFT: 8px; PADDING-TOP: 5px; PADDING-BOTTOM: 0px; MARGIN: 0px; COLOR: #333; FONT-FAMILY: 'Trebuchet MS', Verdana, Arial, sans-serif; BACKGROUND-COLOR: #fff; }</style>


 <form method='get' action=''>
 <input type=hidden name='doupgrade' value='1'/>
 <b><u>����� ������� ���������� ��� ���������� �������� �� ��������� ��������:</u></b><br /><br />
 <font color='red'><b>��������: </b> ����� ������� ���������� ��� <u>�����������</u> ���������� �������
 ��������� ����� ��</font><br/><br/>
 <table width='80%' border='1'>
 <tr>
  <td>��������� ������� ���������������� ������<br/>
  <small>������ �������� ��������� ��� ���������� �� ������ ������ (0.9.5-0.9.6).<br/>
  ��� �������� � ������ 0.9.7 alpha � ������ - �� ���������</td>
  <td width='10%'><input type=checkbox name='config_move' value='1' /></td></tr>
 <tr>
  <td>��������� ���������� ��������� ��<br/>
  <small>������ �������� ��������� ���������� �������� ��� ���������� �� ������ ������.<br/>
  ��� �������� � ����� 0.9.7 beta2 - �� ���������</td>
  <td width='10%'><input type=checkbox name='db_update' value='1' /></td></tr>
 <tr>
  <td>������������� ���� �������� ���� '��������� HTML ��� � ��������/������ ���������'<br />
  <small>������ ���� ���������� ���������� ��� ���������� �� ������ 0.9.7, �����
  HTML ���� � ����� ��������/������ ��������� ������������ �� �����.</small></td>
  <td width='10%'><input type=checkbox name='news_html' value='1' /></td></tr>
 <tr>
  <td>�������� ���������� ������ '&lt;br/&gt;' � ��������/������ ��������� �� ������� ������<br />
  <small>� ����� ������ ������� �������� � �� ��� ���������, � ������ �� ������� �����
  ��������� �� '&lt;br/&gt;'. <br/>���������� �������� ��� ��������� ������������� ��� ��������������
  ������ �������/������ ��������.</small></td>
  <td width='10%'><input type=checkbox name='news_br' value='1' /></td></tr>
 <tr>
  <td>������������� ������ �� ������� �������������<br/>
  <small>��-�� ������������� ��������� �������� ���������� �� �������� �������������,
  ��� ���������� �������� �������������� ������.<br />
  <font color='red'><b>��������:</b> ������ �������� <u>����</u> ��������� � �������
  ������������ ��� ID � ������ ����� (� ������ ���� ��������� �������� ��� ���).</b></font></small></td>
  <td width='10%'><input type=checkbox name='avatar_names' value='1' /></td>
 </tr>
 <tr>
  <td>���������� ����������� ����� ���������� �������������<br/>
  <small>��-�� ������������� ��������� �������� ���������� � ����������� �������������,
  ��� ���������� ����������� � ������������� ����� ����������� �����.<br />
  <font color='red'><b>��������:</b> ������ �������� ������ ���������� ����� ���������� ���� <b>�����</b>.thumb.<b>���-�� ���</b>
  � ���������� thumb/, ������ �� �������� ����� '.thumb'.</font></small></td>
  <td width='10%'><input type=checkbox name='photos_thumbs' value='1' /></td>
 </tr>
 </table><br/>
 <input type='submit' value='������ ��������������!'>
 </form>
 ";
}
