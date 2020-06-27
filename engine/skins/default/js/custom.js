var $ = jQuery.noConflict();

$(document).ready(function(){
	
	/* 
	 * Убиваем старые notify
	*/
	
	$(window).load(function(){
		$('.sysinfo_common').each(function () {
			$.notify({message: $(this).html()},{type: 'info'});
			$(this).remove();
		});
		$('.sysmsg_common').each(function () {
			$.notify({message: $(this).html()},{type: 'success'});
			$(this).remove();
		});
		$('.syserror_common').each(function () {
			$.notify({message: $(this).html()},{type: 'danger'});
			$(this).remove();
		});
		
		$('.ngStickerClassClassic').each(function () {
			$.notify({message: $(this).html()},{type: 'success'});
			$(this).remove();
		});
		$('.ngStickerClassError').each(function () {
			$.notify({message: $(this).html()},{type: 'danger'});
			$(this).remove();
		});
	});
	
	// Добавляем стилизацию в соотвестствии
	// с bootstrap для недоступных элементов
	$('[type=submit]:not(.btn)').addClass('btn btn-success');
	$('input:not([type=hidden]):not([type=submit]):not([type=button]):not([type=reset]):not([type=file]):not([type=checkbox]):not([type=radio]):not(.form-control):not(.button), select:not(.form-control), textarea:not(.form-control)').addClass('form-control').removeAttr('style');
	$('.button:not([type=submit])').addClass('btn btn-default').removeClass('button');
	$('.btnActive').addClass('btn btn-sm btn-primary').removeClass('btnActive');
	$('.btnInactive').addClass('btn btn-sm btn-default').removeClass('btnInactive');
	$('.navbutton').addClass('btn btn-sm btn-primary').removeClass('navbutton');
	$('[type=button]:not(.btn)').addClass('btn btn-default');
	$('[type=cancel]:not(.btn)').addClass('btn btn-default');
	$('table:not(.table)').addClass('table table-condensed table-old');
	$('.contentHead, .contHead').addClass('well');
	$('td').removeAttr('valign');
	
	// Страшная функция
	/*$('.contentHead').replaceWith(function(index, oldHTML){
		return $('<th class="well"' + ($(this).attr('colspan') ? ' colspan="' + $(this).attr('colspan') : '') + ($(this).attr('id') ? ' id="' + $(this).attr('id') : '') + '">').html(oldHTML);
	});
	
	// Еще страшнее
	$('th').parents('tbody').replaceWith(function(index, oldHTML){
		return $('<thead' + ($(this).attr('id') ? ' id="' + $(this).attr('id') : '') + '">').html(oldHTML);
	});*/
	
	// Прокрутка вверх
	$(window).scroll(function () {
		if ($(this).scrollTop() != 0) {
			$('#scrollup').fadeIn();
		} else {
			$('#scrollup').fadeOut(); 
		}
	});
	
	$('#scrollup').click(function(){
		$('html, body').animate({ scrollTop: 0 }, 888);
		return;
	});
	
	// Боковое меню
	$('.sidebar-toggle, #sidenav-overlay').click(function () {
		$("html, body").animate({ scrollTop: 0 }, 0);
		$('.side-menu-container ').toggleClass('slide-in');
		$('.side-body').toggleClass('body-slide-in');
		if($('.side-body').hasClass('body-slide-in')) {
			$('#sidenav-overlay').fadeIn('slow');
		} else {
			$('#sidenav-overlay').fadeOut('slow');
		}
	});
	
	/* Select/unselect all */
	$('table .select-all').click(function() {
		$(this).parents('table').find('input:checkbox:not([disabled])').prop('checked', $(this).prop('checked'));
	});
	
	/* CONFIRMIT */
	function confirmit(url, text){
		var agree = confirm(text);
		if (agree) document.location=url;
	}
	
	/* admGroup hide/show */
	$('.adm-group-toggle').click(function() {
		$(this).parents('fieldset').find('.adm-group-content').toggle();
		return false;
	});
	
	$('code').click(function() {
		var e = this;
		if(window.getSelection) {
			var s = window.getSelection();
			if(s.setBaseAndExtent) {
				s.setBaseAndExtent(e,0,e,e.innerText.length-1);
			} else {
				var r = document.createRange();
				r.selectNodeContents(e);
				s.removeAllRanges();
				s.addRange(r);
			}
		} else if(document.getSelection) {
			var s = document.getSelection();
			var r = document.createRange();
			r.selectNodeContents(e);
			s.removeAllRanges();
			s.addRange(r);
		} else if(document.selection) {
			var r = document.body.createTextRange();
			r.moveToElementText(e);
			r.select();
		}
	});

	/* Устранение дергания экрана при вызове modal */
	$('.modal').on("show.bs.modal", function(){
		var $bodyWidth = $("body").width();
		$('body').css({'overflow-y': 'hidden'}).css({'padding-right': ($("body").width()-$bodyWidth)});
	});

	$('.modal').on("hidden.bs.modal", function(){
		$('body').css({'padding-right': "0", 'overflow-y': "auto"});
	});
	
	// Добавление/редактирование элементов (пользователь, группы) в modal
	$('.add_form').on('click', function(){
		$('#modal-dialog .modal-dialog').load($(this).attr('href') + ' #add_edit_form .modal-content');
		$('#modal-dialog').modal('show');
		return false;
	});
	$('.edit_form').on('click', function(){
		$('#modal-dialog .modal-dialog').load($(this).attr('href') + ' #add_edit_form .modal-content');
		$('#modal-dialog').modal('show');
		return false;
	});
	
	
	/*****************************
	 * ACTION
	******************************/
	
	/*
	 * Images
	*/
	var $lightbox = $('#lightbox');

	$('.thumbnail').on('click', function(event) {
		var $img = $(this).find('img'),
			src = $img.attr('src'),
			alt = $img.attr('alt'),
			css = {
				'maxWidth': $(window).width() - 100,
				'maxHeight': $(window).height() - 100
			};

		$lightbox.find('img').attr('src', src);
		$lightbox.find('img').attr('alt', alt);
		$lightbox.find('img').css(css);

	});

	$lightbox.on('shown.bs.modal', function (event) {
		var $img = $lightbox.find('img');
		$lightbox.find('.modal-dialog').css({'width': $img.width() + 30});
	});
	
	/* Smiles remove &nbsp; */
	$('.smiles').children().each(function(i,e){
		this.parentNode.removeChild(this.nextSibling);
	});
	
	/* switchtheme */
	$('#themes').on('click', function(e) {
		switchtheme( $("#theme-style option:selected").val());
	});
});

