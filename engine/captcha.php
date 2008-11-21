<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru)
// Name: captcha.php
// Description: printing captcha
// Author: NGCMS project team
//

@require_once('core.php');
@include_once root.'includes/classes/captcha.class.php';

$now = mktime (date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")); 
$expires = mktime (date("H"),date("i"),date("s") + 30 ,date("m"),date("d"),date("Y"));
$expires_gmt = gmdate('D, d M Y H:i:s', $expires).' GMT'; 
$last_modified_gmt  = gmdate('D, d M Y H:i:s', $now).' GMT'; 

@header('Content-type:image/png'); 
@header('Expires: '.$expires_gmt); 
@header('last-modified: '.$last_modified_gmt);

$captc = new captcha;
$captc->makeimg($number);

?>