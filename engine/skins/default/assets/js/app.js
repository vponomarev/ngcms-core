'use strict';

// Загружаем все  JavaScript-зависимости проекта.
require('@/bootstrap.js');

// Константы раскрывающихся панелей.
const COLLAPSE_STATE_NAME = 'collapseState';
const COLLAPSE_STATE = JSON.parse(localStorage.getItem(COLLAPSE_STATE_NAME)) || [];
const COLLAPSE_STATE_EXCLUDING = [
    '#sidebarMenu',
    '#collapseEditPreview',
    '#menu-content',
    '#content',
    "#users",
    "#service",
];

$(document).ready(function() {
    // Устанавливаем формат даты для страниц создания/редактирования новостей.
    $("#cdate").datetimepicker({
        format: 'd.m.Y H:i'
    });

    // Запоминаем отображение раскрывающихся панелей.
    $('[data-toggle="collapse"]').each(function(index, element) {
        const target = $(element).attr('data-target');

        if (target && !COLLAPSE_STATE_EXCLUDING.includes(target)) {
            $(target).collapse({
                toggle: COLLAPSE_STATE.includes(target)
            });

            $(target).on('shown.bs.collapse', function(event) {
                if (!COLLAPSE_STATE.includes(target)) {
                    COLLAPSE_STATE.push(target);

                    localStorage.setItem(COLLAPSE_STATE_NAME, JSON.stringify(COLLAPSE_STATE));
                }
            });

            $(target).on('hidden.bs.collapse', function(event) {
                const findedIndex = COLLAPSE_STATE.findIndex((item) => item === target);

                COLLAPSE_STATE.splice(findedIndex, 1);

                localStorage.setItem(COLLAPSE_STATE_NAME, JSON.stringify(COLLAPSE_STATE));
            });
        }
    });

    // Запоминаем отображение раскрывающихся панелей.
    $('[data-toggle="admin-group"]').on('click', function(event) {
        event.preventDefault();

        const content = $(this).parent().next();

        if (content.length && $(content).hasClass('admin-group-content')) {
            $(content).toggle();
        }
    });
});