function switchtheme(theme) {
	createCookie('theme-style', theme, 365);
	location.reload();
}

/* **************** Extras ****************** */
function searchInTable() {
	
	// Reset nav-tabs
	tabsSwitch($('.nav-tabs li').eq(0));

	// Declare variables 
	var input, filter, table, tr, td, i;
	input = $('#searchInput');
	filter = input.val().toUpperCase();
	table = $('#maincontent');
	tr = table.find('tr');

	// Loop through all table rows, and hide those who don't match the search query
	for (i=0;i<tr.length;i++) {
		td = tr[i].getElementsByTagName("td")[0];
		if (td) {
			if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = '';
			} else {
				tr[i].style.display = 'none';
			}
		} 
	}
}

/* **************** ATTACHas ****************** */


  /* **************** ВСТАВКА изображений ****************** */
function insert_image(text, area) {
	var form = document.forms['form'];
	try {
	 var xarea = document.forms['DATA_tmp_storage'].area.value;
	 if (xarea != '') area = xarea;
	} catch(err) {;}
	var control = document.getElementById(area);
	                  
	//control.focus();

	// IE
	if (document.selection && document.selection.createRange){
		sel = document.selection.createRange();
		sel.text = text = sel.text;
	} else
	// Mozilla
	if (control.selectionStart || control.selectionStart == "0"){
		var startPos = control.selectionStart;
		var endPos = control.selectionEnd;

		control.value = control.value.substring(0, startPos) + text + control.value.substring(startPos, control.value.length);
		//control.selectionStart = msgfield.selectionEnd = endPos + open.length + close.length;
	} else {
		control.value += text;
	}
	control.focus();
}
/* Получение списка изображений */
function get_image_list(id, npp, page) {
	$.post('/engine/admin.php?mod=images&action=list&npp=' + npp + '&page=' + page, function (r) {
		var qw = $('.img-src a', r).attr('href');
		if (!qw) {
			showPopupDiv(id, 'Выберите изображение для вставки', '<b>Нет загруженных изображений!</b>', '<span class="" title="Закрыть" onclick="hidePopupDiv();">Закрыть</span>');
			return;
		} else {
			setTimeout(function () {
				var popup_body = '';
				var snum = '0';
				$(".img-src a", r).each(function () {
					snum++;
					var hrf = $(this).attr('href');
					var tr = $(this).closest('tr');
					var title = $('.img-title', tr).text();
					var width = $('.img-width', tr).text();
					var height = $('.img-height', tr).text();
					var size = $('.img-size', tr).text();
					//itxt += '<li><img width="140" src="' + hrf +'" title="' + title + '" onclick="openImgPopup(\''+ hrf + '\'); return false;" /></li>';
					if (size=='-') {
						popup_body += '<li class="is-broken img-link"><a href="' + hrf + '" target="_blank">' + title + '</a><div class="img-descr"><span>Изображение не найдено!</span></div></li>';
					} else {
						popup_body += '<li class="is-loading img-link">\
						<a href="javascript:;" onclick="\
							insert_image(\'[img=&#34;' + hrf + '&#34; width=&#34;' + width + '&#34; height=&#34;' + height + '&#34; align=&#34;left&#34;]' + title + ' (' + size + ')' + '[/img]\', \'' + currentInputAreaID + '\');\
							$(this).closest(\'li\').children(\'.img-descr\').children(\'span\').html(\'<b>Изображение вставлено</b>\');">Вставить</a>\
						<img src="\
							' + hrf +'" alt="' + title + '" \
							onload="\
							$(this).closest(\'li\').removeClass(\'is-loading\');\
							$(this).css(\'opacity\',\'1\').fadeIn();" \
						/>\
						<div class="img-descr">\
							<span>\
							' + title + '<br />' + width + 'x' + height + '<br />' + size +'</span>\
							</div>\
						</li>';
					}
				});
				
				showPopupDiv(id, 'Выберите изображение для вставки', '<ul class="clear list-image">' + popup_body + '</ul>', '<span class="img-back" title="Стрелочка влево">Назад</span><span class="img-next" title="Стрелочка вправо">Далее</span>');
				
				if (page<2) {$('.img-back').hide();} else {var page_back = page - 1; $('.img-back').attr('onclick','hidePopupDiv();get_image_list(\'' + id + '\', ' + npp + ', ' + page_back + ');');}

				if (snum<npp || page==0) {$('.img-next').hide();} else {page++; $('.img-next').attr('onclick','hidePopupDiv();get_image_list(\'' + id + '\', ' + npp + ', ' + page + ');');}
				
			}, 101);
		}
	});
}
/* Модальное окно */
function hidePopupDiv(){
	$('.div_popup')
	.fadeOut()
	.remove();
	$('#boxesModal').remove();
}
function showPopupDiv(id, title, body, footer) {
	$('#boxesModal').remove();
	var $modal = $('<div class="darkScreen" id="boxesModal" onclick="hidePopupDiv()"></div>');
	$("body").prepend($modal);
	$modal.css({'zIndex':9903, 'cursor' : 'pointer'}).fadeIn();

	$("body").append('<div id="'+id+'" class="div_popup" \
		style="top: ' + ($("body").scrollTop() + 50) + 'px;">\
		<div class="popup-close" title="Закрыть окно" onclick="hidePopupDiv();"><i class="fa fa-times"></i></div>\
		<div class="popup-title">' + title + '</div>\
		<div class="popup-body">' + body + '</div>\
		<div class="popup-footer">' + footer + '</div>').fadeIn();
}

