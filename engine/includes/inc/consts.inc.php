<?php

//
// Copyright (C) 2006-2009 Next Generation CMS (http://ngcms.ru/)
// Name: consts.inc.php
// Description: Initializing global consts
// Author: Alexey Zinchenko, Vitaly Ponomarev
//

// Determine current admin working directory
define('adminDirName', array_pop(preg_split('/(\\\|\/)/',root, -1, PREG_SPLIT_NO_EMPTY)));

@define('NGCMS', true);

@define('engineName', 'NGCMS');
@define('engineVersion', '0.9.1 Release + SVN');

@define('prefix', $config['prefix']);
@define('home', $config['home_url']);
@define('home_title', $config['home_title']);
@define('zz_url', $config['zz_url']);
@define('admin_url', isset($config['admin_url'])?$config['admin_url']:$config['zz_url']);
@define('files_dir', $config['files_dir']);
@define('files_url', $config['files_url']);
@define('images_dir', $config['images_dir']);
@define('images_url', $config['images_url']);
@define('avatars_dir', $config['avatars_dir']);
@define('avatars_url', $config['avatars_url']);
@define('photos_dir', $config['photos_dir']);
@define('photos_url', $config['photos_url']);

@define('timestamp', $config['timestamp_active']);
@define('ctimestamp', $config['timestamp_comment']);
@define('date_adjust', $config['date_adjust']);

@define('skins_url', admin_url.'/skins/default');
@define('tpl_actions', root.'skins/default/tpl/');
@define('tpl_dir', site_root.'templates/');

@define('extras_dir', root.'plugins', true);

@define('id',		intval($_REQUEST['id']));
@define('year',		intval($_REQUEST['year']));
@define('month',	intval($_REQUEST['month']));
@define('day',		intval($_REQUEST['day']));
@define('category', mysql_escape_string(strip_tags(preg_replace('[([/]+)$]', '', $_GET['category']))));
@define('altname' , mysql_escape_string(strip_tags(preg_replace('[([/]+)$]', '', $_GET['altname']))));
@define('user',		mysql_escape_string(strip_tags(preg_replace('[([/]+)$]', '', $_GET['user']))));
@define('future_news', "postdate < ".time());

@define('conf_pactive', confroot.'plugins.php', true);
@define('conf_pconfig', confroot.'plugdata.php', true);
