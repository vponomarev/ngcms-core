<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: news.php
// Description: News display sub-engine
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

$lang = LoadLang('news', 'site');

// Load shared library
include_once root.'includes/inc/libnews.php';

// ================================================================= //
// Module code                                                       //
// ================================================================= //

// Default "show news" function
function showNews($handlerName, $params)
{
    global $catz, $catmap, $template, $config, $userROW, $PFILTERS, $lang, $SYSTEM_FLAGS, $SUPRESS_TEMPLATE_SHOW, $tpl, $parse, $currentCategory, $twig, $twigLoader, $timer, $TemplateCache;
    // preload plugins
    loadActionHandlers('news');
    $timer->registerEvent('All [news] plugins are preloaded');

    // Init array with configuration parameters
    $callingParams = ['customCategoryTemplate' => 1, 'setCurrentCategory' => 1, 'setCurrentNews' => 1];
    $callingCommentsParams = [];

    // Preload template configuration variables
    templateLoadVariables();
    // Check if template requires extracting embedded images
    $tplVars = $TemplateCache['site']['#variables'];
    if (isset($tplVars['configuration']) && is_array($tplVars['configuration']) && isset($tplVars['configuration']['extractEmbeddedItems']) && $tplVars['configuration']['extractEmbeddedItems']) {
        $callingParams['extractEmbeddedItems'] = true;
    }

    // Set default template path
    $templatePath = tpl_dir.$config['theme'];

    // Check for FULL NEWS mode
    if (($handlerName == 'news') || ($handlerName == 'print')) {
        $flagPrint = ($handlerName == 'print') ? true : false;
        if ($flagPrint) {
            $SUPRESS_TEMPLATE_SHOW = true;
        }

        $callingParams['style'] = $flagPrint ? 'print' : 'full';

        // Execute filters [ onBeforeShow ] ** ONLY IN 'news' mode. In print mode we don't use it
        if (!$flagPrint && is_array($PFILTERS['news'])) {
            foreach ($PFILTERS['news'] as $k => $v) {
                $v->onBeforeShow('full');
            }
        }

        // Determine passed params
        $vars = ['id' => 0, 'altname' => ''];
        if (isset($params['id'])) {
            $vars['id'] = $params['id'];
        } elseif (isset($params['zid'])) {
            $vars['id'] = $params['zid'];
        } elseif (isset($params['altname'])) {
            $vars['altname'] = $params['altname'];
        } elseif (isset($_REQUEST['id'])) {
            $vars['id'] = intval($_REQUEST['id']);
        } elseif (isset($_REQUEST['zid'])) {
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

        $callingParams['addCanonicalLink'] = true;

        // Try to show news
        if (($row = news_showone($vars['id'], $vars['altname'], $callingParams)) !== false) {
            // Execute filters [ onAfterShow ] ** ONLY IN 'news' mode. In print mode we don't use it
            if (!$flagPrint && is_array($PFILTERS['news'])) {
                foreach ($PFILTERS['news'] as $k => $v) {
                    $v->onAfterNewsShow($row['id'], $row, ['style' => 'full']);
                }
            }
        }
    } else {
        $callingParams['style'] = 'short';
        $callingParams['page'] = (isset($params['page']) && intval($params['page'])) ? intval($params['page']) : (isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 0);

        // Execute filters [ onBeforeShow ]
        if (is_array($PFILTERS['news'])) {
            foreach ($PFILTERS['news'] as $k => $v) {
                $v->onBeforeShow('short');
            }
        }

        $tableVars = [];

        $ntTemplateName = 'news.table.tpl';

        $callingParams['extendedReturn'] = true;
        $callingParams['extendedReturnData'] = true;
        $callingParams['entendedReturnPagination'] = true;

        switch ($handlerName) {
            case 'main':
                $SYSTEM_FLAGS['info']['title']['group'] = $lang['mainpage'];
                $paginationParams = checkLinkAvailable('news', 'main') ?
                    ['pluginName' => 'news', 'pluginHandler' => 'main', 'params' => [], 'xparams' => [], 'paginator' => ['page', 0, false]] :
                    ['pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => ['plugin' => 'news', 'handler' => 'main'], 'xparams' => [], 'paginator' => ['page', 1, false]];

                if ($config['default_newsorder'] != '') {
                    $callingParams['newsOrder'] = $config['default_newsorder'];
                }

                $tableVars = news_showlist(['DATA', 'mainpage', '=', '1'], $paginationParams, $callingParams);

                break;

            case 'all':
                $SYSTEM_FLAGS['info']['title']['group'] = $lang['allnews'];
                $paginationParams = checkLinkAvailable('news', 'all') ?
                    ['pluginName' => 'news', 'pluginHandler' => 'all', 'params' => [], 'xparams' => [], 'paginator' => ['page', 0, false]] :
                    ['pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => ['plugin' => 'news', 'handler' => 'all'], 'xparams' => [], 'paginator' => ['page', 1, false]];

                if ($config['default_newsorder'] != '') {
                    $callingParams['newsOrder'] = $config['default_newsorder'];
                }

                $tableVars = news_showlist([], $paginationParams, $callingParams);

                break;

            case 'by.category':
                $category = '';
                if (isset($params['catid'])) {
                    $category = $params['catid'];
                } elseif (isset($params['category']) && isset($catz[$params['category']])) {
                    $category = $catz[$params['category']]['id'];
                } elseif (isset($_REQUEST['catid'])) {
                    $category = $params['catid'];
                } elseif (isset($_REQUEST['category']) && isset($catz[$_REQUEST['category']])) {
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
                $SYSTEM_FLAGS['news']['currentCategory.alt'] = $currentCategory['alt'];
                $SYSTEM_FLAGS['news']['currentCategory.id'] = $currentCategory['id'];
                $SYSTEM_FLAGS['news']['currentCategory.name'] = $currentCategory['name'];

                // Set title
                $SYSTEM_FLAGS['info']['title']['group'] = $currentCategory['name'];

                // Check if `default template` for this category is set to "current category"
                $cct = intval(mb_substr($currentCategory['flags'], 2, 1));
                if ($cct < 1) {
                    $cct = intval($config['template_mode']);
                    if (!$cct) {
                        $cct = 1;
                    }
                }
                $callingParams['customCategoryTemplate'] = $cct;
                $callingParams['currentCategoryId'] = $currentCategory['id'];

                // Set meta tags for category page
                if ($currentCategory['description']) {
                    $SYSTEM_FLAGS['meta']['description'] = $currentCategory['description'];
                }
                if ($currentCategory['keywords']) {
                    $SYSTEM_FLAGS['meta']['keywords'] = $currentCategory['keywords'];
                }

                // Set personal `order by` for category
                if ($currentCategory['number']) {
                    $callingParams['showNumber'] = $currentCategory['number'];
                }

                // Set number of `news per page` if this parameter is filled in category
                if ($currentCategory['orderby']) {
                    $callingParams['newsOrder'] = $currentCategory['orderby'];
                }

                $paginationParams = checkLinkAvailable('news', 'by.category') ?
                    ['pluginName' => 'news', 'pluginHandler' => 'by.category', 'params' => ['category' => $catmap[$category]], 'xparams' => [], 'paginator' => ['page', 0, false]] :
                    ['pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => ['plugin' => 'news', 'handler' => 'by.category'], 'xparams' => ['category' => $catmap[$category]], 'paginator' => ['page', 1, false]];

                // Sort news for `category` mode
                $callingParams['pin'] = 1;

                // Notify that we use 'pagination category' mode
                $callingParams['paginationCategoryID'] = $currentCategory['id'];

                // Generate news content
                $tableVars = news_showlist(['DATA', 'category', '=', $category], $paginationParams, $callingParams);

                // TABLE - prepare information about category
                $tableVars['category'] = array_shift(makeCategoryInfo($currentCategory['id']));

                // Check if template 'news.table.tpl' exists [first check custom category template (if set), after that - common template for the whole site
                if ($currentCategory['tpl'] && file_exists(tpl_dir.$config['theme'].'/ncustom/'.$currentCategory['tpl'].'/news.table.tpl')) {
                    $ntTemplateName = 'ncustom/'.$currentCategory['tpl'].'/'.$ntTemplateName;
                }

                break;

            case 'by.day':
                $year = intval(isset($params['year']) ? $params['year'] : $_REQUEST['year']);
                $month = intval(isset($params['month']) ? $params['month'] : $_REQUEST['month']);
                $day = intval(isset($params['day']) ? $params['day'] : $_REQUEST['day']);

                if (($year < 1970) || ($year > 2100) || ($month < 1) || ($month > 12) || ($day < 1) || ($day > 31)) {
                    return false;
                }

                $tableVars['year'] = $year;
                $tableVars['month'] = $month;
                $tableVars['day'] = $day;
                $tableVars['dateStamp'] = mktime('0', '0', '0', $month, $day, $year);

                $SYSTEM_FLAGS['info']['title']['group'] = LangDate('j Q Y', mktime('0', '0', '0', $month, $day, $year));
                $paginationParams = checkLinkAvailable('news', 'by.day') ?
                    ['pluginName' => 'news', 'pluginHandler' => 'by.day', 'params' => ['day' => sprintf('%02u', $day), 'month' => sprintf('%02u', $month), 'year' => $year], 'xparams' => [], 'paginator' => ['page', 0, false]] :
                    ['pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => ['plugin' => 'news', 'handler' => 'by.day'], 'xparams' => ['day' => sprintf('%02u', $day), 'month' => sprintf('%02u', $month), 'year' => $year], 'paginator' => ['page', 1, false]];

                // Use extended return mode
                $callingParams['extendedReturn'] = true;
                $tableVars = news_showlist(['DATA', 'postdate', 'BETWEEN', [mktime(0, 0, 0, $month, $day, $year), mktime(23, 59, 59, $month, $day, $year)]], $paginationParams, $callingParams);

                // Check if there're output data
                if ($tableVars['count'] <= 0) {
                    // No data, stop execution
                    if (!$params['FFC']) {
                        error404();
                    }

                    return false;
                }
                break;

            case 'by.month':
                $year = intval(isset($params['year']) ? $params['year'] : $_REQUEST['year']);
                $month = intval(isset($params['month']) ? $params['month'] : $_REQUEST['month']);

                if (($year < 1970) || ($year > 2100) || ($month < 1) || ($month > 12)) {
                    return false;
                }

                $tableVars['year'] = $year;
                $tableVars['month'] = $month;
                $tableVars['dateStamp'] = mktime('0', '0', '0', $month, 1, $year);

                $SYSTEM_FLAGS['info']['title']['group'] = LangDate('F Y', mktime(0, 0, 0, $month, 1, $year));
                $paginationParams = checkLinkAvailable('news', 'by.month') ?
                    ['pluginName' => 'news', 'pluginHandler' => 'by.month', 'params' => ['month' => sprintf('%02u', $month), 'year' => $year], 'xparams' => [], 'paginator' => ['page', 0, false]] :
                    ['pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => ['plugin' => 'news', 'handler' => 'by.month'], 'xparams' => ['month' => sprintf('%02u', $month), 'year' => $year], 'paginator' => ['page', 1, false]];

                // Use extended return mode
                $callingParams['extendedReturn'] = true;
                $tableVars = news_showlist(['DATA', 'postdate', 'BETWEEN', [mktime(0, 0, 0, $month, 1, $year), mktime(23, 59, 59, $month, date('t', mktime(0, 0, 0, $month, 1, $year)), $year)]], $paginationParams, $callingParams);

                // Check if there're output data
                if ($tableVars['count'] <= 0) {
                    // No data, stop execution
                    if (!$params['FFC']) {
                        error404();
                    }

                    return false;
                }
                break;

            case 'by.year':
                $year = intval(isset($params['year']) ? $params['year'] : $_REQUEST['year']);

                if (($year < 1970) || ($year > 2100)) {
                    return false;
                }

                $tableVars['year'] = $year;
                $tableVars['dateStamp'] = mktime('0', '0', '0', 1, 1, $year);

                $SYSTEM_FLAGS['info']['title']['group'] = LangDate('Y', mktime(0, 0, 0, 1, 1, $year));
                $paginationParams = checkLinkAvailable('news', 'by.year') ?
                    ['pluginName' => 'news', 'pluginHandler' => 'by.year', 'params' => ['year' => $year], 'xparams' => [], 'paginator' => ['page', 0, false]] :
                    ['pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => ['plugin' => 'news', 'handler' => 'by.year'], 'xparams' => ['year' => $year], 'paginator' => ['page', 1, false]];

                // Use extended return mode
                $callingParams['extendedReturn'] = true;
                $tableVars = news_showlist(['DATA', 'postdate', 'BETWEEN', [mktime(0, 0, 0, 1, 1, $year), mktime(23, 59, 59, 12, 31, $year)]], $paginationParams, $callingParams);

                // Check if there're output data
                if ($tableVars['count'] <= 0) {
                    // No data, stop execution
                    if (!$params['FFC']) {
                        error404();
                    }

                    return false;
                }
                break;
        }

        $tableVars['handler'] = $handlerName;

        // Prepare news table
        //print "[TABLE VARS]<pre>".var_export($tableVars, true)."</pre>";
        $twigLoader->setDefaultContent($ntTemplateName, '{% for entry in data %}{{ entry }}{% else %}{{ engineMSG(\'common\', lang[\'msgi_no_news\']) }}{% endfor %} {{ pagination }}');
        $xt = $twig->loadTemplate($ntTemplateName);
        $template['vars']['mainblock'] .= $xt->render($tableVars);

        // Execute filters [ onAfterShow ]
        if (is_array($PFILTERS['news'])) {
            foreach ($PFILTERS['news'] as $k => $v) {
                $v->onAfterShow('short');
            }
        }
    }
}
