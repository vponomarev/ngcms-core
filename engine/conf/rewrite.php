<?php

$handlerList = [
    0 => [
        'pluginName'       => 'core',
        'handlerName'      => 'login',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/login/',
            'regex'    => '#^/login/$#',
            'regexMap' => [
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/login/',
                    2 => 0,
                ],
            ],
        ],
    ],
    1 => [
        'pluginName'       => 'core',
        'handlerName'      => 'logout',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/logout/',
            'regex'    => '#^/logout/$#',
            'regexMap' => [
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/logout/',
                    2 => 0,
                ],
            ],
        ],
    ],
    2 => [
        'pluginName'       => 'core',
        'handlerName'      => 'registration',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/register/',
            'regex'    => '#^/register/$#',
            'regexMap' => [
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/register/',
                    2 => 0,
                ],
            ],
        ],
    ],
    3 => [
        'pluginName'       => 'core',
        'handlerName'      => 'activation',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/activate/[{userid}-{code}/]',
            'regex'    => '#^/activate/(?:(\\d+)-(.+?)/){0,1}$#',
            'regexMap' => [
                1 => 'userid',
                2 => 'code',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/activate/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'userid',
                    2 => 1,
                ],
                2 => [
                    0 => 0,
                    1 => '-',
                    2 => 1,
                ],
                3 => [
                    0 => 1,
                    1 => 'code',
                    2 => 1,
                ],
                4 => [
                    0 => 0,
                    1 => '/',
                    2 => 1,
                ],
            ],
        ],
    ],
    4 => [
        'pluginName'       => 'core',
        'handlerName'      => 'lostpassword',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/lostpassword/[{userid}-{code}/]',
            'regex'    => '#^/lostpassword/(?:(\\d+)-(.+?)/){0,1}$#',
            'regexMap' => [
                1 => 'userid',
                2 => 'code',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/lostpassword/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'userid',
                    2 => 1,
                ],
                2 => [
                    0 => 0,
                    1 => '-',
                    2 => 1,
                ],
                3 => [
                    0 => 1,
                    1 => 'code',
                    2 => 1,
                ],
                4 => [
                    0 => 0,
                    1 => '/',
                    2 => 1,
                ],
            ],
        ],
    ],
    5 => [
        'pluginName'       => 'core',
        'handlerName'      => 'plugin',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/plugin/{plugin}/[{handler}/]',
            'regex'    => '#^/plugin/(.+?)/(?:(.+?)/){0,1}$#',
            'regexMap' => [
                1 => 'plugin',
                2 => 'handler',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/plugin/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'plugin',
                    2 => 0,
                ],
                2 => [
                    0 => 0,
                    1 => '/',
                    2 => 0,
                ],
                3 => [
                    0 => 1,
                    1 => 'handler',
                    2 => 1,
                ],
                4 => [
                    0 => 0,
                    1 => '/',
                    2 => 1,
                ],
            ],
        ],
    ],
    6 => [
        'pluginName'       => 'news',
        'handlerName'      => 'main',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/[page/{page}/]',
            'regex'    => '#^/(?:page/(\\d+)/){0,1}$#',
            'regexMap' => [
                1 => 'page',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/',
                    2 => 0,
                ],
                1 => [
                    0 => 0,
                    1 => 'page/',
                    2 => 1,
                ],
                2 => [
                    0 => 1,
                    1 => 'page',
                    2 => 1,
                ],
                3 => [
                    0 => 0,
                    1 => '/',
                    2 => 1,
                ],
            ],
        ],
    ],
    7 => [
        'pluginName'       => 'static',
        'handlerName'      => '',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/static/{altname}.html',
            'regex'    => '#^/static/(.+?).html$#',
            'regexMap' => [
                1 => 'altname',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/static/',
                    2 => 0,
                ],
                1 => [
                    0 => 2,
                    1 => 'altname',
                    2 => 0,
                ],
                2 => [
                    0 => 0,
                    1 => '.html',
                    2 => 0,
                ],
            ],
        ],
    ],
    8 => [
        'pluginName'       => 'static',
        'handlerName'      => 'print',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/static/{altname}.print',
            'regex'    => '#^/static/(.+?).print$#',
            'regexMap' => [
                1 => 'altname',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/static/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'altname',
                    2 => 0,
                ],
                2 => [
                    0 => 0,
                    1 => '.print',
                    2 => 0,
                ],
            ],
        ],
    ],
    9 => [
        'pluginName'       => 'uprofile',
        'handlerName'      => 'edit',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/profile.html',
            'regex'    => '#^/profile.html$#',
            'regexMap' => [
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/profile.html',
                    2 => 0,
                ],
            ],
        ],
    ],
    10 => [
        'pluginName'       => 'uprofile',
        'handlerName'      => 'show',
        'flagPrimary'      => false,
        'flagFailContinue' => true,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/users/{id}.html',
            'regex'    => '#^/users/(\\d+).html$#',
            'regexMap' => [
                1 => 'id',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/users/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'id',
                    2 => 0,
                ],
                2 => [
                    0 => 0,
                    1 => '.html',
                    2 => 0,
                ],
            ],
        ],
    ],
    11 => [
        'pluginName'       => 'uprofile',
        'handlerName'      => 'show',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/users/{name}.html',
            'regex'    => '#^/users/(.+?).html$#',
            'regexMap' => [
                1 => 'name',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/users/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'name',
                    2 => 0,
                ],
                2 => [
                    0 => 0,
                    1 => '.html',
                    2 => 0,
                ],
            ],
        ],
    ],
    12 => [
        'pluginName'       => 'rss_export',
        'handlerName'      => '',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/rss.xml',
            'regex'    => '#^/rss.xml$#',
            'regexMap' => [
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/rss.xml',
                    2 => 0,
                ],
            ],
        ],
    ],
    13 => [
        'pluginName'       => 'search',
        'handlerName'      => '',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/search/',
            'regex'    => '#^/search/$#',
            'regexMap' => [
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/search/',
                    2 => 0,
                ],
            ],
        ],
    ],
    14 => [
        'pluginName'       => 'news',
        'handlerName'      => 'by.day',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/{year}-{month}-{day}[-page{page}].html',
            'regex'    => '#^/(\\d{4})-(\\d{2})-(\\d{2})(?:-page(\\d{1,4})){0,1}.html$#',
            'regexMap' => [
                1 => 'year',
                2 => 'month',
                3 => 'day',
                4 => 'page',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'year',
                    2 => 0,
                ],
                2 => [
                    0 => 0,
                    1 => '-',
                    2 => 0,
                ],
                3 => [
                    0 => 1,
                    1 => 'month',
                    2 => 0,
                ],
                4 => [
                    0 => 0,
                    1 => '-',
                    2 => 0,
                ],
                5 => [
                    0 => 1,
                    1 => 'day',
                    2 => 0,
                ],
                6 => [
                    0 => 0,
                    1 => '-page',
                    2 => 1,
                ],
                7 => [
                    0 => 1,
                    1 => 'page',
                    2 => 1,
                ],
                8 => [
                    0 => 0,
                    1 => '.html',
                    2 => 0,
                ],
            ],
        ],
    ],
    15 => [
        'pluginName'       => 'news',
        'handlerName'      => 'by.month',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/{year}-{month}[-page{page}].html',
            'regex'    => '#^/(\\d{4})-(\\d{2})(?:-page(\\d+)){0,1}.html$#',
            'regexMap' => [
                1 => 'year',
                2 => 'month',
                3 => 'page',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'year',
                    2 => 0,
                ],
                2 => [
                    0 => 0,
                    1 => '-',
                    2 => 0,
                ],
                3 => [
                    0 => 1,
                    1 => 'month',
                    2 => 0,
                ],
                4 => [
                    0 => 0,
                    1 => '-page',
                    2 => 1,
                ],
                5 => [
                    0 => 1,
                    1 => 'page',
                    2 => 1,
                ],
                6 => [
                    0 => 0,
                    1 => '.html',
                    2 => 0,
                ],
            ],
        ],
    ],
    16 => [
        'pluginName'       => 'news',
        'handlerName'      => 'by.year',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/{year}[-page{page}].html',
            'regex'    => '#^/(\\d{4})(?:-page(\\d+)){0,1}.html$#',
            'regexMap' => [
                1 => 'year',
                2 => 'page',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'year',
                    2 => 0,
                ],
                2 => [
                    0 => 0,
                    1 => '-page',
                    2 => 1,
                ],
                3 => [
                    0 => 1,
                    1 => 'page',
                    2 => 1,
                ],
                4 => [
                    0 => 0,
                    1 => '.html',
                    2 => 0,
                ],
            ],
        ],
    ],
    17 => [
        'pluginName'       => 'rss_export',
        'handlerName'      => 'category',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/{category}.xml',
            'regex'    => '#^/(.+?).xml$#',
            'regexMap' => [
                1 => 'category',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'category',
                    2 => 0,
                ],
                2 => [
                    0 => 0,
                    1 => '.xml',
                    2 => 0,
                ],
            ],
        ],
    ],
    18 => [
        'pluginName'       => 'news',
        'handlerName'      => 'by.category',
        'flagPrimary'      => false,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/{category}/page/{page}.html',
            'regex'    => '#^/(.+?)/page/(\\d+).html$#',
            'regexMap' => [
                1 => 'category',
                2 => 'page',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'category',
                    2 => 0,
                ],
                2 => [
                    0 => 0,
                    1 => '/page/',
                    2 => 0,
                ],
                3 => [
                    0 => 1,
                    1 => 'page',
                    2 => 0,
                ],
                4 => [
                    0 => 0,
                    1 => '.html',
                    2 => 0,
                ],
            ],
        ],
    ],
    19 => [
        'pluginName'       => 'news',
        'handlerName'      => 'news',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/{category}/{altname}[/page{page}].html',
            'regex'    => '#^/(.+?)/(.+?)(?:/page(\\d+)){0,1}.html$#',
            'regexMap' => [
                1 => 'category',
                2 => 'altname',
                3 => 'page',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'category',
                    2 => 0,
                ],
                2 => [
                    0 => 0,
                    1 => '/',
                    2 => 0,
                ],
                3 => [
                    0 => 1,
                    1 => 'altname',
                    2 => 0,
                ],
                4 => [
                    0 => 0,
                    1 => '/page',
                    2 => 1,
                ],
                5 => [
                    0 => 1,
                    1 => 'page',
                    2 => 1,
                ],
                6 => [
                    0 => 0,
                    1 => '.html',
                    2 => 0,
                ],
            ],
        ],
    ],
    20 => [
        'pluginName'       => 'news',
        'handlerName'      => 'print',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/{category}/{altname}[/page{page}].print',
            'regex'    => '#^/(.+?)/(.+?)(?:/page(\\d+)){0,1}.print$#',
            'regexMap' => [
                1 => 'category',
                2 => 'altname',
                3 => 'page',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'category',
                    2 => 0,
                ],
                2 => [
                    0 => 0,
                    1 => '/',
                    2 => 0,
                ],
                3 => [
                    0 => 1,
                    1 => 'altname',
                    2 => 0,
                ],
                4 => [
                    0 => 0,
                    1 => '/page',
                    2 => 1,
                ],
                5 => [
                    0 => 1,
                    1 => 'page',
                    2 => 1,
                ],
                6 => [
                    0 => 0,
                    1 => '.print',
                    2 => 0,
                ],
            ],
        ],
    ],
    21 => [
        'pluginName'       => 'news',
        'handlerName'      => 'by.category',
        'flagPrimary'      => true,
        'flagFailContinue' => false,
        'flagDisabled'     => false,
        'rstyle'           => [
            'rcmd'     => '/{category}[/page/{page}].html',
            'regex'    => '#^/(.+?)(?:/page/(\\d+)){0,1}.html$#',
            'regexMap' => [
                1 => 'category',
                2 => 'page',
            ],
            'reqCheck' => [
            ],
            'setVars' => [
            ],
            'genrMAP' => [
                0 => [
                    0 => 0,
                    1 => '/',
                    2 => 0,
                ],
                1 => [
                    0 => 1,
                    1 => 'category',
                    2 => 0,
                ],
                2 => [
                    0 => 0,
                    1 => '/page/',
                    2 => 1,
                ],
                3 => [
                    0 => 1,
                    1 => 'page',
                    2 => 1,
                ],
                4 => [
                    0 => 0,
                    1 => '.html',
                    2 => 0,
                ],
            ],
        ],
    ],
];
$handlerPrimary = [
    'core' => [
        'login' => [
            0 => 0,
            1 => true,
        ],
        'logout' => [
            0 => 1,
            1 => true,
        ],
        'registration' => [
            0 => 2,
            1 => true,
        ],
        'activation' => [
            0 => 3,
            1 => true,
        ],
        'lostpassword' => [
            0 => 4,
            1 => true,
        ],
        'plugin' => [
            0 => 5,
            1 => true,
        ],
    ],
    'news' => [
        'main' => [
            0 => 6,
            1 => true,
        ],
        'by.day' => [
            0 => 14,
            1 => true,
        ],
        'by.month' => [
            0 => 15,
            1 => true,
        ],
        'by.year' => [
            0 => 16,
            1 => true,
        ],
        'by.category' => [
            0 => 21,
            1 => true,
        ],
        'news' => [
            0 => 19,
            1 => true,
        ],
        'print' => [
            0 => 20,
            1 => true,
        ],
    ],
    'static' => [
        '' => [
            0 => 7,
            1 => true,
        ],
        'print' => [
            0 => 8,
            1 => true,
        ],
    ],
    'uprofile' => [
        'edit' => [
            0 => 9,
            1 => true,
        ],
        'show' => [
            0 => 11,
            1 => true,
        ],
    ],
    'rss_export' => [
        '' => [
            0 => 12,
            1 => true,
        ],
        'category' => [
            0 => 17,
            1 => true,
        ],
    ],
    'search' => [
        '' => [
            0 => 13,
            1 => true,
        ],
    ],
];
