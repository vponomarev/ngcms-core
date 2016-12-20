<?php
$handlerList = array (
  0 => 
  array (
    'pluginName' => 'core',
    'handlerName' => 'login',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/login/',
      'regex' => '#^/login/$#',
      'regexMap' => 
      array (
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/login/',
          2 => 0,
        ),
      ),
    ),
  ),
  1 => 
  array (
    'pluginName' => 'core',
    'handlerName' => 'logout',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/logout/',
      'regex' => '#^/logout/$#',
      'regexMap' => 
      array (
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/logout/',
          2 => 0,
        ),
      ),
    ),
  ),
  2 => 
  array (
    'pluginName' => 'core',
    'handlerName' => 'registration',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/register/',
      'regex' => '#^/register/$#',
      'regexMap' => 
      array (
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/register/',
          2 => 0,
        ),
      ),
    ),
  ),
  3 => 
  array (
    'pluginName' => 'core',
    'handlerName' => 'activation',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/activate/[{userid}-{code}/]',
      'regex' => '#^/activate/(?:(\\d+)-(.+?)/){0,1}$#',
      'regexMap' => 
      array (
        1 => 'userid',
        2 => 'code',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/activate/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'userid',
          2 => 1,
        ),
        2 => 
        array (
          0 => 0,
          1 => '-',
          2 => 1,
        ),
        3 => 
        array (
          0 => 1,
          1 => 'code',
          2 => 1,
        ),
        4 => 
        array (
          0 => 0,
          1 => '/',
          2 => 1,
        ),
      ),
    ),
  ),
  4 => 
  array (
    'pluginName' => 'core',
    'handlerName' => 'lostpassword',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/lostpassword/[{userid}-{code}/]',
      'regex' => '#^/lostpassword/(?:(\\d+)-(.+?)/){0,1}$#',
      'regexMap' => 
      array (
        1 => 'userid',
        2 => 'code',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/lostpassword/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'userid',
          2 => 1,
        ),
        2 => 
        array (
          0 => 0,
          1 => '-',
          2 => 1,
        ),
        3 => 
        array (
          0 => 1,
          1 => 'code',
          2 => 1,
        ),
        4 => 
        array (
          0 => 0,
          1 => '/',
          2 => 1,
        ),
      ),
    ),
  ),
  5 => 
  array (
    'pluginName' => 'core',
    'handlerName' => 'plugin',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/plugin/{plugin}/[{handler}/]',
      'regex' => '#^/plugin/(.+?)/(?:(.+?)/){0,1}$#',
      'regexMap' => 
      array (
        1 => 'plugin',
        2 => 'handler',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/plugin/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'plugin',
          2 => 0,
        ),
        2 => 
        array (
          0 => 0,
          1 => '/',
          2 => 0,
        ),
        3 => 
        array (
          0 => 1,
          1 => 'handler',
          2 => 1,
        ),
        4 => 
        array (
          0 => 0,
          1 => '/',
          2 => 1,
        ),
      ),
    ),
  ),
  6 => 
  array (
    'pluginName' => 'news',
    'handlerName' => 'main',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/[page/{page}/]',
      'regex' => '#^/(?:page/(\\d+)/){0,1}$#',
      'regexMap' => 
      array (
        1 => 'page',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 0,
          1 => 'page/',
          2 => 1,
        ),
        2 => 
        array (
          0 => 1,
          1 => 'page',
          2 => 1,
        ),
        3 => 
        array (
          0 => 0,
          1 => '/',
          2 => 1,
        ),
      ),
    ),
  ),
  7 => 
  array (
    'pluginName' => 'static',
    'handlerName' => '',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/static/{altname}.html',
      'regex' => '#^/static/(.+?).html$#',
      'regexMap' => 
      array (
        1 => 'altname',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/static/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 2,
          1 => 'altname',
          2 => 0,
        ),
        2 => 
        array (
          0 => 0,
          1 => '.html',
          2 => 0,
        ),
      ),
    ),
  ),
  8 => 
  array (
    'pluginName' => 'static',
    'handlerName' => 'print',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/static/{altname}.print',
      'regex' => '#^/static/(.+?).print$#',
      'regexMap' => 
      array (
        1 => 'altname',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/static/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'altname',
          2 => 0,
        ),
        2 => 
        array (
          0 => 0,
          1 => '.print',
          2 => 0,
        ),
      ),
    ),
  ),
  9 => 
  array (
    'pluginName' => 'uprofile',
    'handlerName' => 'edit',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/profile.html',
      'regex' => '#^/profile.html$#',
      'regexMap' => 
      array (
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/profile.html',
          2 => 0,
        ),
      ),
    ),
  ),
  10 => 
  array (
    'pluginName' => 'uprofile',
    'handlerName' => 'show',
    'flagPrimary' => false,
    'flagFailContinue' => true,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/users/{id}.html',
      'regex' => '#^/users/(\\d+).html$#',
      'regexMap' => 
      array (
        1 => 'id',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/users/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'id',
          2 => 0,
        ),
        2 => 
        array (
          0 => 0,
          1 => '.html',
          2 => 0,
        ),
      ),
    ),
  ),
  11 => 
  array (
    'pluginName' => 'uprofile',
    'handlerName' => 'show',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/users/{name}.html',
      'regex' => '#^/users/(.+?).html$#',
      'regexMap' => 
      array (
        1 => 'name',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/users/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'name',
          2 => 0,
        ),
        2 => 
        array (
          0 => 0,
          1 => '.html',
          2 => 0,
        ),
      ),
    ),
  ),
  12 => 
  array (
    'pluginName' => 'rss_export',
    'handlerName' => '',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/rss.xml',
      'regex' => '#^/rss.xml$#',
      'regexMap' => 
      array (
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/rss.xml',
          2 => 0,
        ),
      ),
    ),
  ),
  13 => 
  array (
    'pluginName' => 'search',
    'handlerName' => '',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/search/',
      'regex' => '#^/search/$#',
      'regexMap' => 
      array (
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/search/',
          2 => 0,
        ),
      ),
    ),
  ),
  14 => 
  array (
    'pluginName' => 'news',
    'handlerName' => 'by.day',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/{year}-{month}-{day}[-page{page}].html',
      'regex' => '#^/(\\d{4})-(\\d{2})-(\\d{2})(?:-page(\\d{1,4})){0,1}.html$#',
      'regexMap' => 
      array (
        1 => 'year',
        2 => 'month',
        3 => 'day',
        4 => 'page',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'year',
          2 => 0,
        ),
        2 => 
        array (
          0 => 0,
          1 => '-',
          2 => 0,
        ),
        3 => 
        array (
          0 => 1,
          1 => 'month',
          2 => 0,
        ),
        4 => 
        array (
          0 => 0,
          1 => '-',
          2 => 0,
        ),
        5 => 
        array (
          0 => 1,
          1 => 'day',
          2 => 0,
        ),
        6 => 
        array (
          0 => 0,
          1 => '-page',
          2 => 1,
        ),
        7 => 
        array (
          0 => 1,
          1 => 'page',
          2 => 1,
        ),
        8 => 
        array (
          0 => 0,
          1 => '.html',
          2 => 0,
        ),
      ),
    ),
  ),
  15 => 
  array (
    'pluginName' => 'news',
    'handlerName' => 'by.month',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/{year}-{month}[-page{page}].html',
      'regex' => '#^/(\\d{4})-(\\d{2})(?:-page(\\d+)){0,1}.html$#',
      'regexMap' => 
      array (
        1 => 'year',
        2 => 'month',
        3 => 'page',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'year',
          2 => 0,
        ),
        2 => 
        array (
          0 => 0,
          1 => '-',
          2 => 0,
        ),
        3 => 
        array (
          0 => 1,
          1 => 'month',
          2 => 0,
        ),
        4 => 
        array (
          0 => 0,
          1 => '-page',
          2 => 1,
        ),
        5 => 
        array (
          0 => 1,
          1 => 'page',
          2 => 1,
        ),
        6 => 
        array (
          0 => 0,
          1 => '.html',
          2 => 0,
        ),
      ),
    ),
  ),
  16 => 
  array (
    'pluginName' => 'news',
    'handlerName' => 'by.year',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/{year}[-page{page}].html',
      'regex' => '#^/(\\d{4})(?:-page(\\d+)){0,1}.html$#',
      'regexMap' => 
      array (
        1 => 'year',
        2 => 'page',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'year',
          2 => 0,
        ),
        2 => 
        array (
          0 => 0,
          1 => '-page',
          2 => 1,
        ),
        3 => 
        array (
          0 => 1,
          1 => 'page',
          2 => 1,
        ),
        4 => 
        array (
          0 => 0,
          1 => '.html',
          2 => 0,
        ),
      ),
    ),
  ),
  17 => 
  array (
    'pluginName' => 'rss_export',
    'handlerName' => 'category',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/{category}.xml',
      'regex' => '#^/(.+?).xml$#',
      'regexMap' => 
      array (
        1 => 'category',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'category',
          2 => 0,
        ),
        2 => 
        array (
          0 => 0,
          1 => '.xml',
          2 => 0,
        ),
      ),
    ),
  ),
  18 => 
  array (
    'pluginName' => 'news',
    'handlerName' => 'by.category',
    'flagPrimary' => false,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/{category}/page/{page}.html',
      'regex' => '#^/(.+?)/page/(\\d+).html$#',
      'regexMap' => 
      array (
        1 => 'category',
        2 => 'page',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'category',
          2 => 0,
        ),
        2 => 
        array (
          0 => 0,
          1 => '/page/',
          2 => 0,
        ),
        3 => 
        array (
          0 => 1,
          1 => 'page',
          2 => 0,
        ),
        4 => 
        array (
          0 => 0,
          1 => '.html',
          2 => 0,
        ),
      ),
    ),
  ),
  19 => 
  array (
    'pluginName' => 'news',
    'handlerName' => 'news',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/{category}/{altname}[/page{page}].html',
      'regex' => '#^/(.+?)/(.+?)(?:/page(\\d+)){0,1}.html$#',
      'regexMap' => 
      array (
        1 => 'category',
        2 => 'altname',
        3 => 'page',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'category',
          2 => 0,
        ),
        2 => 
        array (
          0 => 0,
          1 => '/',
          2 => 0,
        ),
        3 => 
        array (
          0 => 1,
          1 => 'altname',
          2 => 0,
        ),
        4 => 
        array (
          0 => 0,
          1 => '/page',
          2 => 1,
        ),
        5 => 
        array (
          0 => 1,
          1 => 'page',
          2 => 1,
        ),
        6 => 
        array (
          0 => 0,
          1 => '.html',
          2 => 0,
        ),
      ),
    ),
  ),
  20 => 
  array (
    'pluginName' => 'news',
    'handlerName' => 'print',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/{category}/{altname}[/page{page}].print',
      'regex' => '#^/(.+?)/(.+?)(?:/page(\\d+)){0,1}.print$#',
      'regexMap' => 
      array (
        1 => 'category',
        2 => 'altname',
        3 => 'page',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'category',
          2 => 0,
        ),
        2 => 
        array (
          0 => 0,
          1 => '/',
          2 => 0,
        ),
        3 => 
        array (
          0 => 1,
          1 => 'altname',
          2 => 0,
        ),
        4 => 
        array (
          0 => 0,
          1 => '/page',
          2 => 1,
        ),
        5 => 
        array (
          0 => 1,
          1 => 'page',
          2 => 1,
        ),
        6 => 
        array (
          0 => 0,
          1 => '.print',
          2 => 0,
        ),
      ),
    ),
  ),
  21 => 
  array (
    'pluginName' => 'news',
    'handlerName' => 'by.category',
    'flagPrimary' => true,
    'flagFailContinue' => false,
    'flagDisabled' => false,
    'rstyle' => 
    array (
      'rcmd' => '/{category}[/page/{page}].html',
      'regex' => '#^/(.+?)(?:/page/(\\d+)){0,1}.html$#',
      'regexMap' => 
      array (
        1 => 'category',
        2 => 'page',
      ),
      'reqCheck' => 
      array (
      ),
      'setVars' => 
      array (
      ),
      'genrMAP' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => '/',
          2 => 0,
        ),
        1 => 
        array (
          0 => 1,
          1 => 'category',
          2 => 0,
        ),
        2 => 
        array (
          0 => 0,
          1 => '/page/',
          2 => 1,
        ),
        3 => 
        array (
          0 => 1,
          1 => 'page',
          2 => 1,
        ),
        4 => 
        array (
          0 => 0,
          1 => '.html',
          2 => 0,
        ),
      ),
    ),
  ),
);
$handlerPrimary = array (
  'core' => 
  array (
    'login' => 
    array (
      0 => 0,
      1 => true,
    ),
    'logout' => 
    array (
      0 => 1,
      1 => true,
    ),
    'registration' => 
    array (
      0 => 2,
      1 => true,
    ),
    'activation' => 
    array (
      0 => 3,
      1 => true,
    ),
    'lostpassword' => 
    array (
      0 => 4,
      1 => true,
    ),
    'plugin' => 
    array (
      0 => 5,
      1 => true,
    ),
  ),
  'news' => 
  array (
    'main' => 
    array (
      0 => 6,
      1 => true,
    ),
    'by.day' => 
    array (
      0 => 14,
      1 => true,
    ),
    'by.month' => 
    array (
      0 => 15,
      1 => true,
    ),
    'by.year' => 
    array (
      0 => 16,
      1 => true,
    ),
    'by.category' => 
    array (
      0 => 21,
      1 => true,
    ),
    'news' => 
    array (
      0 => 19,
      1 => true,
    ),
    'print' => 
    array (
      0 => 20,
      1 => true,
    ),
  ),
  'static' => 
  array (
    '' => 
    array (
      0 => 7,
      1 => true,
    ),
    'print' => 
    array (
      0 => 8,
      1 => true,
    ),
  ),
  'uprofile' => 
  array (
    'edit' => 
    array (
      0 => 9,
      1 => true,
    ),
    'show' => 
    array (
      0 => 11,
      1 => true,
    ),
  ),
  'rss_export' => 
  array (
    '' => 
    array (
      0 => 12,
      1 => true,
    ),
    'category' => 
    array (
      0 => 17,
      1 => true,
    ),
  ),
  'search' => 
  array (
    '' => 
    array (
      0 => 13,
      1 => true,
    ),
  ),
);