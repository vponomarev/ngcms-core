//
// JS Functions used for admin panel
//


function getCookie(name)
{
    var cookie = " " + document.cookie;
    var search = " " + name + "=";
    var setStr = null;
    var offset = 0;
    var end = 0;
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
    return setStr;
}

function setCookie(name, value, expires, path, domain, secure)
{
    document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
    return true;
}

function toggleAdminGroup(ref)
{
    // Decide to find parent node for this block
    var maxIter = 5;
    var node = ref;

    while (maxIter) {
        if (node.className == 'admGroup') {
            break;
        }
        node = node.parentNode;
        maxIter--;
    }
    if (!maxIter) {
        alert('Scripting Error');
    }


    for (var i = 0; i < node.childNodes.length; i++) {
        var item = node.childNodes[i];
        if (item.className == 'content') {
            mode = (item.style.display == 'none') ? 1 : 0;
            item.style.display = mode ? '' : 'none';
            break;
        }
    }
}

function ngShowLoading(msg)
{

    var setX = ( $(window).width() - $("#loading-layer").width()  ) / 2;
    var setY = ( $(window).height() - $("#loading-layer").height() ) / 2;

    $("#loading-layer").css({
        left: setX + "px",
        top: setY + "px",
        position: 'fixed',
        zIndex: '99'
    });

    $("#loading-layer").fadeIn(0);
}

function ngHideLoading()
{
    $("#loading-layer").fadeOut('slow');
}


function ngNotifyWindow(msg, title)
{

    $("#ngNotifyWindowDIV").remove();
    $("body").append("<div id='ngNotifyWindowDIV' title='" + title + "' style='display:none'><br />" + msg + "</div>");

    $('#ngNotifyWindowDIV').dialog({
        autoOpen: true,
        width: 470,
        dialogClass: "modalfixed",
        buttons: {
            "Ok": function () {
                $(this).dialog("close");
                $("#ngNotifyWindowDIV").remove();
            }
        }
    });

    $('.modalfixed.ui-dialog').css({position: "fixed"});
    $('#ngNotifyWindowDIV').dialog("option", "position", ['0', '0']);

}

function ngNotifySticker(msg, o)
{
    var o = $.extend({                      // настройки по умолчанию
        time: 5000,                             // количество мс, которое отображается сообщение
        speed: 'slow',                      // скорость исчезания
        className: 'ngStickerClassClassic',     // класс, добавляемый к сообщению
        sticked: false,                         // не выводить кнопку закрытия сообщения
        closeBTN: false,                        // выводить кнопку закрытия окна
        position: {top: 0, right: 0}            // позиция по умолчанию - справа сверху
    }, o);

    var stickers = $('#ngSticker'); // начинаем работу с главным элементом
    if (!stickers.length) { // если его ещё не существует
        $('body').prepend('<div id="ngSticker"></div>'); // добавляем его
        var stickers = $('#ngSticker');
    }

    stickers.css('position', 'fixed').css({right: 'auto', left: 'auto', top: 'auto', bottom: 'auto'}).css(o.position); // позиционируем
    var stick = $('<div class="ngStickerClass"></div>'); // создаём стикер
    stickers.append(stick); // добавляем его к родительскому элементу
    if (o.className) {
        stick.addClass(o.className); // если необходимо, добавляем класс
    }    stick.html(msg); // вставляем сообщение

    // Вывод кнопки закрытия
    if (o.sticked || o.closeBTN) {
        var exit = $('<div class="ngStickerClassExitBTN"></div>');  // создаём кнопку выхода
        stick.prepend(exit); // вставляем её перед сообщением
        exit.click(function () {
  // при клике
            stick.fadeOut(o.speed, function () {
 // скрываем стикер
                $(this).remove(); // по окончании анимации удаляем его
            })
        });
    }

    // Автоматическое закрытие окна - если сообщение не закреплено
    if (!o.sticked) {
        setTimeout(function () {
 // устанавливаем таймер на необходимое время
            stick.fadeOut(o.speed, function () {
 // затем скрываем стикер
                $(this).remove(); // по окончании анимации удаляем его
            });
        }, o.time);
    }
}
$.datepicker.regional['ru'] = {
    closeText: 'Закрыть',
    prevText: '<Пред',
    nextText: 'След>',
    currentText: 'Сегодня',
    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
        'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
    monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн',
        'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
    dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
    dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
    dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    weekHeader: 'Не',
    dateFormat: 'dd.mm.yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
};

$.timepicker.regional['ru'] = {
    timeOnlyTitle: 'Выберите время',
    timeText: 'Время',
    hourText: 'Часы',
    minuteText: 'Минуты',
    secondText: 'Секунды',
    millisecText: 'Миллисекунды',
    timezoneText: 'Часовой пояс',
    currentText: 'Сейчас',
    closeText: 'Закрыть',
    timeFormat: 'HH:mm',
    amNames: ['AM', 'A'],
    pmNames: ['PM', 'P'],
    isRTL: false
};