/*
|--------------------------------------------------------------------------
| JavaScript-функции, используемые в админ. панели.
|--------------------------------------------------------------------------
|
| Содержит функционал, перенесенный из `{{ home }}/lib/admin.js`,  `{{ home }}/lib/functions.js`.
|
*/

/**
 * [json_encode description]
 * @param  {object} params
 * @return {string}
 */
export function json_encode(params) {
    return JSON.stringify(params);
}

/**
 * [ngShowLoading description]
 */
export function ngShowLoading() {
    const setX = ($(window).width() - $("#loading-layer").width()) / 2;
    const setY = ($(window).height() - $("#loading-layer").height()) / 2;

    $("#loading-layer").css({
            left: setX + 'px',
            top: setY + 'px'
        })
        .fadeIn(0);
}

/**
 * [ngHideLoading description]
 */
export function ngHideLoading() {
    $("#loading-layer").fadeOut('slow');
}

/**
 * [ngNotifySticker description]
 * @param  {string}  message
 * @param  {object}  params
 */
export function ngNotifySticker(message, params = {}) {
    // настройки по умолчанию
    params = $.extend({
        time: 5000, // количество мс, которое отображается сообщение
        speed: 'slow', // скорость исчезания
        className: 'alert-info', // класс, добавляемый к сообщению
        sticked: false, // не закреплять сообщение
        closeBTN: false, // выводить кнопку закрытия окна
        // позиция по умолчанию - справа сверху
        position: {
            top: 0,
            right: 0
        }
    }, params);

    // начинаем работу с главным элементом
    // если его ещё не существует
    if (!$('#ng-stickers').length) {
        // добавляем его
        $('body').prepend('<div id="ng-stickers"></div>');
    }

    const stickers = $('#ng-stickers');

    // позиционируем
    stickers.css('position', 'fixed')
        .css({
            right: 'auto',
            left: 'auto',
            top: 'auto',
            bottom: 'auto'
        })
        .css(params.position);

    // создаём стикер
    const stick = $('<div class="alert alert-dismissible fade"></div>')
        .html(message);

    // добавляем его к родительскому элементу
    stickers.append(stick);

    // если необходимо, добавляем класс
    if (params.className) {
        stick.addClass(params.className);
    }

    // Вывод кнопки закрытия
    if (params.sticked || params.closeBTN) {
        // создаём кнопку выхода
        const exit = $('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

        // вставляем её перед сообщением
        stick.prepend(exit);
    }

    // Автоматическое закрытие окна, если сообщение не закреплено.
    if (!params.sticked) {
        // Устанавливаем таймер на необходимое время.
        setTimeout(function() {
            stick.alert('close');
        }, params.time);
    }

    stick.addClass('show');
}

/**
 * [confirmit description]
 * @param  {string} url
 * @param  {string} text
 */
export function confirmit(url, text) {
    if (confirm(text)) {
        document.location = url;
    }
}

/**
 * [setCookie description]
 * @param {string} name
 * @param {string} value
 */
export function setCookie(name, value) {
    document.cookie = name + "=" + value + '; path="/";' + " expires=Thu, 1 Jan 2030 00:00:00 GMT;";
}

/**
 * [deleteCookie description]
 * @param  {string} name
 */
export function deleteCookie(name) {
    document.cookie = name + "=" + "; path=/;" + " expires=Sut, 1 Jan 2000 00:00:01 GMT;";
}

/**
 * [getCookie description]
 * @param  {string} name
 * @return {string}
 */
export function getCookie(name) {
    const cookie = " " + document.cookie;
    const search = " " + name + "=";
    let setStr = null;
    let offset = 0;
    let end = 0;

    if (cookie.length > 0) {
        offset = cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = cookie.indexOf(";", offset)
            if (end == -1) {
                end = cookie.length;
            }
            setStr = unescape(cookie.substring(offset, end));
        }
    }

    return (setStr);
}

/**
 * [check_uncheck_all description]
 * @param  {HTMLFormElement} form
 * @param  {string} prefix
 */
