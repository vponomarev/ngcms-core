//
// JS Functions used for admin panel
//


function getCookie(name) {
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

function setCookie (name, value, expires, path, domain, secure) {
      document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
      return true;
}

function toggleAdminGroup(ref) {
 // Decide to find parent node for this block
 var maxIter = 5;
 var node = ref;

 while(maxIter) {
  if (node.className == 'admGroup') { break; }
  node = node.parentNode;
  maxIter--;
 }
 if (!maxIter) { alert('Scripting Error'); }


 for (var i = 0; i < node.childNodes.length; i++) {
	var item = node.childNodes[i];
 	if (item.className == 'content') {
 		mode = (item.style.display == 'none')?1:0;
		item.style.display = mode?'':'none';
		break;
	}
 }
}

function ngShowLoading(msg) {

	var setX = ( $(window).width()  - $("#loading-layer").width()  ) / 2;
	var setY = ( $(window).height() - $("#loading-layer").height() ) / 2;

	$("#loading-layer").css( {
		left : setX + "px",
		top : setY + "px",
		position : 'fixed',
		zIndex : '99'
	});

	$("#loading-layer").fadeIn(0);
}

function ngHideLoading() {
	$("#loading-layer").fadeOut('slow');
}


function ngNotifyWindow(msg, title) {

	$("#ngNotifyWindowDIV").remove();
	$("body").append("<div id='ngNotifyWindowDIV' title='" + title + "' style='display:none'><br />" + msg + "</div>");

	$('#ngNotifyWindowDIV').dialog({
		autoOpen: true,
		width: 470,
		dialogClass: "modalfixed",
		buttons: {
			"Ok": function() {
				$(this).dialog("close");
				$("#ngNotifyWindowDIV").remove();
			}
		}
	});

	$('.modalfixed.ui-dialog').css({position:"fixed"});
	$('#ngNotifyWindowDIV').dialog( "option", "position", ['0','0'] );

}

function ngNotifySticker(msg,o){
	var o = $.extend({  					// ��������� �� ���������
		time:5000,							// ���������� ��, ������� ������������ ���������
		speed:'slow',						// �������� ���������
		className:'ngStickerClassClassic',	// �����, ����������� � ���������
		sticked:false,						// �� �������� ������ �������� ���������
		closeBTN:false,						// �������� ������ �������� ����
		position:{top:0,right:0}			// ������� �� ��������� - ������ ������
	}, o);

	var stickers = $('#ngSticker'); // �������� ������ � ������� ���������
	if (!stickers.length) { // ���� ��� ��� �� ����������
		$('body').prepend('<div id="ngSticker"></div>'); // ��������� ���
		var stickers = $('#ngSticker');
	}

	stickers.css('position','fixed').css({right:'auto',left:'auto',top:'auto',bottom:'auto'}).css(o.position); // �������������
	var stick = $('<div class="ngStickerClass"></div>'); // ������ ������
	stickers.append(stick); // ��������� ��� � ������������� ��������
	if (o.className) stick.addClass(o.className); // ���� ����������, ��������� �����
        stick.html(msg); // ��������� ���������

	// ����� ������ ��������
	if (o.sticked || o.closeBTN) {
		var exit = $('<div class="ngStickerClassExitBTN"></div>');  // ������ ������ ������
		stick.prepend(exit); // ��������� � ����� ����������
		exit.click(function(){  // ��� �����
			stick.fadeOut(o.speed,function(){ // �������� ������
				$(this).remove(); // �� ��������� �������� ������� ���
			})
		});
	}

	// �������������� �������� ���� - ���� ��������� �� ����������
	if (!o.sticked) {
		setTimeout(function(){ // ������������� ������ �� ����������� �����
			stick.fadeOut(o.speed,function(){ // ����� �������� ������
				$(this).remove(); // �� ��������� �������� ������� ���
			});
		}, o.time);
	}
}
$.datepicker.regional['ru'] = {
     closeText: '�������',
     prevText: '<����',
     nextText: '����>',
     currentText: '�������',
     monthNames: ['������','�������','����','������','���','����',
     '����','������','��������','�������','������','�������'],
     monthNamesShort: ['���','���','���','���','���','���',
     '���','���','���','���','���','���'],
     dayNames: ['�����������','�����������','�������','�����','�������','�������','�������'],
     dayNamesShort: ['���','���','���','���','���','���','���'],
     dayNamesMin: ['��','��','��','��','��','��','��'],
     weekHeader: '��',
     dateFormat: 'dd.mm.yy',
     firstDay: 1,
     isRTL: false,
     showMonthAfterYear: false,
     yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['ru']);

$.timepicker.regional['ru'] = {
     timeOnlyTitle: '�������� �����',
     timeText: '�����',
     hourText: '����',
     minuteText: '������',
     secondText: '�������',
     millisecText: '������������',
     timezoneText: '������� ����',
     currentText: '������',
     closeText: '�������',
     timeFormat: 'HH:mm',
     amNames: ['AM', 'A'],
     pmNames: ['PM', 'P'],
     isRTL: false
};
$.timepicker.setDefaults($.timepicker.regional['ru']);