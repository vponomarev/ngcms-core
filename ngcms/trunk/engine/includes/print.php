<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: print.php
// Description: Print version generator for news
// Author: Vitaly Ponomarev
//

@include_once '../core.php';

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');


@include_once 'news.php';

function printNews() {

 // preload plugins
 load_extras('news');

 if (altname || id) {
	print news_showone(id?id:0, !id?altname:'', array('style' => 'export', 'overrideTemplateName' => 'print'));
 } else {
	print news_showlist(array(), array('style' => 'export', 'overrideTemplateName' => 'print'));
 }
}

printNews();