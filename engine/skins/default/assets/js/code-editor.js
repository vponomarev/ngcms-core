'use strict';

import CodeMirror from 'codemirror';
import emmet from '@emmetio/codemirror-plugin';

import 'codemirror/mode/htmlmixed/htmlmixed.js';
import 'codemirror/mode/css/css.js';
import 'codemirror/mode/javascript/javascript.js';

import 'codemirror/addon/display/fullscreen.js';
import 'codemirror/addon/dialog/dialog.js';
import 'codemirror/addon/search/search.js';
import 'codemirror/addon/search/searchcursor.js';
import 'codemirror/addon/selection/active-line.js';

require('../vendor/ngFileTree/ngFileTree.js');
emmet(CodeMirror);

var ngTemplateName = 'default';
var ngFileName = '';
var ngFileType = '';
var ngFileContent = '';

$(document).ready(function() {
    const ngFileTreeParams = {
        root: '/',
        script: `${NGCMS.admin_url}/rpc.php`,
        securityToken: $('input[name="token"]').val(),
        templateName: ngTemplateName
    };

    // Обработчик выбора шаблона.
    $('[data-teplate-mode]').on('click', function(e) {
        e.preventDefault();

        $('#fileEditorInfo').html('');
        $('#imageViewContainer').html('');
        $('#fileEditorSelector').val('');

        ngFileName = '';

        if ('template' == $(this).attr('data-teplate-mode')) {
            ngTemplateName = $(this).attr('data-teplate-name');
            ngFileTreeParams.templateName = ngTemplateName;

            $('#fileTreeSelector').ngFileTree(ngFileTreeParams, ngFileTreeFunc);
            $('#templateNameArea').text(ngTemplateName);
        } else {
            ngTemplateName = '#plugins';
            ngFileTreeParams.templateName = ngTemplateName;

            $('#fileTreeSelector').ngFileTree(ngFileTreeParams, ngFileTreeFunc);
            $('#templateNameArea').text(' PLUGIN TEMPLATES ');
        }
    });

    // Обработчик сохранения шаблона
    $('#submitTemplateEdit').on('click', function(e) {
        e.preventDefault();

        post('admin.templates.updateFile', {
                token: $('input[name="token"]').val(),
                template: ngTemplateName,
                file: ngFileName,
                content: $('#fileEditorSelector').val()
            }, false)
            .then(function(response) {
                response.content && ngNotifySticker(response.content, {
                    closeBTN: true
                });
            });
    });

    // Инициализация дерева директорий и файлов.
    $('#fileTreeSelector').ngFileTree(ngFileTreeParams, ngFileTreeFunc);
});

function ngFileTreeFunc(file) {
    ngFileName = file;

    post('admin.templates.getFile', {
            token: $('input[name="token"]').val(),
            template: ngTemplateName,
            file: file
        }, false)
        .then(function(response) {
            // Удаляем предыдущий контейнер редактора.
            $('.CodeMirror').remove();

            ngFileContent = '';
            ngFileType = response.type;

            $('#fileEditorInfo').html(
                (('image' == ngFileType) ? 'Image' : 'File') + ' name: <b>' + ngFileName + '</b> (' + response.size + ' bytes)<br/>Last change time: ' + response.lastChange
            );

            if ('image' == response.type) {
                $('#imageViewContainer').show();
                $('#fileEditorSelector').hide();
                $('#fileEditorButtonLine').hide();
                $('#imageViewContainer').html(response.content);
            } else {
                $('#imageViewContainer').hide();
                $('#fileEditorSelector').show();
                $('#fileEditorButtonLine').show();
                $('#fileEditorSelector').val(response.content);

                // Install codemirror
                const eW = $('#fileEditorSelector').width();
                const eH = $('#fileEditorSelector').height();
                const cm = CodeMirror.fromTextArea(document.getElementById('fileEditorSelector'), {
                    lineNumbers: true,
                    lineWrapping: true,
                    styleActiveLine: true,
                    indentUnit: 4,
                    tabMode: 'shift',
                    enterMode: 'indent',
                    theme: 'default',
                    extraKeys: {
                        'Tab': 'indentMore',
                        'Shift-Tab': 'indentLess',
                        'Ctrl-E': 'emmetExpandAbbreviation',
                        'F11': function(cm) {
                            cm.setOption('fullScreen', !cm.getOption('fullScreen'));
                        },
                        'Esc': function(cm) {
                            if (cm.getOption('fullScreen')) {
                                cm.setOption('fullScreen', false);
                            }
                        }
                    }

                });

                cm.setSize(eW, eH);

                cm.on('change', function(cm) {
                    $("#fileEditorSelector").val(cm.getValue());
                });

                ngFileContent = response.content;
            }
        });
}
