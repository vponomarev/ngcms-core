var $ = jQuery.noConflict();
var attachAbsoluteRowID = 0;

$(function() {
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

$(document).ready(function(){
	
	// Добавляем стилизацию в соответствии
	// с bootstrap для недоступных элементов
	$('[type=submit]:not(.btn)').addClass('btn btn-success');
	$('input:not([type=hidden]):not([type=submit]):not([type=button]):not([type=reset]):not([type=file]):not([type=checkbox]):not([type=radio]):not(.form-control):not(.button), select:not(.form-control), textarea:not(.form-control)').addClass('form-control').removeAttr('style');
	$('.button:not([type=submit])').addClass('btn btn-default').removeClass('button');
	$('.btnActive').addClass('btn btn-sm btn-primary').removeClass('btnActive');
	$('.btnInactive').addClass('btn btn-sm btn-default').removeClass('btnInactive');
	$('.navbutton').addClass('btn btn-sm btn-primary').removeClass('navbutton');
	$('[type=button]:not(.btn)').addClass('btn btn-default');
	$('[type=cancel]:not(.btn)').addClass('btn btn-default');
	$('table:not(.table):not(.table-condensed)').addClass('table table-condensed table-old');
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
		( $(this).scrollTop() != 0 ) ? $('#scrollup').fadeIn() : $('#scrollup').fadeOut();
	});
	
	$('#scrollup').click(function(){
		$('html, body').animate({ scrollTop: 0 }, 888);
		return;
	});
	
	// Боковое меню
	$('.sidebar-toggle, #sidenav-overlay').click(function () {
		$('.side-menu-container').toggleClass('slide-in');
		$('.side-body').toggleClass('body-slide-in');
		if($('.side-body').hasClass('body-slide-in'))
			$('#sidenav-overlay').fadeIn('slow');
		else
			$('#sidenav-overlay').fadeOut('slow');
	});

	/* Select/unselect all */
	$('table .select-all').click(function() {
		$(this).parents('table').find('input:checkbox:not([disabled])').prop('checked', $(this).prop('checked'));
	});

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
	
	/* switchTheme */
	$('#themes').on('click', function(e) {
		switchTheme( $("#theme-style option:selected").val());
	});

});
});
/* switchTheme */
function switchTheme(theme) {
	createCookie('theme-style', theme, 365);
	location.reload();
}

// Добавление элементов (пользователь, группы) в modal
$(document).on('click', '.add_form', function(){
	$('#modal-dialog .modal-dialog').load($(this).attr('href') + ' #add_edit_form .modal-content');
	$('#modal-dialog').modal('show');
	return false;
});
// Редактирование элементов (пользователь, группы) в modal
$(document).on('click', '.edit_form', function(){
	$('#modal-dialog .modal-dialog').load($(this).attr('href') + ' #add_edit_form .modal-content');
	$('#modal-dialog').modal('show');
	return false;
});

/* confirmIt */
function confirmIt(url, text){
	var agree = confirm(text);
	if (agree) document.location=url;
}

