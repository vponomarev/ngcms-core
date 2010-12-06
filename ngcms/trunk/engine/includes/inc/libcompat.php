<?php

//
// ���������� ��� ���������� �������� ������������� ��� ������ ��-��� ������
//

function compatRedirector() {
	global $mysql, $catz, $config;
	$uri = $_SERVER['REQUEST_URI'];

	$homePrefix = '';
	if (preg_match('#^http\:\/\/.+?\/(.+)$#', $config['home_url'], $p)) {
		$homePrefix = $p[1];
	}
	//print "<pre>".var_export($_SERVER, true)."</pre>";

	if (preg_match('#^\/\?#', $uri, $null) || ($homePrefix && preg_match('#^\/'.$homePrefix.'\/\?#', $uri, $null))) {
		// ��� ������
		//print "GET PARAMS:<br/>\n<pre>".var_export($_GET, true)."</pre>";
		if (isset($_GET['action']) && ($_GET['action'] == 'static')) {
			if (isset($_GET['altname'])) {
				if ($row = $mysql->record("select * from ".prefix."_static where alt_name=".db_squote($_GET['altname']))) {
					$link = checkLinkAvailable('static', '')?
								generateLink('static', '', array('altname' => $row['alt_name'], 'id' => $row['id']), array(), false, true):
								generateLink('core', 'plugin', array('plugin' => 'static'), array('altname' => $row['alt_name'], 'id' => $row['id']), false, true);
					header("Location: ".$link);
					exit;
				}
			}
			header("Location: ".home);
			exit;
		}

		if (isset($_GET['action']) && ($_GET['action'] == 'users')) {
			if (isset($_GET['user'])) {
				if ($row = $mysql->record("select * from ".uprefix."_users where name=".db_squote($_GET['user']))) {
					$link = checkLinkAvailable('uprofile', 'show')?
												generateLink('uprofile', 'show', array('name' => $row['name'], 'id' => $row['id'])):
												generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('name' => $row['name'], 'id' => $row['id']));
					header("Location: ".$link);
					exit;
				}
			}
			header("Location: ".home);
			exit;
		}


		if (isset($_GET['category']) && isset($_GET['altname'])) {
			// ������ �������, ������� �
			if ($nrow = $mysql->record("select * from ".prefix."_news where alt_name=".db_squote($_GET['altname']))) {
				$link = newsGenerateLink($nrow, false, 0, true);
				//print "Redirect: ".$link;
				header("Location: ".$link);
			} else {
				//print "Unknown news";
				header("Location: ".home);
			}
			exit;
		} else if (isset($_GET['id'])) {
			// ������ �������, ������� �
			if ($nrow = $mysql->record("select * from ".prefix."_news where id=".db_squote($_GET['id']))) {
				$link = newsGenerateLink($nrow, false, 0, true);
				//print "Redirect: ".$link;
				header("Location: ".$link);
			} else {
				//print "Unknown news";
				header("Location: ".home);
			}
			exit;
		} else if (isset($_GET['category'])) {
			// �������� ���������
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
				header("Location: ".home);
			}
			exit;
		} else if (isset($_GET['year'])) {
			// ��������� �� ���� [���]
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
		} else if (isset($_GET['cstart'])) {
			$params['page'] = intval($_GET['cstart']);

			$link = generateLink('news', 'main', $params);

			header("Location: ".$link);
			exit;
		}
	}
}
