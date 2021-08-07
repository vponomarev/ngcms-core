<?php

//
// Copyright (C) 2006-2013 Next Generation CMS (http://ngcms.ru/)
// Name: search.php
// Description: News search
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

$lang = LoadLang('search', 'site');

//
// Make search
//
include_once root.'includes/news.php';

function search_news()
{
    global $catz, $catmap, $mysql, $config, $userROW, $twig, $parse, $template, $lang, $PFILTERS, $SYSTEM_FLAGS, $TemplateCache;

    // PREPARE FILTER RULES FOR NEWS SHOWER
    $filter = [];

    // AUTHOR
    if ($_REQUEST['author']) {
        array_push($filter, ['DATA', 'author', '=', $_REQUEST['author']]);
    }

    // CATEGORY
    if ($_REQUEST['catid']) {
        array_push($filter, ['DATA', 'category', '=', $_REQUEST['catid']]);
    }

    // POST DATE
    if ($_REQUEST['postdate'] && preg_match('#^(\d{4})(\d{2})$#', $_REQUEST['postdate'], $dv)) {
        if (($dv[1] >= 1970) && ($dv[1] <= 2100) && ($dv[2] >= 1) && ($dv[2] <= 12)) {
            array_push($filter, [
                'OR',
                ['DATA', 'postdate', 'BETWEEN', [mktime(0, 0, 0, $dv[2], 1, $dv[1]), mktime(23, 59, 59, $dv[2], date('t', mktime(0, 0, 0, $dv[2], 1, $dv[1])), $dv[1])]],
            ]);
        }
    }

    // TEXT
    $search = [];
    $search_words = [];
    if ($_REQUEST['search']) {
        $search_words = preg_split('#[ \,\.]+#', trim(str_replace(['<', '>', '%', '$', '#'], '', mb_substr($_REQUEST['search'], 0, 64))), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($search_words as $s) {
            array_push(
                $search,
                [
                    'OR',
                    ['DATA', 'title', 'like', '%'.$mysql->db_quote($s).'%'],
                    ['DATA', 'content', 'like', '%'.$mysql->db_quote($s).'%'],
                ]
            );
        }

        if (count($search) > 1) {
            array_unshift($search, 'AND');
        }
        if (count($search) == 1) {
            $search = $search[0];
        }

        array_push($filter, $search);
    }

    if (count($filter) > 1) {
        array_unshift($filter, 'AND');
    }
    if (count($filter) == 1) {
        $filter = $filter[0];
    }

    //print "FILTER: <pre>".var_export($filter, true)."</pre>\n";
    loadActionHandlers('news');
    loadActionHandlers('news:search');

    // Configure pagination
    $paginationParams = ['pluginName' => 'search', 'xparams' => ['search' => $_REQUEST['search'], 'author' => $_REQUEST['author'], 'catid' => $_REQUEST['catid'], 'postdate' => $_REQUEST['postdate']], 'paginator' => ['page', 1, false]];

    // Configure display params
    $callingParams = ['style' => 'short', 'searchFlag' => true, 'extendedReturn' => true, 'customCategoryTemplate' => true];

    if ($_REQUEST['page']) {
        $callingParams['page'] = (int) $_REQUEST['page'];
    }

    // Preload template configuration variables
    templateLoadVariables();
    $tplVars = $TemplateCache['site']['#variables'];

    // Check if template requires extracting embedded images
    $callingParams['extractEmbeddedItems'] = false;

    if (isset($tplVars['configuration']['extractEmbeddedItems'])) {
        $callingParams['extractEmbeddedItems'] = (bool) $tplVars['configuration']['extractEmbeddedItems'];
    }

    // Call SEARCH only if search words are entered
    $founded = ['count' => 0, 'data' => false];

    if (count($search_words)) {
        $founded = news_showlist($filter, $paginationParams, $callingParams);
    }

    // Now let's show SEARCH basic template
    $tvars = [];
    $tvars['author'] = secure_html($_REQUEST['author']);
    $tvars['search'] = secure_html($_REQUEST['search']);

    $tvars['count'] = $founded['count'];
    $tvars['form_url'] = generateLink('search', '', []);

    $tvars['flags'] = [
        'found' => isset($_REQUEST['search']) && count($search_words) && $founded['count'],
        'notfound' => isset($_REQUEST['search']) && count($search_words) && !$founded['count'],
        'error' => isset($_REQUEST['search']) && !count($search_words),
    ];

    // Make category list
    $tvars['catlist'] = makeCategoryList(['name' => 'catid', 'selected' => $_REQUEST['catid'], 'doempty' => 1]);

    // Results of search
    $tvars['entries'] = $founded['data'];

    // Make month list
    $monthsList = explode(',', $lang['months']);
    $rows = $mysql->select(
        "SELECT
            month(from_unixtime(postdate)) as month,
            year(from_unixtime(postdate)) as year,
            COUNT(id) AS cnt
        FROM ".prefix."_news
        WHERE approve = '1'
        GROUP BY year, month
        ORDER BY year DESC, month DESC"
    );

    foreach ($rows as $row) {
        $pd_value = sprintf('%04u%02u', $row['year'], $row['month']);
        $pd_text = $monthsList[$row['month'] - 1].' '.$row['year'].' '.$row['cnt'];

        $tvars['datelist'] .= '
            <option value="'.$pd_value.'"'.(($pd_value == $_REQUEST['postdate']) ? ' selected' : '').'>'
                .$pd_text
            .'</option>';
    }

    // Set meta tags for search page
    $SYSTEM_FLAGS['info']['title']['group'] = $lang['search.title'];

    $template['vars']['mainblock'] .= $twig->render('search.table.tpl', $tvars);
}
