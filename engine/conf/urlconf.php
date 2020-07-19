<?php

$urlLibrary = [
    'news' => [
        'main' => [
            'vars' => [
                'page' => [
                    'matchRegex' => '\\d{1,4}',
                    'descr'      => [
                        'russian' => 'Страница',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Главная новостная страница',
            ],
        ],
        'by.category' => [
            'vars' => [
                'category' => [
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'Альт. имя категории',
                    ],
                ],
                'catid' => [
                    'matchRegex' => '\\d{1,4}(?:\\-[0-9\\-]+)?',
                    'descr'      => [
                        'russian' => 'ID категории',
                    ],
                ],
                'page' => [
                    'matchRegex' => '\\d{1,4}',
                    'descr'      => [
                        'russian' => 'Страница',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Новости из заданной категории',
            ],
        ],
        'news' => [
            'vars' => [
                'category' => [
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'Альт. имя категории',
                    ],
                ],
                'catid' => [
                    'matchRegex' => '\\d{1,4}(?:\\-[0-9\\-]+)?',
                    'descr'      => [
                        'russian' => 'ID категории',
                    ],
                ],
                'year' => [
                    'matchRegex' => '\\d{4}',
                    'descr'      => [
                        'russian' => 'Год',
                    ],
                ],
                'month' => [
                    'matchRegex' => '\\d{2}',
                    'descr'      => [
                        'russian' => 'Месяц',
                    ],
                ],
                'day' => [
                    'matchRegex' => '\\d{2}',
                    'descr'      => [
                        'russian' => 'День',
                    ],
                ],
                'page' => [
                    'matchRegex' => '\\d{1,4}',
                    'descr'      => [
                        'russian' => 'Страница внутри новости',
                    ],
                ],
                'altname' => [
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'Альт. имя новости',
                    ],
                ],
                'id' => [
                    'matchRegex' => '\\d{1,7}',
                    'descr'      => [
                        'russian' => 'ID новости',
                    ],
                ],
                'zid' => [
                    'matchRegex' => '\\d{4,7}',
                    'descr'      => [
                        'russian' => 'ID новости с ведущими нулями (4 цифры)',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Отображение полной новости',
            ],
        ],
        'by.year' => [
            'vars' => [
                'year' => [
                    'matchRegex' => '\\d{4}',
                    'descr'      => [
                        'russian' => 'Год',
                    ],
                ],
                'page' => [
                    'matchRegex' => '\\d{1,4}',
                    'descr'      => [
                        'russian' => 'Страница',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Новости за год',
            ],
        ],
        'by.month' => [
            'vars' => [
                'year' => [
                    'matchRegex' => '\\d{4}',
                    'descr'      => [
                        'russian' => 'Год',
                    ],
                ],
                'month' => [
                    'matchRegex' => '\\d{2}',
                    'descr'      => [
                        'russian' => 'Месяц',
                    ],
                ],
                'page' => [
                    'matchRegex' => '\\d{1,4}',
                    'descr'      => [
                        'russian' => 'Страница',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Новости за месяц',
            ],
        ],
        'by.day' => [
            'vars' => [
                'year' => [
                    'matchRegex' => '\\d{4}',
                    'descr'      => [
                        'russian' => 'Год',
                    ],
                ],
                'month' => [
                    'matchRegex' => '\\d{2}',
                    'descr'      => [
                        'russian' => 'Месяц',
                    ],
                ],
                'day' => [
                    'matchRegex' => '\\d{2}',
                    'descr'      => [
                        'russian' => 'День',
                    ],
                ],
                'page' => [
                    'matchRegex' => '\\d{1,4}',
                    'descr'      => [
                        'russian' => 'Страница',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Новости за день',
            ],
        ],
        'print' => [
            'vars' => [
                'category' => [
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'Альт. имя категории',
                    ],
                ],
                'catid' => [
                    'matchRegex' => '\\d{1,4}(?:\\-[0-9\\-]+)?',
                    'descr'      => [
                        'russian' => 'ID категории',
                    ],
                ],
                'year' => [
                    'matchRegex' => '\\d{4}',
                    'descr'      => [
                        'russian' => 'Год',
                    ],
                ],
                'month' => [
                    'matchRegex' => '\\d{2}',
                    'descr'      => [
                        'russian' => 'Месяц',
                    ],
                ],
                'day' => [
                    'matchRegex' => '\\d{2}',
                    'descr'      => [
                        'russian' => 'День',
                    ],
                ],
                'page' => [
                    'matchRegex' => '\\d{1,4}',
                    'descr'      => [
                        'russian' => 'Страница внутри новости',
                    ],
                ],
                'altname' => [
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'Альт. имя новости',
                    ],
                ],
                'id' => [
                    'matchRegex' => '\\d{1,4}',
                    'descr'      => [
                        'russian' => 'ID новости',
                    ],
                ],
                'zid' => [
                    'matchRegex' => '\\d{4,7}',
                    'descr'      => [
                        'russian' => 'ID новости с ведущими нулями (4 цифры)',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Страница для печати полной новости',
            ],
        ],
        'all' => [
            'vars' => [
                'page' => [
                    'matchRegex' => '\\d{1,4}',
                    'descr'      => [
                        'russian' => 'Страница',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Лента новостей',
            ],
        ],
    ],
    'rss_export' => [
        '' => [
            'vars' => [
            ],
            'descr' => [
                'russian' => 'Основной RSS поток',
            ],
        ],
        'category' => [
            'vars' => [
                'category' => [
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'Альт. имя категории',
                    ],
                ],
                'catid' => [
                    'matchRegex' => '\\d{1,4}(?:\\-[0-9\\-]+)?',
                    'descr'      => [
                        'russian' => 'ID категории',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'RSS поток указанной категории',
            ],
        ],
        'main' => [
            'vars' => [
            ],
            'descr' => [
                'russian' => 'Основной RSS поток',
            ],
        ],
    ],
    'core' => [
        'plugin' => [
            'vars' => [
                'plugin' => [
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'ID плагина',
                    ],
                ],
                'handler' => [
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'Передаваемая команда',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Страница плагина',
            ],
        ],
        'registration' => [
            'vars' => [
            ],
            'descr' => [
                'russian' => 'Регистрация нового пользователя',
            ],
        ],
        'activation' => [
            'vars' => [
                'userid' => [
                    'matchRegex' => '\\d+',
                    'descr'      => [
                        'russian' => 'ID пользователя',
                    ],
                ],
                'code' => [
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'Код активации',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Активация нового пользователя',
            ],
        ],
        'lostpassword' => [
            'vars' => [
                'userid' => [
                    'matchRegex' => '\\d+',
                    'descr'      => [
                        'russian' => 'ID пользователя',
                    ],
                ],
                'code' => [
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'Код активации',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Восстановление потерянного пароля',
            ],
        ],
        'login' => [
            'vars' => [
            ],
            'descr' => [
                'russian' => 'Вход на сайт (авторизация)',
            ],
        ],
        'logout' => [
            'vars' => [
            ],
            'descr' => [
                'russian' => 'Выход с сайта',
            ],
        ],
    ],
    'uprofile' => [
        'edit' => [
            'vars' => [
            ],
            'descr' => [
                'russian' => 'Редактирование собственного профиля',
            ],
        ],
        'show' => [
            'vars' => [
                'name' => [
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'Логин пользователя',
                    ],
                ],
                'id' => [
                    'matchRegex' => '\\d+',
                    'descr'      => [
                        'russian' => 'ID пользователя',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Показать профиль конкретного пользователя',
            ],
        ],
    ],
    'static' => [
        '' => [
            'vars' => [
                'altname' => [
                    'isSecure'   => 1,
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'Альт. имя статической страницы',
                    ],
                ],
                'id' => [
                    'matchRegex' => '\\d{1,4}',
                    'descr'      => [
                        'russian' => 'ID статической страницы',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Отображение статической страницы',
            ],
        ],
        'print' => [
            'vars' => [
                'altname' => [
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'Альт. имя статической страницы',
                    ],
                ],
                'id' => [
                    'matchRegex' => '\\d{1,4}',
                    'descr'      => [
                        'russian' => 'ID статической страницы',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Печать статической страницы',
            ],
        ],
    ],
    'search' => [
        '' => [
            'vars' => [
            ],
            'descr' => [
                'russian' => 'Страница поиска',
            ],
        ],
    ],
    'a_test' => [
        '' => [
            'vars' => [
                'action' => [
                    'matchRegex' => '.+?',
                    'descr'      => [
                        'russian' => 'Super action',
                    ],
                ],
            ],
            'descr' => [
                'russian' => 'Super description',
            ],
        ],
    ],
];
