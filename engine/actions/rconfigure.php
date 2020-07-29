<?php

@include_once '..\core.php';

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

include_once '../includes/classes/uhandler.class.php';
$ULIB = new urlLibrary();
$ULIB->loadConfig();

$UHANDLER = new urlHandler();
$UHANDLER->loadConfig();

$ULIB->registerCommand(
    'news',
    'all',
    [
        'vars'  => [
            'page' => ['matchRegex' => '\d+', 'descr' => ['russian' => 'Страница']],
        ],
        'descr' => ['russian' => 'Лента новостей'],
    ]
);

/*

$ULIB->registerCommand('core', 'plugin',
        array ('vars' =>
                    array(	'plugin' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'ID плагина')),
                            'handler' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'Передаваемая команда')),
                    ),
                'descr'	=> array ('russian' => 'Страница плагина'),
        )
);

$ULIB->registerCommand('core', 'registration',
        array ('vars' => array(),
                'descr'	=> array ('russian' => 'Регистрация нового пользователя'),
        )
);

$ULIB->registerCommand('core', 'activation',
        array ('vars' => array(		'userid' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID пользователя')),
                        'code'	=> array('matchRegex' => '.+?', 'descr' => array( 'russian' => 'Код активации')),

                ),
                'descr'	=> array ('russian' => 'Активация нового пользователя'),
        )
);

$ULIB->registerCommand('core', 'lostpassword',
        array ('vars' => array(		'userid' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID пользователя')),
                        'code'	=> array('matchRegex' => '.+?', 'descr' => array( 'russian' => 'Код активации')),

                ),
                'descr'	=> array ('russian' => 'Восстановление потерянного пароля'),
        )
);

$ULIB->registerCommand('core', 'login',
        array ('vars' => array(),
                'descr'	=> array ('russian' => 'Вход на сайт (авторизация)'),
        )
);

$ULIB->registerCommand('core', 'logout',
        array ('vars' => array(),
                'descr'	=> array ('russian' => 'Выход с сайта'),
        )
);

$ULIB->registerCommand('news', 'main',
        array ('vars' =>
                    array(	'page' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'Страница')),
                    ),
                'descr'	=> array ('russian' => 'Главная новостная страница'),
        )
);

$ULIB->registerCommand('news', 'by.category',
        array ('vars' =>
                    array(	'category' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'Альт. имя категории')),
                            'catid' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID категории')),
                            'page' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'Страница')),
                    ),
                'descr'	=> array ('russian' => 'Новости из заданной категории'),
        )
);

$ULIB->registerCommand('news', 'news',
        array ('vars' =>
                    array(	'category' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'Альт. имя категории')),
                            'catid' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID категории')),
                            'year' => array('matchRegex' => '\d{4}', 'descr' => array ('russian' => 'Год')),
                            'month' => array('matchRegex' => '\d{2}', 'descr' => array ('russian' => 'Месяц')),
                            'day' => array('matchRegex' => '\d{2}', 'descr' => array ('russian' => 'День')),
                            'page' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'Страница внутри новости')),
                            'altname' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'Альт. имя новости')),
                            'id' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID новости')),
                            'page' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'Страница внутри новости')),
                    ),
                'descr'	=> array ('russian' => 'Отображение полной новости'),
        )
);

$ULIB->registerCommand('news', 'print',
    array ('vars' =>
    array(	'category' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'Альт. имя категории')),
    'catid' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID категории')),
    'year' => array('matchRegex' => '\d{4}', 'descr' => array ('russian' => 'Год')),
    'month' => array('matchRegex' => '\d{2}', 'descr' => array ('russian' => 'Месяц')),
    'day' => array('matchRegex' => '\d{2}', 'descr' => array ('russian' => 'День')),
    'page' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'Страница внутри новости')),
    'altname' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'Альт. имя новости')),
    'id' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID новости')),
    'page' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'Страница внутри новости')),
    ),
    'descr'	=> array ('russian' => 'Страница для печати полной новости'),
    )
);

$ULIB->registerCommand('news', 'by.year',
        array ('vars' =>
                    array(	'year' => array('matchRegex' => '\d{4}', 'descr' => array('russian' => 'Год')),
                            'page' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'Страница')),
                    ),
                'descr'	=> array ('russian' => 'Новости за год'),
        )
);


$ULIB->registerCommand('news', 'by.month',
        array ( 'vars' =>
            array(	'year' => array('matchRegex' => '\d{4}', 'descr' => array ('russian' => 'Год')),
                    'month' => array('matchRegex' => '\d{2}', 'descr' => array ('russian' => 'Месяц')),
                    'page' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'Страница')),
            ),
        'descr'	=> array ('russian' => 'Новости за месяц'),
    )
);

$ULIB->registerCommand('news', 'by.day',
        array ( 'vars' =>
            array(	'year' => array('matchRegex' => '\d{4}', 'descr' => array ('russian' => 'Год')),
                    'month' => array('matchRegex' => '\d{2}', 'descr' => array ('russian' => 'Месяц')),
                    'day' => array('matchRegex' => '\d{2}', 'descr' => array ('russian' => 'День')),
                    'page' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'Страница')),
            ),
        'descr'	=> array ('russian' => 'Новости за день'),
    )
);


$ULIB->registerCommand('rss_export', 'main',
        array ('vars' => array(),
        'descr'	=> array ('russian' => 'Основной RSS поток'),
    )
);

$ULIB->registerCommand('rss_export', 'category',
        array ('vars' =>
            array(	'category' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'Альт. имя категории')),
                'catid' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID категории')),
            ),
        'descr'	=> array ('russian' => 'RSS поток указанной категории'),
    )
);

$ULIB->registerCommand('uprofile', 'edit',
        array ('vars' =>
                    array(),
                'descr'	=> array ('russian' => 'Редактирование собственного профиля'),
        )
);

$ULIB->registerCommand('uprofile', 'show',
        array ('vars' =>
            array(	'name' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'Логин пользователя')),
                'id' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID пользователя')),
            ),
                'descr'	=> array ('russian' => 'Показать профиль конкретного пользователя'),
        )
);


$ULIB->registerCommand('static', '',
        array ('vars' =>
                    array(		'altname' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'Альт. имя статической страницы')),
                            'id' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID статической страницы')),
                    ),
                'descr'	=> array ('russian' => 'Отображение статической страницы'),
        )
);

$ULIB->registerCommand('search', '',
        array ('vars' =>        array(),
                'descr'	=> array ('russian' => 'Страница поиска'),
        )
);
*/