/* Main function to show Modal Bootsrtap */
function showModal(textOrID, header, footer, size) {
	var withID = document.getElementById(textOrID);
	if (withID && !header && !footer) { // Show modal with ID
		$(withID).modal('show');
		return;
	}
	var modalContent = '';
	if (header) {
		if (textOrID) {
		modalContent = '<div class="modal-header">\
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
								<span aria-hidden="true">&times;</span>\
							</button>\
							<h4 class="modal-title">' + header + '</h4>\
						</div>';
		} else {
		modalContent = '<div class="modal-header">\
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
								<span aria-hidden="true">&times;</span>\
							</button>\
							<h4 class="modal-title">Info</h4>\
						</div>';
		}
	} else {
		modalContent = '<div class="modal-header">\
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
								<span aria-hidden="true">&times;</span>\
							</button>\
							<h4 class="modal-title">Error</h4>\
						</div>';
	}
	if (textOrID)
		modalContent += '<div class="modal-body">' + textOrID + '</div>';
	else
		modalContent += '<div class="modal-body">Unable to load content . . .</div>';
	
	if (footer) {
		modalContent += '<div class="modal-footer">' + footer + '</div>';
	} else {
		modalContent += '<div class="modal-footer">\
							<button type="button" class="btn btn-default" data-dismiss="modal">\
							Close\
							</button>\
						</div>';
	}
	if (size == 'modal-lg')
		$('#modal-dialog .modal-dialog').addClass('modal-lg');
	else
		$('#modal-dialog .modal-dialog').removeClass('modal-lg');

	$('#modal-dialog .modal-content').html(modalContent); // #modal-dialog isset in html document'е
	$('#modal-dialog').modal('show');

	return;
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

// formatSize
function formatSize($file_size){
	if ($file_size >= 1073741824) {
		$file_size = Math.round( $file_size / 1073741824 * 100 ) / 100 + " Gb";
	} else if ($file_size >= 1048576) {
		$file_size = Math.round( $file_size / 1048576 * 100 ) / 100 + " Mb";
	} else if ($file_size >= 1024) {
		$file_size = Math.round( $file_size / 1024 * 100 ) / 100 + " Kb";
	} else {
		$file_size = $file_size + " b";
	}
	return $file_size;
}

function printElem(data) {
	
	var printing_css='<style>* {color:#888;} input{display:none;} a {text-decoration:none;}</style>';
	var html_to_print=printing_css + data;
	var iframe=$('<iframe id="print_frame">');
	$('body').append(iframe);
	var doc = $('#print_frame')[0].contentDocument || $('#print_frame')[0].contentWindow.document;
	var win = $('#print_frame')[0].contentWindow || $('#print_frame')[0];
	doc.getElementsByTagName('body')[0].innerHTML=html_to_print;
	win.print();
	$('iframe').remove();

	return true;
}
/* **************** Insert image ****************** */

$(document).on('click', '.preview-img a', function(){
	$(this).html('<span class=text-success>&#10004;</span>');
});

function insert_image(text, area) {
	var form = document.forms['form'];
	try {
	 var xarea = document.forms['DATA_tmp_storage'].area.value;
	 if (xarea != '') area = xarea;
	} catch(err) {;}
	var control = document.getElementById(area);
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
function getImageList(id, npp, page) {
	
	ngShowLoading();
	
	$.post('admin.php?mod=images&npp=' + npp + '&page=' + page, function (r) {
		var qw = $('.img-src a', r).attr('href');
		if (!qw) {
			showModal('Нет загруженных изображений!',
						'Выберите изображение для вставки',
						'<button type="button" class="btn btn-default pull-left" data-dismiss="modal">\
							<i class="fa fa-times"></i>\
						</button>\
						<button type="button" class="btn btn-primary pull-left" onclick="getImageList(\'img_popup\', 8, 1);return false;">\
							<i class="fa fa-refresh"></i>\
						</button>\
						<a href="#" class="btn btn-primary pull-left" onclick="$(\'#modal-dialog .modal-body\').load(\'admin.php?mod=images #upload-files\');return false;">\
							<i class="fa fa-upload"></i>\
						</a>');
			ngHideLoading();
			return false;
			
		} else {
			setTimeout(function () {
				var modalContent = '';
				var snum = '0';
				$(".img-src a", r).each(function () {
					snum++;
					var hrf = $(this).attr('href');
					var tr = $(this).closest('tr');
					var title = $('.img-title', tr).text();
					var width = $('.img-width', tr).text();
					var height = $('.img-height', tr).text();
					var size = $('.img-size', tr).text();
					if (size=='-') {
						modalContent += '<div class="preview-img" title="' + title + '"><a href="' + hrf + '" target="_blank">' + title + '</a><div class="img-descr"><span>Wrong Image source!</span></div></div>';
					} else {
						modalContent += '<div class="col-md-3 text-center" ><div class="preview-img" style="background-image: url(\'' + hrf + '\');" title="' + title + '">\
						<span class="img-descr"><span class="img-title">' + title + '</span></span>' +
							$('.insert-file', tr).html().replace('insertimage','insert_image') + 
							$('.insert-thumb', tr).html().replace('insertimage','insert_image') + 
							$('.insert-preview', tr).html().replace('insertimage','insert_image') + 
					'</div></div>';
					}
				});
				
				$("#modal-dialog .modal-dialog").addClass('modal-lg');
				
				$("#modal-dialog").animate({ scrollTop: 0 }, 888);
				
				showModal('<div class="row">' + modalContent + '</div>',
						'Выберите изображение для вставки',
						'<button type="button" class="btn btn-default pull-left" data-dismiss="modal">\
							<i class="fa fa-times"></i>\
						</button>\
						<a href="#" class="btn btn-primary pull-left" onclick="getImageList(\'img_popup\', 8, 1);return false;">\
							<i class="fa fa-refresh"></i>\
						</a>\
						<a href="#" class="btn btn-primary pull-left" onclick="$(\'#modal-dialog .modal-body\').load(\'admin.php?mod=images #upload-files\');return false;">\
							<i class="fa fa-upload"></i>\
						</a>\
						<button type="button" class="btn btn-success img-back">\
							<i class="fa fa-backward"></i>\
						</button>\
						<button type="button" class="btn btn-success img-next">\
							<i class="fa fa-forward"></i>\
						</button>',
						'modal-lg');
				
				if (page<2) {$('.img-back').attr('disabled','disabled');} else {var page_back = page - 1; $('.img-back').attr('onclick','ngShowLoading();getImageList(\'' + id + '\', ' + npp + ', ' + page_back + ');ngHideLoading(); return false;');}

				if (snum<npp || page==0) {$('.img-next').attr('disabled','disabled');} else {page++; $('.img-next').attr('onclick','ngShowLoading();getImageList(\'' + id + '\', ' + npp + ', ' + page + ');ngHideLoading(); return false;');}
				
			}, 101);
		}
		ngHideLoading();
	});
}

/*
Для input type="file"
HTML
<div class="btn btn-default btn-fileinput">
	<span><i class="fa fa-plus"></i> Add files ...</span>
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
	var btnFileInput = $(fileInput).closest('.btn-fileinput');
	
	if (!fileInput.value) {
		btnFileInput.attr('style', '');
		btnFileInput.addClass('btn');
		btnFileInput.children('span').eq(0).html('<i class="fa fa-plus"></i> Add files ...');
		btnFileInput.children('span').attr('style', '');
		return false;
	}
	
	if (multiple) {
		for (var i=0;i<fileInput.files.length;i++) {
			if (fileMaxSize) {
				htext += '<tr><td style="overflow:hidden;text-overflow:ellipsis;max-width: 400px;">' + fileInput.files[i].name+'</td><td nowrap><b class="pull-right' + (fileInput.files[i].size>fileMaxSize?' text-danger':'') + '">'+formatSize(fileInput.files[i].size)+'</b></td></tr>';
			} else {
				htext += '<tr><td style="overflow:hidden;text-overflow:ellipsis;max-width: 400px;">' + fileInput.files[i].name+'</td><td nowrap><b class="pull-right">'+formatSize(fileInput.files[i].size)+'</td></tr>';
			}
			hsize = Number(fileInput.files[i].size) + Number(hsize);
		}
		
		btnFileInput.removeClass('btn');
		btnFileInput.children('span').eq(0).html('<table\
			class="table-condensed table-bordered" style="width: 100%;">\
			' + htext + '<tr><td colspan="2" class="text-right">' + formatSize(hsize) + '</td></tr></table><div class="progress"><div id="progressbar" class="progress-bar progress-bar-success" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div>');
		btnFileInput.children('span').eq(0).css({'width': '100%', 'display': 'block'/*, 'white-space': 'nowrap'*/});
		btnFileInput.css({'width': '100%', 'display': 'block'});
	} else {
		for (var i=0;i< fileInput.files.length;i++) {
			htext += fileInput.files[i].name+' ('+formatSize(fileInput.files[i].size)+')<br />';
			hsize = Number(fileInput.files[i].size) + Number(hsize);
		}
		
		btnFileInput.children('span').eq(0).html(htext);
	}
	
	return true;
}