export function check_uncheck_all(form, prefix = '') {
    [...form.elements].map(function(element) {
        if ((element.type == "checkbox") && (element.name != "master_box") &&
            ((prefix.length == 0) || (element.name.substr(0, prefix.length) == prefix))
        ) {
            element.checked = form.master_box.checked ? true : false;
        }
    });
}

/**
 * [insertext description]
 * @param  {string} open
 * @param  {string} close
 * @param  {string} field
 */
export function insertext(open, close, field) {
    window.event.preventDefault();

    const msgfield = document.getElementById(field || 'content');

    // IE support
    if (document.selection && document.selection.createRange) {
        msgfield.focus();
        sel = document.selection.createRange();
        sel.text = open + sel.text + close;
    }
    // Moz support
    else if (msgfield.selectionStart || msgfield.selectionStart == "0") {
        const startPos = msgfield.selectionStart;
        const endPos = msgfield.selectionEnd;
        const scrollPos = msgfield.scrollTop;

        msgfield.value = msgfield.value.substring(0, startPos) + open + msgfield.value.substring(startPos, endPos) + close + msgfield.value.substring(endPos, msgfield.value.length);
        msgfield.selectionStart = msgfield.selectionEnd = endPos + open.length + close.length;
        msgfield.scrollTop = scrollPos;
    }
    // Fallback support for other browsers
    else {
        msgfield.value += open + close;
    }

    msgfield.focus();
}

/**
 * [insertimage description]
 * @param  {string} text
 * @param  {string} area
 */
export function insertimage(text, area) {
    var win = window.opener;
    var form = win.document.forms['form'];

    try {
        var xarea = win.document.forms['DATA_tmp_storage'].area.value;
        if (xarea != '') {
            area = xarea;
        }
    } catch (err) {
        ;
    }

    var control = win.document.getElementById(area);

    control.focus();

    // IE
    if (win.selection && win.selection.createRange) {
        sel = win.selection.createRange();
        sel.text = text = sel.text;
    }
    // Mozilla
    else {
        if (control.selectionStart || control.selectionStart == "0") {
            var startPos = control.selectionStart;
            var endPos = control.selectionEnd;

            control.value = control.value.substring(0, startPos) + text + control.value.substring(startPos, control.value.length);
            //control.selectionStart = msgfield.selectionEnd = endPos + open.length + close.length;
        } else {
            control.value += text;
        }
    }

    control.focus();
}

/**
 * [ResponseException description]
 * @param  {string}  message
 * @param  {object}  response
 */
function ResponseException(message, response) {
    this.message = message;
    this.response = response;
    this.name = 'AJAX Response Exception';
    this.toString = function() {
        return `${this.name}: ${this.message}`
    };
}

/**
 * Выполнить асинхронный POST-запрос (AJAX) на сервер.
 * @param  {string}  methodName  Имя метода.
 * @param  {object}  params  Параметры запроса.
 * @param  {boolean} notifyResult  Отобразить результат запроса во всплывающем сообщении.
 * @return {jqXHR}
 */
export function post(methodName, params = {}, notifyResult = true) {
    const token = $('input[name="token"]').val();

    return $.ajax({
            method: 'POST',
            url: NGCMS.admin_url + '/rpc.php',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
            },
            data: {
                json: 1,
                token: token,
                methodName: methodName,
                params: JSON.stringify(params)
            },
            beforeSend() {
                ngShowLoading();
            }
        })
        .then(function(response) {
            if (!response.status) {
                throw new ResponseException(`Error [${response.errorCode}]: ${response.errorText}`, response);
            }

            return response;
        })
        .done(function(response) {
            // title: `<h5>${NGCMS.lang.notifyWindowInfo}</h5>`,
            notifyResult && ngNotifySticker(response.errorText, {
                className: 'alert-success',
                closeBTN: true
            });

            return response;
        })
        .fail(function(error) {
            // title: `<h5>${NGCMS.lang.notifyWindowError}</h5>`,
            ngNotifySticker(error.message || NGCMS.lang.rpc_httpError, {
                className: 'alert-danger',
                closeBTN: true
            });
        })
        .always(function() {
            ngHideLoading();
        });
}
