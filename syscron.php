<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru)
// Name: syscron.php
// Description: Entry point for maintanance (cron) external calls
// Author: NGCMS project team
//

// Load CORE
@include_once 'engine/core.php';

// Run CRON tasks
$cron->run(true);

// Terminate execution of script
coreNormalTerminate();
