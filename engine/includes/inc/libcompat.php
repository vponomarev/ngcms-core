<?php

//
// Библиотека для сохранения обратной совместимости при работе не-ЧПУ сайтов
//

function compatRedirector()
{
    global $mysql, $catz, $config;
    $uri = $_SERVER['REQUEST_URI'];

    $homePrefix = '';
    if (preg_match('#^http\:\/\/.+?\/(.+)$#', $config['home_url'], $p)) {
        $homePrefix = $p[1];
    }
    //print "<pre>".var_export($_SERVER, true)."</pre>";

    if (preg_match('#^\/\?#', $uri, $null) || ($homePrefix && preg_match('#^\/'.$homePrefix.'\/\?#', $uri, $null))) {
        // Наш клиент
        //print "GET PARAMS:<br/>\n<pre>".var_export($_GET, true)."</pre>";
        if (isset($_GET['action']) && ($_GET['action'] == 'static')) {
            if (isset($_GET['altname'])) {
                if ($row = $mysql->record('select * from '.prefix.'_static where alt_name='.db_squote($_GET['altname']))) {
                    $link = checkLinkAvailable('static', '') ?
                        generateLink('static', '', ['altname' => $row['alt_name'], 'id' => $row['id']], [], false, true) :
                        generateLink('core', 'plugin', ['plugin' => 'static'], ['altname' => $row['alt_name'], 'id' => $row['id']], false, true);
                    header('HTTP/1.1 301 Moved permanently');
                    header('Location: '.$link);
                    exit;
                }
            }
            header('Location: '.home);
            exit;
        }

        if (isset($_GET['action']) && ($_GET['action'] == 'users')) {
            if (isset($_GET['user'])) {
                if ($row = $mysql->record('select * from '.prefix.'_users where name='.db_squote($_GET['user']))) {
                    $link = checkLinkAvailable('uprofile', 'show') ?
                        generateLink('uprofile', 'show', ['name' => $row['name'], 'id' => $row['id']]) :
                        generateLink('core', 'plugin', ['plugin' => 'uprofile', 'handler' => 'show'], ['name' => $row['name'], 'id' => $row['id']]);
                    header('HTTP/1.1 301 Moved permanently');
                    header('Location: '.$link);
                    exit;
                }
            }
            header('Location: '.home);
            exit;
        }

        if (isset($_GET['category']) && isset($_GET['altname'])) {
            // Полная новость, находим её
            if ($nrow = $mysql->record('select * from '.prefix.'_news where alt_name='.db_squote($_GET['altname']))) {
                $link = newsGenerateLink($nrow, false, 0, true);
                //print "Redirect: ".$link;
                header('HTTP/1.1 301 Moved permanently');
                header('Location: '.$link);
            } else {
                //print "Unknown news";
                header('HTTP/1.1 301 Moved permanently');
                header('Location: '.home);
            }
            exit;
        } elseif (isset($_GET['id'])) {
            // Полная новость, находим её
            if ($nrow = $mysql->record('select * from '.prefix.'_news where id='.db_squote($_GET['id']))) {
                $link = newsGenerateLink($nrow, false, 0, true);
                //print "Redirect: ".$link;
                header('HTTP/1.1 301 Moved permanently');
                header('Location: '.$link);
            } else {
                //print "Unknown news";
                header('HTTP/1.1 301 Moved permanently');
                header('Location: '.home);
            }
            exit;
        } elseif (isset($_GET['category'])) {
            // Страница категории
            if (isset($catz[$_GET['category']])) {
                $xc = $catz[$_GET['category']];
                $params = ['category' => $xc['alt'], 'catid' => $xc['id']];
                if (isset($_GET['cstart'])) {
                    $params['page'] = intval($_GET['cstart']);
                }

                $link = generateLink('news', 'by.category', $params);
                //print "Redirect: ".$link;
                header('HTTP/1.1 301 Moved permanently');
                header('Location: '.$link);
            } else {
                //print "Unknown category";
                header('HTTP/1.1 301 Moved permanently');
                header('Location: '.home);
            }
            exit;
        } elseif (isset($_GET['year'])) {
            // Адресация по дате [год]
            if (isset($_GET['month']) && isset($_GET['day'])) {
                $params = ['year' => sprintf('%04u', intval($_GET['year'])), 'month' => sprintf('%02u', intval($_GET['month'])), 'day' => sprintf('%02u', intval($_GET['day']))];
                if (isset($_GET['cstart'])) {
                    $params['page'] = intval($_GET['cstart']);
                }

                $link = generateLink('news', 'by.day', $params);
                //print "Redirect: ".$link;
                header('HTTP/1.1 301 Moved permanently');
                header('Location: '.$link);
                exit;
            }
            if (isset($_GET['month'])) {
                $params = ['year' => sprintf('%04u', intval($_GET['year'])), 'month' => sprintf('%02u', intval($_GET['month']))];
                if (isset($_GET['cstart'])) {
                    $params['page'] = intval($_GET['cstart']);
                }

                $link = generateLink('news', 'by.month', $params);
                //print "Redirect: ".$link;
                header('HTTP/1.1 301 Moved permanently');
                header('Location: '.$link);
                exit;
            }

            $params = ['year' => sprintf('%04u', intval($_GET['year']))];
            if (isset($_GET['cstart'])) {
                $params['page'] = intval($_GET['cstart']);
            }

            $link = generateLink('news', 'by.year', $params);
            //print "Redirect: ".$link;
            header('HTTP/1.1 301 Moved permanently');
            header('Location: '.$link);
            exit;
        } elseif (isset($_GET['cstart'])) {
            $params['page'] = intval($_GET['cstart']);

            $link = generateLink('news', 'main', $params);

            header('HTTP/1.1 301 Moved permanently');
            header('Location: '.$link);
            exit;
        }
    }
}
