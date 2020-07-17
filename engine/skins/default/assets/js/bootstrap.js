import * as helpers from '@/helpers/functions.js';

try {
    window.$ = window.jQuery = require('jquery');
    window.Popper = require('popper.js').default;
    require('bootstrap');
    require('jquery-datetimepicker');

    // Подгружаем скрипты, которых нет на `npmjs.com`.
    require('@/../vendor/uploadifive/jquery.uploadifive.js');

    // При необходимости регистрируем импортируемые функции.
    for (const helper in helpers) {
        window[helper] = helpers[helper];
    }
} catch (error) {
    console.error(error.message)
}

// Библиотека `jquery.uploadifive` имеет одно обращение
// к устаревшей jQuery-функции. Делаем заглушку для этого.
jQuery.fn.extend({
    size: function() {
        return this.length;
    }
});

$(document).ready(function() {
    // Если на странице нет токена, то просто проинформируем об этом.
    if (!$('input[name="token"]')) {
        console.info('CSRF token not found');
    }

    // Устанавливаем локаль для `datetimepicker`.
    $.datetimepicker.setLocale(NGCMS.langcode);
});
