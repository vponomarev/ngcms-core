<?php

//
// Библиотека для сохранения обратной совместимости при работе не-ЧПУ сайтов
//

function compatRedirector() {
	global $mysql, $catz;
	$uri = $_SERVER['REQUEST_URI'];

	//print "<pre>".var_export($_SERVER, true)."</pre>";

	if (preg_match('#^\/\?#', $uri, $null)) {
		// Наш клиент
		//print "GET PARAMS:<br/>\n<pre>".var_export($_GET, true)."</pre>";

		if (isset($_GET['category']) && isset($_GET['altname'])) {
			// Полная новость, находим её
			if ($nrow = $mysql->record("select * from ".prefix."_news where alt_name=".db_squote($_GET['altname']))) {
				$link = newsGenerateLink($nrow, false, 0, true);
				//print "Redirect: ".$link;
				header("Location: ".$link);
			} else {
				//print "Unknown news";
				header("Location: /");
			}
			exit;
		} else if (isset($_GET['category'])) {
			// Страница категории
			if (isset($catz[$_GET['category']])) {
				$xc = $catz[$_GET['category']];
				$params = array('category' => $xc['alt'], 'catid' => $xc['id']);
				if (isset($_GET['cstart']))
					$params['page'] = intval($_GET['cstart']);

				$link = generateLink('news', 'by.category', $params);
				//print "Redirect: ".$link;
				header("Location: ".$link);
			} else {
				//print "Unknown category";
				header("Location: /");
			}
			exit;
		} else if (isset($_GET['year'])) {
			// Адресация по дате [год]
			if (isset($_GET['month']) && isset($_GET['day'])) {
				$params = array('year' => sprintf('%04u', intval($_GET['year'])), 'month' => sprintf('%02u', intval($_GET['month'])), 'day' => sprintf('%02u', intval($_GET['day'])));
				if (isset($_GET['cstart']))
					$params['page'] = intval($_GET['cstart']);

				$link = generateLink('news', 'by.day', $params);
				//print "Redirect: ".$link;
				header("Location: ".$link);
				exit;
			}
			if (isset($_GET['month'])) {
				$params = array('year' => sprintf('%04u', intval($_GET['year'])), 'month' => sprintf('%02u', intval($_GET['month'])));
				if (isset($_GET['cstart']))
					$params['page'] = intval($_GET['cstart']);

				$link = generateLink('news', 'by.month', $params);
				//print "Redirect: ".$link;
				header("Location: ".$link);
				exit;
			}

			$params = array('year' => sprintf('%04u', intval($_GET['year'])));
			if (isset($_GET['cstart']))
				$params['page'] = intval($_GET['cstart']);

			$link = generateLink('news', 'by.year', $params);
			//print "Redirect: ".$link;
			header("Location: ".$link);
			exit;
		}
	}
}
