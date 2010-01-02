<?php

//
// Copyright (C) 2006-2010 Next Generation CMS (http://ngcms.ru)
// Name: captcha.php
// Description: printing captcha
// Author: NGCMS project team
//

@require_once('core.php');
@include_once root.'includes/classes/captcha.class.php';

// Print HTTP headers
@header('Content-type: image/png');
@header('Expires: '.		gmdate('D, d M Y H:i:s', time()+ 30).	' GMT');
@header('last-modified: '.	gmdate('D, d M Y H:i:s', time()).		' GMT');

// Determine captcha block identifier
$blockName = $_REQUEST['id'];

// Determine captcha number to show
$cShowNumber = 1234;

if (($blockName != '')&&(isset($_SESSION['captcha.'.$blockName]))) {
	$cShowNumber = $_SESSION['captcha.'.$blockName];
} else if (isset($_SESSION['captcha'])) {
	$cShowNumber = $_SESSION['captcha'];
}

$captc = new captcha;
$captc->makeimg($cShowNumber);
