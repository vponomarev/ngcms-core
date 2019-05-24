<?php
global $permRules;
$permRules = array(
	'#admin'	=> array(
		'title'	=> 'Административные настройки CMS',
		'description' => '',
		'items' => array(
			'system' => array(
				'title'	=> 'Общие настройки системы',
				'items'	=> array(
					'admpanel.view'					=> array(	'title'	=> 'Доступ в админ-панель',								),
					'lockedsite.view'				=> array(	'title'	=> 'Просмотр заблокированного сайта',					),
					'*'								=> array(	'title'	=> '** Значение по умолчанию **'						),

				),
			),
			'configuration' => array(
				'title'	=> 'Управление глобальными настройками CMS',
				'items'	=> array(
					'details'					=> array(	'title'	=> 'Просмотр настроек',										),
					'modify'					=> array(	'title'	=> 'Редактирование настроек',								),
					'*'							=> array(	'title'	=> '** Значение по умолчанию **',							),

				),
			),
			'static' => array(
				'title'	=> 'Управление статическими страницами',
				'items'	=> array(
					'view'						=> array(	'title'	=> 'Просмотр списка',										),
					'details'					=> array(	'title'	=> 'Просмотр конкретных статических страниц',				),
					'modify'					=> array(	'title'	=> 'Редактирование статических страниц',					),
//					'*'							=> array(	'title'	=> '** DEFAULT **',											),
				),
			),
			'users' => array(
				'title'	=> 'Управление пользователями',
				'items'	=> array(
					//					'*'							=> array(	'title'	=> '** DEFAULT **',											),
					'view'						=> array(	'title'	=> 'Просмотр списка',										),
					'details'					=> array(	'title'	=> 'Просмотр профиля пользователя',							),
					'modify'					=> array(	'title'	=> 'Редактирование профиля пользователя',					),
				),
			),
			'cron' => array(
				'title'	=> 'Управление планировщиком задач',
				'items'	=> array(
					'details'					=> array(	'title'	=> 'Просмотр настроек планировщика задач',					),
					'modify'					=> array(	'title'	=> 'Изменение настроек планировщика задач',					),
				),
			),
			'rewrite' => array(
				'title'	=> 'Управление форматом ссылок',
				'items'	=> array(
					'details'					=> array(	'title'	=> 'Просмотр настроек управления ссылками',					),
					'modify'					=> array(	'title'	=> 'Изменение настроек управления ссылок',					),
				),
			),
			'ipban' => array(
				'title'	=> 'Блокировка пользователей по IP адресу',
				'items'	=> array(
					'view'						=> array(	'title'	=> 'Просмотр списка заблокированных IP адресов',			),
					'modify'					=> array(	'title'	=> 'Редактирование списка',									),
//					'*'							=> array(	'title'	=> '** DEFAULT **',											),
				),
			),
			'categories' => array(
				'title'	=> 'Управление категориями',
				'items'	=> array(
					'view'						=> array(	'title'	=> 'Просмотр списка категорий',								),
					'details'					=> array(	'title'	=> 'Просмотр настроек конкретных категорий',				),
					'modify'					=> array(	'title'	=> 'Редактирование категорий',								),
					'list.admin'					=> array(	'title' => 'Категории, в которых разрешено управление новостями', 'type' => 'listCategoriesSelector#withDefault'),
					//					'*'							=> array(	'title'	=> '** DEFAULT **',											),
				),
			),
			'news' => array(
				'title' => 'Управление новостями',
				'description' => 'Интерфейс управления новостями (добавление, удаление,..)',
				'items' => array(
					'view'						=> array(	'title' => 'Просмотр списка новостей',							),
					'add'						=> array(	'title'	=> 'Добавление новостей',								),
					'add.mainpage'				=> array(	'title'	=> 'Значение по умолчанию для флага "размещение новости на главной"',		),
					'add.pinned'				=> array(	'title'	=> 'Значение по умолчанию для флага "прикрепление новости на главной"',		),
					'add.catpinned'				=> array(	'title'	=> 'Значение по умолчанию для флага "прикрепление новости в категории"',	),
					'add.favorite'				=> array(	'title'	=> 'Значение по умолчанию для флага "добавление новости в закладки"',		),
					'add.html'					=> array(	'title'	=> 'Значение по умолчанию для флага "использование HTML кода в новостях"',	),
					'add.raw'					=> array(	'title'	=> 'Значение по умолчанию для флага "отключить автоформатирование"',	),
					'personal.list'				=> array(	'title'	=> 'Собственные новости: Просмотр списка',				),
					'personal.view'				=> array(	'title'	=> 'Собственные новости: Просмотр содержимого',			),
					'personal.modify'			=> array(	'title'	=> 'Собственные новости: Редактирование',				),
					'personal.modify.published'	=> array(	'title'	=> 'Собственные новости: Редактирование опубликованных',	),
					'personal.publish'			=> array(	'title'	=> 'Собственные новости: Публикация',					),
					'personal.unpublish'		=> array(	'title'	=> 'Собственные новости: Снятие с публикации',			),
					'personal.delete'			=> array(	'title'	=> 'Собственные новости: Удаление',						),
					'personal.delete.published'	=> array(	'title'	=> 'Собственные новости: Удаление опубликованных',		),
					'personal.html'				=> array(	'title'	=> 'Собственные новости: Использование HTML кода',		),
					'personal.mainpage'			=> array(	'title'	=> 'Собственные новости: Размещение на главной',		),
					'personal.pinned'			=> array(	'title'	=> 'Собственные новости: Прикрепление на главной',		),
					'personal.catpinned'		=> array(	'title'	=> 'Собственные новости: Прикрепление в категории',		),
					'personal.favorite'			=> array(	'title'	=> 'Собственные новости: Добавление в закладки',		),
					'personal.setviews'			=> array(	'title'	=> 'Собственные новости: Установка кол-ва просмотров',	),
					'personal.multicat'			=> array(	'title'	=> 'Собственные новости: Размещение в нескольких категориях',		),
					'personal.nocat'			=> array(	'title'	=> 'Собственные новости: Размещение вне категории',		),
					'personal.customdate'		=> array(	'title'	=> 'Собственные новости: Изменение даты публикации',	),
					'personal.altname'				=> array(	'title'	=> 'Собственные новости: Задание альт. имени',		),
					'other.list'				=> array(	'title'	=> 'Чужие новости: Просмотр списка',					),
					'other.view'				=> array(	'title'	=> 'Чужие новости: Просмотр содержимого',				),
					'other.modify'				=> array(	'title'	=> 'Чужие новости: Редактирование',						),
					'other.modify.published'	=> array(	'title'	=> 'Чужие новости: Редактирование опубликованных',		),
					'other.publish'				=> array(	'title'	=> 'Чужие новости: Публикация',							),
					'other.unpublish'			=> array(	'title'	=> 'Чужие новости: Снятие с публикации',				),
					'other.delete'				=> array(	'title'	=> 'Чужие новости: Удаление',							),
					'other.delete.published'	=> array(	'title'	=> 'Чужие новости: Удаление опубликованных',			),
					'other.html'				=> array(	'title'	=> 'Чужие новости: Использование HTML кода',			),
					'other.mainpage'			=> array(	'title'	=> 'Чужие новости: Размещение на главной',				),
					'other.pinned'				=> array(	'title'	=> 'Чужие новости: Прикрепление на главной',			),
					'other.catpinned'			=> array(	'title'	=> 'Чужие новости: Прикрепление в категории',			),
					'other.favorite'			=> array(	'title'	=> 'Чужие новости: Добавление в закладки',				),
					'other.setviews'			=> array(	'title'	=> 'Чужие новости: Установка кол-ва просмотров',		),
					'other.multicat'			=> array(	'title'	=> 'Чужие новости: Размещение в нескольких категориях',	),
					'other.nocat'				=> array(	'title'	=> 'Чужие новости: Размещение вне категории',			),
					'other.customdate'			=> array(	'title'	=> 'Чужие новости: Изменение даты публикации',			),
					'other.altname'				=> array(	'title'	=> 'Чужие новости: Задание альт. имени',				),
					'*'							=> array(	'title'	=> '** Значение по умолчанию **',						),
				),
			),
			'dbo' => array(
				'title'	=> 'Управление базой данных',
				'items'	=> array(
					'details'					=> array(	'title'	=> 'Просмотр текущего состояния базы данных',			),
					'modify'					=> array(	'title'	=> 'Изменение в базе данных',							),
				),
			),
			'templates' => array(
				'title'	=> 'Управление шаблонами',
				'items'	=> array(
					'details'					=> array(	'title'	=> 'Просмотр шаблонов',									),
					'modify'					=> array(	'title'	=> 'Изменение шаблонов',								),
				),
			),

		),
	),
	'nsm'	=> array(
		'title'		=> 'Плагин NSM',
		'items'		=> array(
			''			=> array(
			'items'			=> array(
				'add'					=> array(	'title'	=> 'Добавление новостей',							),
				'list'					=> array(	'title'	=> 'Просмотр списка новостей',						),
				'view'					=> array(	'title'	=> 'Просмотр содержимого новости',					),
				'view.draft'			=> array(	'title'	=> 'Просмотр содержимого черновика',				),
				'view.unpublished'		=> array(	'title'	=> 'Просмотр содержимого модерируемой новости',		),
				'view.published'		=> array(	'title'	=> 'Просмотр содержимого опубликованной новости',	),
				'modify.draft'			=> array(	'title'	=> 'Изменение содержимого черновика',				),
				'modify.unpublished'	=> array(	'title'	=> 'Изменение содержимого модерируемой новости',	),
				'modify.published'		=> array(	'title'	=> 'Изменение содержимого опубликованной новости',	),
				'delete.draft'			=> array(	'title'	=> 'Удаление содержимого черновика',				),
				'delete.unpublished'	=> array(	'title'	=> 'Удаление содержимого модерируемой новости',		),
				'delete.published'		=> array(	'title'	=> 'Удаление содержимого опубликованной новости',	),

				),
			),
		),
	),

);