//Изменение вкладок
	function ChangeOption(optn) {
		$('.navtab').css('display', 'none');
		document.getElementById(optn).style.display = (optn == optn)?"block":"none";
	}
	
	

/* cookie style core */
function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {
    createCookie(name,"",-1);
}

function formatSize($file_size){
    if ($file_size >= 1073741824) {
        $file_size = Math.round( $file_size / 1073741824 * 100 ) / 100 + " Гб";
    } else if ($file_size >= 1048576) {
        $file_size = Math.round( $file_size / 1048576 * 100 ) / 100 + " Мб";
    } else if ($file_size >= 1024) {
        $file_size = Math.round( $file_size / 1024 * 100 ) / 100 + " Кб";
    } else {
        $file_size = $file_size + " б";
    }
    return $file_size;
}
// считаем разрешение экрана
function findDimensions(){
	var width = 0, height = 0;
	if(window.innerWidth){
		width = window.innerWidth;
		height = window.innerHeight;
	}
	else if(document.body && document.body.clientWidth){
		width = document.body.clientWidth;
		height = document.body.clientHeight;
	}
	if(document.documentElement && document.documentElement.clientWidth){
		width = document.documentElement.clientWidth;
		height = document.documentElement.clientHeight;
	}
	var ret=new Array();
		ret['width']=width;
		ret['height']=height;
		return ret;
}

