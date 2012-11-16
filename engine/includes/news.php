<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: news.php
// Description: News display sub-engine
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('news', 'site');

// Load shared library
include_once root.'includes/inc/libnews.php';



// ================================================================= //
// Module code                                                       //
// ================================================================= //

// Default "show news" function
function showNews($handlerName, $params) {
 global $catz, $catmap, $template, $config, $userROW, $PFILTERS, $lang, $SYSTEM_FLAGS, $SUPRESS_TEMPLATE_SHOW, $tpl, $parse, $currentCategory;
 // preload plugins
 load_extras('news');

 // Init array with configuration parameters
 $callingParams = array('customCategoryTemplate' => 1, 'customCategoryNumber' => 1, 'setCurrentCategory' => 1, 'setCurrentNews' => 1);
 $callingCommentsParams = array();

 // Set default template path
 $templatePath = tpl_dir.$config['theme'];

 // Check for FULL NEWS mode
 if (($handlerName == 'news')||($handlerName == 'print')) {
 	$flagPrint = ($handlerName == 'print')?true:false;
	if ($flagPrint)
		$SUPRESS_TEMPLATE_SHOW = true;

	$callingParams['style'] = $flagPrint?'print':'full';

	// Execute filters [ onBeforeShow ] ** ONLY IN 'news' mode. In print mode we don't use it
	if (!$flagPrint && is_array($PFILTERS['news'])) {
		foreach ($PFILTERS['news'] as $k => $v) { $v->onBeforeShow('full'); }
	}

	// Determine passed params
	$vars = array('id' => 0, 'altname' => '');
	if (isset($params['id'])) {
		$vars['id'] = $params['id'];
	} else if (isset($params['zid'])) {
		$vars['id'] = $params['zid'];
	} else if (isset($params['altname'])) {
		$vars['altname'] = $params['altname'];
	} else if (isset($_REQUEST['id'])) {
		$vars['id'] = intval($_REQUEST['id']);
	} else if (isset($_REQUEST['zid'])) {
		$vars['id'] = intval($_REQUEST['zid']);
	} else {
		$vars['altname'] = $_REQUEST['altname'];
	}

 	if (isset($params['category'])) {
 		$callingParams['validateCategoryAlt'] = $params['category'];
 	}
 	if (isset($params['catid'])) {
 		$callingParams['validateCategoryID'] = $params['catid'];
 	}

	$callingParams['addCanonicalLink']	= true;

 	// Try to show news
	if (($row = news_showone($vars['id'], $vars['altname'], $callingParams)) !== false) {
		// Execute filters [ onAfterShow ] ** ONLY IN 'news' mode. In print mode we don't use it
		if (!$flagPrint && is_array($PFILTERS['news'])) {
			foreach ($PFILTERS['news'] as $k => $v) { $v->onAfterNewsShow($row['id'], $row, array('style' => 'full')); }
		}
	 }
} else {
 	$callingParams['style'] = 'short';
 	$callingParams['page']  = (isset($params['page']) && intval($params['page']))?intval($params['page']):(isset($_REQUEST['page'])?intval($_REQUEST['page']):0);

	// Execute filters [ onBeforeShow ]
	if (is_array($PFILTERS['news'])) {
		foreach ($PFILTERS['news'] as $k => $v) { $v->onBeforeShow('short'); }
	}

	switch ($handlerName) {
		case 'main':
			$SYSTEM_FLAGS['info']['title']['group'] = $lang['mainpage'];
		    $paginationParams = checkLinkAvailable('news', 'main')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'main', 'params' => array(), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'main'), 'xparams' => array(), 'paginator' => array('page', 1, false));

			if ($config['default_newsorder'] != '')
				$callingParams['newsOrder'] = $config['default_newsorder'];

			$template['vars']['mainblock'] .= news_showlist(array('DATA', 'mainpage', '=', '1'), $paginationParams, $callingParams);
			break;

		case 'all':
			$SYSTEM_FLAGS['info']['title']['group'] = $lang['allnews'];
			$paginationParams = checkLinkAvailable('news', 'all')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'all', 'params' => array(), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'all'), 'xparams' => array(), 'paginator' => array('page', 1, false));

			if ($config['default_newsorder'] != '')
				$callingParams['newsOrder'] = $config['default_newsorder'];

			$template['vars']['mainblock'] .= news_showlist(array(), $paginationParams, $callingParams);
			break;

		case 'by.category':
			$category = '';
			if (isset($params['catid'])) {
				$category = $params['catid'];
			} else if (isset($params['category']) && isset($catz[$params['category']])) {
				$category = $catz[$params['category']]['id'];
			} else if (isset($_REQUEST['catid'])) {
				$category = $params['catid'];
			} else if (isset($_REQUEST['category']) && isset($catz[$_REQUEST['category']])) {
				$category = $catz[$_REQUEST['category']]['id'];
			}

			// We can't show unexisted categories
			if (!$category || !isset($catmap[$category])) {
				if (!$params['FFC']) {
					error404();
				}
				return false;
			}
			$currentCategory = $catz[$catmap[$category]];

			// Save current category identifier
			$SYSTEM_FLAGS['news']['currentCategory.alt']	= $currentCategory['alt'];
			$SYSTEM_FLAGS['news']['currentCategory.id']		= $currentCategory['id'];
			$SYSTEM_FLAGS['news']['currentCategory.name']	= $currentCategory['name'];

			// Set title
			$SYSTEM_FLAGS['info']['title']['group'] = $currentCategory['name'];

			// Set meta tags for category page
			if ($currentCategory['description'])
				$SYSTEM_FLAGS['meta']['description'] = $currentCategory['description'];
			if ($currentCategory['keywords'])
				$SYSTEM_FLAGS['meta']['keywords']    = $currentCategory['keywords'];

			// Set personal `order by` for category
			if ($currentCategory['number'])
					$callingParams['showNumber'] = $currentCategory['number'];

			// Set number of `news per page` if this parameter is filled in category
			if ($currentCategory['orderby'])
				$callingParams['newsOrder'] = $currentCategory['orderby'];

			$paginationParams = checkLinkAvailable('news', 'by.category')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'by.category', 'params' => array('category' => $catmap[$category]), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'by.category'), 'xparams' => array('category' => $catmap[$category]), 'paginator' => array('page', 1, false));

			// Sort news for `category` mode
			$callingParams['pin'] = 1;

			// Generate news content
			$newsContent = news_showlist(array('DATA', 'category', '=', $category), $paginationParams, $callingParams);

			// Check if template 'news.table.tpl' exists [first check custom category template (if set), after that - common template for the whole site
			$ntTemplatePath = '';
			$ntTemplateFound = false;
			if ($currentCategory['tpl'] && file_exists(tpl_dir.$config['theme'].'/ncustom/'.$currentCategory['tpl'].'/news.table.tpl')) {
				$ntTemplateFound	= true;
				$ntTemplatePath		= tpl_dir.$config['theme'].'/ncustom/'.$currentCategory['tpl'];
			} else if (file_exists(tpl_dir.$config['theme'].'/news.table.tpl')) {
				$ntTemplateFound	= true;
				$ntTemplatePath		= tpl_dir.$config['theme'];
			}

			if ($ntTemplateFound) {
				$tpl->template('news.table', $ntTemplatePath);
				$tnvars = array('vars' => array(
					'category.id'	=> $currentCategory['id'],
					'category.alt'	=> secure_html($currentCategory['alt']),
					'category.name'	=> secure_html($currentCategory['name']),
					'category.info'	=> ($config['use_bbcodes'])?$parse -> bbcodes($currentCategory['info']):$currentCategory['info'],
					'entries'		=> $newsContent,
				));

				if ($currentCategory['image_id'] && $currentCategory['icon_id']) {
					$tnvars['regx']['#\[icon\](.*?)\[\/icon\]#is'] = '$1';
					$tnvars['vars']['icon.url']				= $config['attach_url'].'/'.$currentCategory['icon_folder'].'/'.$currentCategory['icon_name'];;
					$tnvars['vars']['icon.width']			= $row['icon_width'];
					$tnvars['vars']['icon.height']			= $row['icon_height'];
					if ($currentCategory['icon_preview']) {
						$tnvars['regx']['#\[icon\.preview\](.*?)\[\/icon.preview\]#is'] = '$1';
						$tnvars['regx']['#\[icon\.nopreview\](.*?)\[\/icon.nopreview\]#is'] = '';
						$tnvars['vars']['icon.preview.url']		= $config['attach_url'].'/'.$currentCategory['icon_folder'].'/thumb/'.$currentCategory['icon_name'];;
						$tnvars['vars']['icon.preview.width']	= $currentCategory['icon_pwidth'];
						$tnvars['vars']['icon.preview.height']	= $currentCategory['icon_pheight'];
					} else {
						$tnvars['regx']['#\[icon\.preview\](.*?)\[\/icon.preview\]#is'] = '';
						$tnvars['regx']['#\[icon\.nopreview\](.*?)\[\/icon.nopreview\]#is'] = '$1';
					}
				} else {
					$tnvars['regx']['#\[icon\](.*?)\[\/icon\]#is'] = '';
					$tnvars['regx']['#\[icon\.preview\](.*?)\[\/icon.preview\]#is'] = '';
					$tnvars['regx']['#\[icon\.nopreview\](.*?)\[\/icon.nopreview\]#is'] = '';
				}

				$tpl->vars('news.table', $tnvars);
				$template['vars']['mainblock'] .= $tpl->show('news.table');
			} else {
				$template['vars']['mainblock'] .= $newsContent;
			}

			break;

		case 'by.day':
			$year	= intval(isset($params['year'])?$params['year']:$_REQUEST['year']);
			$month	= intval(isset($params['month'])?$params['month']:$_REQUEST['month']);
			$day	= intval(isset($params['day'])?$params['day']:$_REQUEST['day']);

			if (($year < 1970)||($year > 2100)||($month < 1)||($month > 12)||($day < 1)||($day > 31))
				return false;

			$SYSTEM_FLAGS['info']['title']['group'] = LangDate("j Q Y", mktime("0", "0", "0", $month, $day, $year));
		    $paginationParams = checkLinkAvailable('news', 'by.day')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'by.day', 'params' => array('day' => sprintf('%02u', $day), 'month' => sprintf('%02u', $month), 'year' => $year), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'by.day'), 'xparams' => array('day' => sprintf('%02u', $day), 'month' => sprintf('%02u', $month), 'year' => $year), 'paginator' => array('page', 1, false));

			// Use extended return mode
			$callingParams['extendedReturn'] = true;
			$output = news_showlist(array('DATA', 'postdate', 'BETWEEN', array(mktime(0,0,0,$month,$day,$year), mktime(23,59,59,$month,$day,$year))), $paginationParams, $callingParams);

			// Check if there're output data
			if ($output['count'] > 0) {
				$template['vars']['mainblock'] .= $output['data'];
			} else {
				// No data, stop execution
				if (!$params['FFC']) {
					error404();
				}
				return false;
			}
			break;

		case 'by.month':
			$year	= intval(isset($params['year'])?$params['year']:$_REQUEST['year']);
			$month	= intval(isset($params['month'])?$params['month']:$_REQUEST['month']);

			if (($year < 1970)||($year > 2100)||($month < 1)||($month > 12))
				return false;

			$SYSTEM_FLAGS['info']['title']['group'] = LangDate("F Y", mktime(0,0,0, $month, 1, $year));
		    $paginationParams = checkLinkAvailable('news', 'by.month')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'by.month', 'params' => array('month' => sprintf('%02u', $month), 'year' => $year), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'by.month'), 'xparams' => array('month' => sprintf('%02u', $month), 'year' => $year), 'paginator' => array('page', 1, false));

			// Use extended return mode
			$callingParams['extendedReturn'] = true;
			$output = news_showlist(array('DATA', 'postdate', 'BETWEEN', array(mktime(0,0,0,$month,1,$year), mktime(23,59,59,$month,date("t",mktime(0,0,0,$month,1,$year)),$year))), $paginationParams, $callingParams);

			// Check if there're output data
			if ($output['count'] > 0) {
				$template['vars']['mainblock'] .= $output['data'];
			} else {
				// No data, stop execution
				if (!$params['FFC']) {
					error404();
				}
				return false;
			}
			break;

		case 'by.year':
			$year	= intval(isset($params['year'])?$params['year']:$_REQUEST['year']);

			if (($year < 1970)||($year > 2100))
				return false;

			$SYSTEM_FLAGS['info']['title']['group'] = LangDate("Y", mktime(0,0,0, 1, 1, $year));
		    $paginationParams = checkLinkAvailable('news', 'by.year')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'by.year', 'params' => array('year' => $year), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'by.year'), 'xparams' => array('year' => $year), 'paginator' => array('page', 1, false));

			// Use extended return mode
			$callingParams['extendedReturn'] = true;
			$output = news_showlist(array('DATA', 'postdate', 'BETWEEN', array(mktime(0,0,0,1,1,$year), mktime(23,59,59,12,31,$year))), $paginationParams, $callingParams);

			// Check if there're output data
			if ($output['count'] > 0) {
				$template['vars']['mainblock'] .= $output['data'];
			} else {
				// No data, stop execution
				if (!$params['FFC']) {
					error404();
				}
				return false;
			}
			break;
	}

	// Execute filters [ onAfterShow ]
	if (is_array($PFILTERS['news'])) {
		foreach ($PFILTERS['news'] as $k => $v) { $v->onAfterShow('short'); }
	}
 }
}