// ---------------------------------
/*
$UHANDLER->registerHandler(0,
    array(
        'pluginName'		=> 'news',
        'handlerName'		=> 'news',
        'flagPrimary'		=> true,
        'flagFailContinue'	=> false,
        'callbackFunc'		=> 'defaultCallbackFunction',
        'rstyle'	=> array(
            'rcmd'			=> '/{category}/{altname}[/page{page}].html',
            'regex'			=> '#^/([^/]+?)/([^/]+?)(?:/page(\d+))?.html#',
            'regexMap'		=> array(
                1	=> 'catname',
                2	=> 'altname',
                3	=> 'page',
            ),
            'reqCheck'		=> array(),
            'setVars'		=> array(),
            'genrMAP'		=> array(
                array(0, '/'),
                array(1, 'catname'),
                array(0, '/'),
                array(1, 'altname'),
                array(0, '/page', 'page'),
                array(1, 'page', 'page'),
                array(0, '.html'),
            ),
        ),
    )
);

$UHANDLER->registerHandler(0,
    array(
        'pluginName'		=> 'news',
        'handlerName'		=> 'by.category',
        'flagPrimary'		=> true,
        'flagFailContinue'	=> false,
        'callbackFunc'		=> 'defaultCallbackFunction',
        'rstyle'	=> array(
            'rcmd'			=> '/category/{category}[/page/{page}].html',
            'regex'			=> '#^/category/([^/]+?)(?:/page/(\d+))?.html#',
            'regexMap'		=> array(
                1	=> 'catname',
                2	=> 'page',
            ),
            'reqCheck'		=> array(),
            'setVars'		=> array(),
            'genrMAP'		=> array(
                array(0, '/category/'),
                array(1, 'catname'),
                array(0, '/page/', 'page'),
                array(1, 'page', 'page'),
                array(0, '.html'),
            ),
        ),
    )
);

$UHANDLER->registerHandler(0,
    array(
        'pluginName'		=> 'news',
        'handlerName'		=> 'by.year',
        'flagPrimary'		=> true,
        'flagFailContinue'	=> false,
        'callbackFunc'		=> 'defaultCallbackFunction',
        'rstyle'	=> array(
            'rcmd'			=> '/{year}[-page{page}].html',
            'regex'			=> '#^/(\d{4})(?:\-page(\d+)).html#',
            'regexMap'		=> array(
                1	=> 'year',
                2	=> 'page',
            ),
            'reqCheck'		=> array(),
            'setVars'		=> array(),
            'genrMAP'		=> array(
                array(0, '/'),
                array(1, 'year'),
                array(0, '-page', 'page'),
                array(1, 'page', 'page'),
                array(0, '.html'),
            ),
        ),
    )
);

$UHANDLER->registerHandler(0,
    array(
        'pluginName'		=> 'news',
        'handlerName'		=> 'by.month',
        'flagPrimary'		=> true,
        'flagFailContinue'	=> false,
        'callbackFunc'		=> 'defaultCallbackFunction',
        'rstyle'	=> array(
            'rcmd'			=> '/{year}-{month}[-page{page}].html',
            'regex'			=> '#^/(\d{4})-(\d{2})(?:\-page(\d+)).html#',
            'regexMap'		=> array(
                1	=> 'year',
                2	=> 'month',
                3	=> 'page',
            ),
            'reqCheck'		=> array(),
            'setVars'		=> array(),
            'genrMAP'		=> array(
                array(0, '/'),
                array(1, 'year'),
                array(0, '-'),
                array(1, 'month'),
                array(0, '-page', 'page'),
                array(1, 'page', 'page'),
                array(0, '.html'),
            ),
        ),
    )
);

$UHANDLER->registerHandler(0,
    array(
        'pluginName'		=> 'news',
        'handlerName'		=> 'by.day',
        'flagPrimary'		=> true,
        'flagFailContinue'	=> false,
        'callbackFunc'		=> 'defaultCallbackFunction',
        'rstyle'	=> array(
            'rcmd'			=> '/{year}-{month}-{day}[-page{page}].html',
            'regex'			=> '#^/(\d{4})-(\d{2})-(\d{2})(?:\-page(\d+)).html#',
            'regexMap'		=> array(
                1	=> 'year',
                2	=> 'month',
                3	=> 'day',
                4	=> 'page',
            ),
            'reqCheck'		=> array(),
            'setVars'		=> array(),
            'genrMAP'		=> array(
                array(0, '/'),
                array(1, 'year'),
                array(0, '-'),
                array(1, 'month'),
                array(0, '-'),
                array(1, 'day'),
                array(0, '-page', 'page'),
                array(1, 'page', 'page'),
                array(0, '.html'),
            ),
        ),
    )
);

*/
// --------

$ULIB->saveConfig();
//$UHANDLER->saveConfig();