// Всплывающее изображение
function openImgPopup(text) {
	var monitor = findDimensions();
	$('#photo_popup').remove();
	$('#photo_popup_container').remove();
	html = '<div class="darkScreen" id="photo_popup" onclick="closeImgPopup();"></div>' +
			'<div class="popupNew" id="photo_popup_container" onclick="closeImgPopup();">' +
			 '<div class="popup-body">' +
			 '<img src="' + text + '" />' +
			'</div></div>';
	$("body").prepend(html);
	$("#photo_popup_container img").css({'max-height':(monitor['height'] - 100)});
	$("#photo_popup_container img").css({'max-width':(monitor['width'] - 50)});
	$("#photo_popup").fadeIn();
	$("#photo_popup_container").fadeIn();
	return false;
}
// Закрытие всплывающего окна изображения
function closeImgPopup() {
	$("#photo_popup").fadeOut();
	$("#photo_popup_container").fadeOut();
}


/*
Для input type="file"
HTML
<div class="btn btn-default btn-fileinput">
	<span><i class="fa fa-plus"></i> {l_attach.new}</span>
	<input type="file" name="image" id="image-con" onchange="validateFile(this);">
</div>
*/
function checkImage(where, idnumber) {
	var preview = document.getElementById('preview' + idnumber);
	preview.innerHTML = '';
	[].forEach.call(where.files, function(file) {
		if (file.type.match(/image.*/)) {
			var reader = new FileReader();
			reader.onload = function(event) {
				var img = document.createElement('img');
				img.src = event.target.result;
				img.style.cssText = 'vertical-align: top; width: 88px;';
				preview.appendChild(img);
			};
			reader.readAsDataURL(file);
		}
	});
}

function validateFile(fileInput,multiple,fileMaxSize) {
	var htext = '';
	var hsize = '';
	
	if (!fileInput.value) {
		$(fileInput).closest('.btn-fileinput').attr('style', '');
		$(fileInput).closest('.btn-fileinput').addClass('btn');
		$(fileInput).closest('.btn-fileinput').children('span').eq(0).html('<i class="fa fa-plus"></i> Add files ...');
		$(fileInput).closest('.btn-fileinput').children('span').attr('style', '');
		return false;
	}
	
	if (multiple) {
		for (var i = 0; i < fileInput.files.length; i++) {
			if (fileMaxSize) {
				htext += '<tr><td style="overflow:hidden;text-overflow:ellipsis;max-width: 400px;">' + fileInput.files[i].name+'</td><td nowrap><b class="pull-right' + (fileInput.files[i].size>fileMaxSize?' text-danger':'') + '">'+formatSize(fileInput.files[i].size)+'</td></tr>';
			} else {
				htext += '<tr><td style="overflow:hidden;text-overflow:ellipsis;max-width: 400px;">' + fileInput.files[i].name+'</td><td nowrap><b class="pull-right">'+formatSize(fileInput.files[i].size)+'</td></tr>';
			}
			hsize = Number(fileInput.files[i].size) + Number(hsize);
		}
		
		$(fileInput).closest('.btn-fileinput').removeClass('btn');
		
		$(fileInput).closest('.btn-fileinput').children('span').eq(0).html('<table\
			class="table-condensed table-bordered" style="width: 100%;">\
			' + htext + '<tr><td colspan="2" class="text-right">' + formatSize(hsize) + '</td></tr></table>');
		$(fileInput).closest('.btn-fileinput').children('span').eq(0).css({'width': '100%', 'display': 'block', 'white-space': 'nowrap'});
		$(fileInput).closest('.btn-fileinput').css({'width': '100%', 'display': 'block'});
	} else {
		for (var i = 0; i < fileInput.files.length; i++) {
			htext += fileInput.files[i].name+' ('+formatSize(fileInput.files[i].size)+')<br />';
			hsize = Number(fileInput.files[i].size) + Number(hsize);
		}
		
		$(fileInput).closest('.btn-fileinput').children('span').eq(0).html(htext);

	}
	
	return true;
}