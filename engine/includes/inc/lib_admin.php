<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: lib_admin.php
// Description: Libraries for admin panel
// Author: Vitaly Ponomarev
//

// ==================================================================
// Categories edit interceptors
// ==================================================================

class FilterAdminCategories {

	// ### Add category interceptor ###
	// Form generator
	function addCategoryForm(&$tvars) { return 1;}

	// Adding executor [done BEFORE actual add and CAN block adding ]
	function addCategory(&$tvars, &$SQL) { return 1;}

	// Adding notificator [ after successful adding ]
	function addCategoryNotify(&$tvars, $SQL, $newsid) { return 1;}


	// ### Edit category interceptor ###
	// Form generator
	function editCategoryForm($categoryID, $SQL, &$tvars) { return 1; }

	// Edit executor  [done BEFORE actual edit and CAN block editing ]
	function editCategory($categoryID, $SQL, &$SQLnew, &$tvars) { return 1; }

	// Edit Notifier [ adfter successful editing ]
	function editCategoryNotify($categoryID, $SQL, &$SQLnew, &$tvars) { return 1; }

}


// Register admin filter
function register_admin_filter($group, $name, $instance) {
 global $AFILTERS;
 $AFILTERS[$group][$name] = $instance;
}
