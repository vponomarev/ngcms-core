<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru)
// Name: syscron.php
// Description: Entry point for maintanance (cron) external calls
// Author: NGCMS project team
//

// Load CORE
@include_once 'engine/core.php';


// Call maintanance actions
exec_acts('maintenance');
if ($config['auto_backup'] == "1") { AutoBackup(false); }

