<?php

@include_once '..\core.php';

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

include_once '../includes/classes/uhandler.class.php';
$ULIB = new urlLibrary();
$ULIB->loadConfig();

$UHANDLER = new urlHandler();
$UHANDLER->loadConfig();

$ULIB->registerCommand('core', 'plugin',
		array ('vars' =>
					array(	'plugin' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'ID �������')),
							'handler' => array('matchRegex' => '.+?', 'descr' => array('russian' => '������������ �������')),
					),
				'descr'	=> array ('russian' => '�������� �������'),
		)
);

$ULIB->registerCommand('core', 'registration',
		array ('vars' => array(),
				'descr'	=> array ('russian' => '����������� ������ ������������'),
		)
);

$ULIB->registerCommand('core', 'activation',
		array ('vars' => array(		'userid' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID ������������')),
						'code'	=> array('matchRegex' => '.+?', 'descr' => array( 'russian' => '��� ���������')),
					
				),
				'descr'	=> array ('russian' => '��������� ������ ������������'),
		)
);

$ULIB->registerCommand('core', 'lostpassword',
		array ('vars' => array(		'userid' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID ������������')),
						'code'	=> array('matchRegex' => '.+?', 'descr' => array( 'russian' => '��� ���������')),
					
				),
				'descr'	=> array ('russian' => '�������������� ����������� ������'),
		)
);

$ULIB->registerCommand('core', 'login',
		array ('vars' => array(),
				'descr'	=> array ('russian' => '���� �� ���� (�����������)'),
		)
);

$ULIB->registerCommand('core', 'logout',
		array ('vars' => array(),
				'descr'	=> array ('russian' => '����� � �����'),
		)
);

$ULIB->registerCommand('news', 'main',
		array ('vars' =>
					array(	'page' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => '��������')),
					),
				'descr'	=> array ('russian' => '������� ��������� ��������'),
		)
);

$ULIB->registerCommand('news', 'by.category',
		array ('vars' =>
					array(	'category' => array('matchRegex' => '.+?', 'descr' => array('russian' => '����. ��� ���������')),
							'catid' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => 'ID ���������')),
							'page' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => '��������')),
					),
				'descr'	=> array ('russian' => '������� �� �������� ���������'),
		)
);

$ULIB->registerCommand('news', 'news',
		array ('vars' =>
					array(	'category' => array('matchRegex' => '.+?', 'descr' => array('russian' => '����. ��� ���������')),
							'catid' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => 'ID ���������')),
							'year' => array('matchRegex' => '\d{4}', 'descr' => array ('russian' => '���')),
							'month' => array('matchRegex' => '\d{2}', 'descr' => array ('russian' => '�����')),
							'day' => array('matchRegex' => '\d{2}', 'descr' => array ('russian' => '����')),							'page' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => '�������� ������ �������')),
							'altname' => array('matchRegex' => '.+?', 'descr' => array('russian' => '����. ��� �������')),
							'id' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => 'ID �������')),
							'page' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => '�������� ������ �������')),
					),
				'descr'	=> array ('russian' => '����������� ������ �������'),
		)
);

$ULIB->registerCommand('news', 'by.year',
		array ('vars' =>
					array(	'year' => array('matchRegex' => '\d{4}', 'descr' => array('russian' => '���')),
							'page' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => '��������')),
					),
				'descr'	=> array ('russian' => '������� �� ���'),
		)
);


$ULIB->registerCommand('news', 'by.month',
		array ( 'vars' =>
			array(	'year' => array('matchRegex' => '\d{4}', 'descr' => array ('russian' => '���')),
					'month' => array('matchRegex' => '\d{2}', 'descr' => array ('russian' => '�����')),
					'page' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => '��������')),
			),
		'descr'	=> array ('russian' => '������� �� �����'),
	)
);

$ULIB->registerCommand('news', 'by.day',
		array ( 'vars' =>
			array(	'year' => array('matchRegex' => '\d{4}', 'descr' => array ('russian' => '���')),
					'month' => array('matchRegex' => '\d{2}', 'descr' => array ('russian' => '�����')),
					'day' => array('matchRegex' => '\d{2}', 'descr' => array ('russian' => '����')),
					'page' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => '��������')),
			),
		'descr'	=> array ('russian' => '������� �� ����'),
	)
);


$ULIB->registerCommand('rss_export', 'main',
		array ('vars' => array(),
		'descr'	=> array ('russian' => '�������� RSS �����'),
	)
);

$ULIB->registerCommand('rss_export', 'category',
		array ('vars' =>
			array(	'category' => array('matchRegex' => '.+?', 'descr' => array('russian' => '����. ��� ���������')),
				'catid' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => 'ID ���������')),
			),
		'descr'	=> array ('russian' => 'RSS ����� ��������� ���������'),
	)
);

$ULIB->registerCommand('uprofile', 'edit',
		array ('vars' =>
					array(),
				'descr'	=> array ('russian' => '�������������� ������������ �������'),
		)
);

$ULIB->registerCommand('uprofile', 'show',
		array ('vars' =>
			array(	'name' => array('matchRegex' => '.+?', 'descr' => array('russian' => '����� ������������')),
				'id' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID ������������')),
			),
				'descr'	=> array ('russian' => '�������� ������� ����������� ������������'),
		)
);


$ULIB->registerCommand('static', '',
		array ('vars' =>
					array(		'altname' => array('matchRegex' => '.+?', 'descr' => array('russian' => '����. ��� ����������� ��������')),
							'id' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => 'ID ����������� ��������')),
					),
				'descr'	=> array ('russian' => '����������� ����������� ��������'),
		)
);

$ULIB->registerCommand('search', '',
		array ('vars' =>        array(),
				'descr'	=> array ('russian' => '�������� ������'),
		)
);


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
			'regex'			=> '#^/([^/]+?)/([^/]+?)(?:/page(\d{1,4}))?.html#',
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
			'regex'			=> '#^/category/([^/]+?)(?:/page/(\d{1,4}))?.html#',
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
			'regex'			=> '#^/(\d{4})(?:\-page(\d{1,4})).html#',
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
			'regex'			=> '#^/(\d{4})-(\d{2})(?:\-page(\d{1,4})).html#',
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
			'regex'			=> '#^/(\d{4})-(\d{2})-(\d{2})(?:\-page(\d{1,4})).html#',
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