function attachAddRow(id) {
	
	++attachAbsoluteRowID;
	var tbl = document.getElementById(id);
	var lastRow = tbl.rows.length;
	var row = tbl.insertRow(lastRow - 1);
	
	// Add cells, Add file input
	if ( id == 'imageup2' || id == 'fileup2' ) {
		row.insertCell(0).innerHTML = '<input type="text" name="userurl[' + attachAbsoluteRowID + ']" class="form-control">'
	} else if ( id == 'imageup' || id == 'fileup' ) {
		row.insertCell(0).innerHTML = '<div class="btn btn-default btn-fileinput">\
							<span><i class="fa fa-plus"></i> Add files ...</span>\
							<input type="file" name="userfile[' + attachAbsoluteRowID + ']" onchange="validateFile(this, multiple);" multiple="multiple" / >\
						</div>';
	} else if ( id == 'attachFilelist' ) {
		row.insertCell(0).innerHTML = '<div class="btn btn-default btn-fileinput">\
								<span><i class="fa fa-plus"></i> Add files ...</span>\
								<input type="file" name="userfile[]" onchange="validateFile(this, multiple);" multiple="multiple" / >\
							</div>';
	} else if ( id == 'attachFilelist_edit' ) {
		var xCell = row.insertCell(0);
		xCell.setAttribute('colspan', '5');
		xCell.innerHTML = '<div class="btn btn-default btn-fileinput">\
							<span><i class="fa fa-plus"></i> Add files ...</span>\
							<input type="file" name="userfile[]" onchange="validateFile(this, multiple);" multiple="multiple" />\
						</div>';
	} else {
		row.insertCell(0).innerHTML = '<div class="btn btn-default btn-fileinput">\
							<span><i class="fa fa-plus"></i> Add files ...</span>\
							<input type="file" name="userfile[' + attachAbsoluteRowID + ']" onchange="validateFile(this);">\
						</div>';
	}
	
	var xCell = row.insertCell(1);
	xCell.setAttribute('class', 'text-center');
	
	el = document.createElement('button');
	el.setAttribute('type', 'button');
	el.setAttribute('onclick', 'document.getElementById("' + id + '").deleteRow(this.parentNode.parentNode.rowIndex);');
	el.setAttribute('class', 'btn btn-danger');
	el.innerHTML = '<i class="fa fa-trash"></i>';
	xCell.appendChild(el);

}

