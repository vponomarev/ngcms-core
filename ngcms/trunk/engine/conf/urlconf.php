<?php
$urlLibrary = array (
  'news' => 
  array (
    'main' => 
    array (
      'vars' => 
      array (
        'page' => 
        array (
          'matchRegex' => '\\d{1,4}',
          'descr' => 
          array (
            'russian' => 'Страница',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Главная новостная страница',
      ),
    ),
    'by.category' => 
    array (
      'vars' => 
      array (
        'category' => 
        array (
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'Альт. имя категории',
          ),
        ),
        'catid' => 
        array (
          'matchRegex' => '\\d{1,4}(?:\\-[0-9\\-]+)?',
          'descr' => 
          array (
            'russian' => 'ID категории',
          ),
        ),
        'page' => 
        array (
          'matchRegex' => '\\d{1,4}',
          'descr' => 
          array (
            'russian' => 'Страница',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Новости из заданной категории',
      ),
    ),
    'news' => 
    array (
      'vars' => 
      array (
        'category' => 
        array (
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'Альт. имя категории',
          ),
        ),
        'catid' => 
        array (
          'matchRegex' => '\\d{1,4}(?:\\-[0-9\\-]+)?',
          'descr' => 
          array (
            'russian' => 'ID категории',
          ),
        ),
        'year' => 
        array (
          'matchRegex' => '\\d{4}',
          'descr' => 
          array (
            'russian' => 'Год',
          ),
        ),
        'month' => 
        array (
          'matchRegex' => '\\d{2}',
          'descr' => 
          array (
            'russian' => 'Месяц',
          ),
        ),
        'day' => 
        array (
          'matchRegex' => '\\d{2}',
          'descr' => 
          array (
            'russian' => 'День',
          ),
        ),
        'page' => 
        array (
          'matchRegex' => '\\d{1,4}',
          'descr' => 
          array (
            'russian' => 'Страница внутри новости',
          ),
        ),
        'altname' => 
        array (
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'Альт. имя новости',
          ),
        ),
        'id' => 
        array (
          'matchRegex' => '\\d{1,7}',
          'descr' => 
          array (
            'russian' => 'ID новости',
          ),
        ),
        'zid' => 
        array (
          'matchRegex' => '\\d{4,7}',
          'descr' => 
          array (
            'russian' => 'ID новости с ведущими нулями (4 цифры)',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Отображение полной новости',
      ),
    ),
    'by.year' => 
    array (
      'vars' => 
      array (
        'year' => 
        array (
          'matchRegex' => '\\d{4}',
          'descr' => 
          array (
            'russian' => 'Год',
          ),
        ),
        'page' => 
        array (
          'matchRegex' => '\\d{1,4}',
          'descr' => 
          array (
            'russian' => 'Страница',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Новости за год',
      ),
    ),
    'by.month' => 
    array (
      'vars' => 
      array (
        'year' => 
        array (
          'matchRegex' => '\\d{4}',
          'descr' => 
          array (
            'russian' => 'Год',
          ),
        ),
        'month' => 
        array (
          'matchRegex' => '\\d{2}',
          'descr' => 
          array (
            'russian' => 'Месяц',
          ),
        ),
        'page' => 
        array (
          'matchRegex' => '\\d{1,4}',
          'descr' => 
          array (
            'russian' => 'Страница',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Новости за месяц',
      ),
    ),
    'by.day' => 
    array (
      'vars' => 
      array (
        'year' => 
        array (
          'matchRegex' => '\\d{4}',
          'descr' => 
          array (
            'russian' => 'Год',
          ),
        ),
        'month' => 
        array (
          'matchRegex' => '\\d{2}',
          'descr' => 
          array (
            'russian' => 'Месяц',
          ),
        ),
        'day' => 
        array (
          'matchRegex' => '\\d{2}',
          'descr' => 
          array (
            'russian' => 'День',
          ),
        ),
        'page' => 
        array (
          'matchRegex' => '\\d{1,4}',
          'descr' => 
          array (
            'russian' => 'Страница',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Новости за день',
      ),
    ),
    'print' => 
    array (
      'vars' => 
      array (
        'category' => 
        array (
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'Альт. имя категории',
          ),
        ),
        'catid' => 
        array (
          'matchRegex' => '\\d{1,4}(?:\\-[0-9\\-]+)?',
          'descr' => 
          array (
            'russian' => 'ID категории',
          ),
        ),
        'year' => 
        array (
          'matchRegex' => '\\d{4}',
          'descr' => 
          array (
            'russian' => 'Год',
          ),
        ),
        'month' => 
        array (
          'matchRegex' => '\\d{2}',
          'descr' => 
          array (
            'russian' => 'Месяц',
          ),
        ),
        'day' => 
        array (
          'matchRegex' => '\\d{2}',
          'descr' => 
          array (
            'russian' => 'День',
          ),
        ),
        'page' => 
        array (
          'matchRegex' => '\\d{1,4}',
          'descr' => 
          array (
            'russian' => 'Страница внутри новости',
          ),
        ),
        'altname' => 
        array (
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'Альт. имя новости',
          ),
        ),
        'id' => 
        array (
          'matchRegex' => '\\d{1,4}',
          'descr' => 
          array (
            'russian' => 'ID новости',
          ),
        ),
        'zid' => 
        array (
          'matchRegex' => '\\d{4,7}',
          'descr' => 
          array (
            'russian' => 'ID новости с ведущими нулями (4 цифры)',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Страница для печати полной новости',
      ),
    ),
    'all' => 
    array (
      'vars' => 
      array (
        'page' => 
        array (
          'matchRegex' => '\\d{1,4}',
          'descr' => 
          array (
            'russian' => 'Страница',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Лента новостей',
      ),
    ),
  ),
  'rss_export' => 
  array (
    '' => 
    array (
      'vars' => 
      array (
      ),
      'descr' => 
      array (
        'russian' => 'Основной RSS поток',
      ),
    ),
    'category' => 
    array (
      'vars' => 
      array (
        'category' => 
        array (
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'Альт. имя категории',
          ),
        ),
        'catid' => 
        array (
          'matchRegex' => '\\d{1,4}(?:\\-[0-9\\-]+)?',
          'descr' => 
          array (
            'russian' => 'ID категории',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'RSS поток указанной категории',
      ),
    ),
    'main' => 
    array (
      'vars' => 
      array (
      ),
      'descr' => 
      array (
        'russian' => 'Основной RSS поток',
      ),
    ),
  ),
  'core' => 
  array (
    'plugin' => 
    array (
      'vars' => 
      array (
        'plugin' => 
        array (
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'ID плагина',
          ),
        ),
        'handler' => 
        array (
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'Передаваемая команда',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Страница плагина',
      ),
    ),
    'registration' => 
    array (
      'vars' => 
      array (
      ),
      'descr' => 
      array (
        'russian' => 'Регистрация нового пользователя',
      ),
    ),
    'activation' => 
    array (
      'vars' => 
      array (
        'userid' => 
        array (
          'matchRegex' => '\\d+',
          'descr' => 
          array (
            'russian' => 'ID пользователя',
          ),
        ),
        'code' => 
        array (
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'Код активации',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Активация нового пользователя',
      ),
    ),
    'lostpassword' => 
    array (
      'vars' => 
      array (
        'userid' => 
        array (
          'matchRegex' => '\\d+',
          'descr' => 
          array (
            'russian' => 'ID пользователя',
          ),
        ),
        'code' => 
        array (
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'Код активации',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Восстановление потерянного пароля',
      ),
    ),
    'login' => 
    array (
      'vars' => 
      array (
      ),
      'descr' => 
      array (
        'russian' => 'Вход на сайт (авторизация)',
      ),
    ),
    'logout' => 
    array (
      'vars' => 
      array (
      ),
      'descr' => 
      array (
        'russian' => 'Выход с сайта',
      ),
    ),
  ),
  'uprofile' => 
  array (
    'edit' => 
    array (
      'vars' => 
      array (
      ),
      'descr' => 
      array (
        'russian' => 'Редактирование собственного профиля',
      ),
    ),
    'show' => 
    array (
      'vars' => 
      array (
        'name' => 
        array (
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'Логин пользователя',
          ),
        ),
        'id' => 
        array (
          'matchRegex' => '\\d+',
          'descr' => 
          array (
            'russian' => 'ID пользователя',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Показать профиль конкретного пользователя',
      ),
    ),
  ),
  'static' => 
  array (
    '' => 
    array (
      'vars' => 
      array (
        'altname' => 
        array (
          'isSecure' => 1,
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'Альт. имя статической страницы',
          ),
        ),
        'id' => 
        array (
          'matchRegex' => '\\d{1,4}',
          'descr' => 
          array (
            'russian' => 'ID статической страницы',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Отображение статической страницы',
      ),
    ),
    'print' => 
    array (
      'vars' => 
      array (
        'altname' => 
        array (
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'Альт. имя статической страницы',
          ),
        ),
        'id' => 
        array (
          'matchRegex' => '\\d{1,4}',
          'descr' => 
          array (
            'russian' => 'ID статической страницы',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Печать статической страницы',
      ),
    ),
  ),
  'search' => 
  array (
    '' => 
    array (
      'vars' => 
      array (
      ),
      'descr' => 
      array (
        'russian' => 'Страница поиска',
      ),
    ),
  ),
  'a_test' => 
  array (
    '' => 
    array (
      'vars' => 
      array (
        'action' => 
        array (
          'matchRegex' => '.+?',
          'descr' => 
          array (
            'russian' => 'Super action',
          ),
        ),
      ),
      'descr' => 
      array (
        'russian' => 'Super description',
      ),
    ),
  ),
);