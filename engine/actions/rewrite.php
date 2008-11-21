<?php


//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: rewrite.php
// Description: Managing rewrite rules
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('rewrite', 'admin');
$format = $_REQUEST['format'];
$subaction = $_REQUEST['subaction'];

function save_links() {
	global $linkz, $format, $PHP_SELF, $lang;

	if (!is_array($format))	return;

	$bridge['full'] = array(
		'by_cat'	=>	$format['full_by_cat'],
		'by_date'	=>	$format['full_by_date']
	);
	$bridge['full_page'] = array(
		'by_cat'	=>	$format['full_page_by_cat'],
		'by_date'	=>	$format['full_page_by_date']
	);
	$bridge['print'] = array(
		'by_cat'	=>	$format['print_by_cat'],
		'by_date'	=>	$format['print_by_date']
	);

	unset($format['full_by_cat'], $format['full_by_date'], $format['full_page_by_cat'], $format['full_page_by_date'], $format['print_by_cat'], $format['print_by_date']);

	$format = array_merge($format, $bridge);

	if (!is_writable(confroot."links.inc.php")){
		@chmod(confroot."links.inc.php", 0777);
	}

	if (is_writable(confroot."links.inc.php")) {
		$tosave = array(
			'rewrite'	=>	$format,
			'plain'		=>	$linkz['plain']
		);

		$handler	=	fopen(confroot."links.inc.php", "w");
		$save		=	"<?php\n";
		$save		.=	'$linkz = ';
		$save		.=	var_export($tosave, true);
		$save		.=	";\n";
		$save		.=	"?>";
		fwrite($handler, $save);
		fclose($handler);

		echo "<script type=\"text/javascript\">self.location.href='$PHP_SELF?mod=rewrite&subaction=done';</script>";
	} else {
		msg(array("type" => "error", "text" => $lang['msge_save_l']));
	}
}

function make_htaccess() {
	global $linkz, $config, $PHP_SELF, $lang;

	foreach ($linkz['rewrite'] as $tag => $link) {
		if (is_array($link)) {
			$link = $config['category_link'] ? $link['by_cat'] : $link['by_date'];
		}

		$replaced = strtr($link, array(
			'{alt}'			=>	'([^/]*)',
			'{catlink}'		=>	'([^/]*)',
			'{id}'			=>	'([0-9]+)',
			'{alt_name}'	=>	'(.*)',
			'{plugin_name}'	=>	'([^/]*)',
			'{year}'		=>	'([0-9]{4})',
			'{month}'		=>	'([0-9]{2})',
			'{day}'			=>	'([0-9]{1,2})',
			'{author}'		=>	'([^/]*)',
			'{page}'		=>	'([0-9]+)',
			'{userid}'		=>	'([0-9]{1,7})',
			'{code}'		=>	'([^/]*)'
		));

		preg_match_all('/\{(.*?)\}/i', $link, $array);

		if (is_array($linkz['plain'][$tag])) {
			$link = $config['category_link'] ? $linkz['plain'][$tag]['by_cat'] : $linkz['plain'][$tag]['by_date'];
		} else {
			$link = $linkz['plain'][$tag];
		}

		for ($i = 0; $i < sizeof($array[1]); $i++){
			$link = str_replace('{'.$array[1][$i].'}', '$'.($i + 1), $link);
		}

		$line[] = 'RewriteRule ^'.$replaced.'(/?)+$ '.$link.' [QSA,L]';
	}

	if (!is_writable('../.htaccess')){
		@chmod('../.htaccess', 0777);
	}

	if (is_writable('../.htaccess')){
		$htaccess_dist = explode("# Editable links", @file_get_contents(root.'trash/htaccess-dist.txt'));
		$htaccess_dist = $htaccess_dist[0];

		$file = fopen('../.htaccess', 'w');
		fwrite($file, $htaccess_dist."# Editable links\n".join("\n", $line)."\n");
		fclose($file);

		echo "<script type=\"text/javascript\">self.location.href='$PHP_SELF?mod=rewrite&subaction=done';</script>";
	} else {
		msg(array("type" => "error", "text" => $lang['msge_save_h']));
	}
}


// ============================================================ //
// CODE                                                         //
// ============================================================ //

switch($subaction){
	case 'save':		save_links(); break;
	case 'htaccess':	make_htaccess(); break;
	case 'done':		msg(array("text" => $lang['msgo_saved'])); break;
}


include confroot.'links.inc.php';
$rwlinkz = $linkz['rewrite'];

$tvars['vars'] = array(
	'php_self'				=>	$PHP_SELF,
	'lnk_category'			=>	$rwlinkz['category'],
	'lnk_category_page'		=>	$rwlinkz['category_page'],
	'lnk_category_rss'		=>	$rwlinkz['category_rss'],
	'lnk_full_by_cat'		=>	$rwlinkz['full']['by_cat'],
	'lnk_full_by_date'		=>	$rwlinkz['full']['by_date'],
	'lnk_full_page_by_cat'	=>	$rwlinkz['full_page']['by_cat'],
	'lnk_full_page_by_date'	=>	$rwlinkz['full_page']['by_date'],
	'lnk_date'				=>	$rwlinkz['date'],
	'lnk_date_page'			=>	$rwlinkz['date_page'],
	'lnk_year'				=>	$rwlinkz['year'],
	'lnk_year_page'			=>	$rwlinkz['year_page'],
	'lnk_month'				=>	$rwlinkz['month'],
	'lnk_month_page'		=>	$rwlinkz['month_page'],
	'lnk_user'				=>	$rwlinkz['user'],
	'lnk_print_by_cat'		=>	$rwlinkz['print']['by_cat'],
	'lnk_print_by_date'		=>	$rwlinkz['print']['by_date'],
	'lnk_registration'		=>	$rwlinkz['registration'],
	'lnk_activation'		=>	$rwlinkz['activation'],
	'lnk_activation_do'		=>	$rwlinkz['activation_do'],
	'lnk_lostpassword'		=>	$rwlinkz['lostpassword'],
	'lnk_rss'				=>	$rwlinkz['rss'],
	'lnk_firstpage'			=>	$rwlinkz['firstpage'],
	'lnk_page'				=>	$rwlinkz['page'],
	'lnk_profile'			=>	$rwlinkz['profile'],
	'lnk_addnews'			=>	$rwlinkz['addnews'],
	'lnk_static'			=>	$rwlinkz['static'],
	'lnk_plugins'			=>	$rwlinkz['plugins'],
);

$tpl -> template('rewrite', tpl_actions);
$tpl -> vars('rewrite', $tvars);
echo $tpl -> show('rewrite');
