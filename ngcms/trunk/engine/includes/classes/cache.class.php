<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: cache.class.php
// Description: Cache manager
// Author: Vitaly Ponomarev
//


//
// Formal hierarchy of data:
// > module     - system module. For example, "news"
// >> group     - group of data of module. For example, "category"
// >>> id       - data identifier. For example "1" - for news ID, category ID,...
// >>>> subid   - subdata identifier. For example, some element of news or category or so...


class CacheManager {
	// Load master cache
	function CacheManager() {
		$cacheDir = root."cache/data";

	}

	// Store data into cache
	function set($module, $group, $id, $subid, $value, $deps = array()) {

	}

	// Retrieve data from cache
	function get($module, $group, $id, $subid) {

	}

	// Mark as expired/updated
	function expire($module, $group, $id, $subid) {
	}




}