$(document).on('submit', '#upload-files', function(e){
	
	e.preventDefault();
	$("#modal-dialog").animate({ scrollTop: 0 }, 888);
	var progressBar = $('#progressbar');
	
	var $formData = new FormData($(this)[0]);
	$formData.append('ngAuthCookie', '{authcookie}');
	$formData.append('category', document.getElementById('categorySelect').value);
	$formData.append('rand', document.getElementById('flagRand').checked?1:0);
	$formData.append('replace', document.getElementById('flagReplace').checked?1:0);
	
	if ( $("input[name='uploadType']").val() == 'image' ) {
		$formData.append('uploadType', 'image');
		$formData.append('thumb', document.getElementById('flagThumb').checked?1:0);
		$formData.append('stamp', document.getElementById('flagStamp').checked?1:0);
		$formData.append('shadow', document.getElementById('flagShadow').checked?1:0);
	} else {
		$formData.append('uploadType', 'file');
	}
	
	$.each($(this).find("input[type='file']"), function(i, tag) {
		var input, filter, table, tr, td, i;
		table = $(this).parent().find('table');

		$.each($(tag)[0].files, function(i, file) {
			tr = table.find('tr');
			$formData.append('Filedata', file);
			$.ajax({
				url: 'rpc.php?methodName=admin.files.upload',
				data: $formData,
				processData: false,
				contentType: false,
				type: 'POST',
				//dataType: 'JSON',
				xhr: function(){
					var xhr = $.ajaxSettings.xhr();
					xhr.upload.addEventListener('progress', function(evt){
					  if(evt.lengthComputable) {
						var percentComplete = Math.floor(evt.loaded / evt.total * 100);
						progressBar.css('width', percentComplete + '%').text(percentComplete + '%');
					  }
					}, false);
					return xhr;
				},
				success: function (res) {
					// Response should be in JSON format
					var resData;
					var resStatus = 0;
					td = tr[i].getElementsByTagName('td')[0];
					tr[i].style.background = 'white';
					tr[i].style.color = 'black';
					
					try {
						resData = eval(res);
						if (typeof(resData['status']))
							resStatus = 1;
					} catch (err) {
						alert('Error parsing JSON output. Result: '+res);
					}
					if (!resStatus) {
						alert('Upload resp: '+res);
						tr[i].style.color = 'red';
						return false;
					}
					
					flagRequireReload = 1;
					
					// If upload fails
					if (resData['status'] < 1) {
						el = document.createElement('div');
						el.setAttribute('class', 'text-danger');
						el.innerHTML = '('+resData['errorCode']+') '+resData['errorText'];
						td.appendChild(el);
						if (typeof(resData['errorDescription']) !== 'undefined') {
							el = document.createElement('div');
							el.setAttribute('class', 'text-info');
							el.innerHTML = resData['errorDescription'];
							td.appendChild(el);
						}
						tr[i].style.color = 'red';
						return false;
					} else {
						el = document.createElement('div');
						el.setAttribute('class', 'text-success');
						el.innerHTML = resData['errorText'];
						td.appendChild(el);
						//$(tr[i]).fadeOut(3000);
					}
					return true;
				},
				error : function(res) {
					console.log(res.responseText);
					$.notify({message:'Error parsing JSON output.'},{type:'danger'});
					tr[i].style.color = 'red';
					return false;
				}
				
			});
		});
		
	});
});