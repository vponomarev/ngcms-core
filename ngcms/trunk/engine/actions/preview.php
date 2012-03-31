<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: preview.php
// Description: News preview
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('preview', 'admin');

// Preload news display engine
include_once root.'includes/news.php';
include_once root.'includes/classes/upload.class.php';

showPreview();