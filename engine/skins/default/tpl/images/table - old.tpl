<h2 class="content-head">{l_images_title}</h2>

<!-- Main scripts -->
<script type="text/javascript">
var flagRequireReload = 0;

function setStatus(mode) {
 var st = document.getElementById('delform');
 st.subaction.value = mode;
}
</script>

<!-- Main content form -->
<div class="content tabs clear">
	<!-- Navigation bar -->
	<ul class="tabs-title clear">
		<li>{l_list}</li>
		[status]<li>{l_categories}</li>[/status]
		<li>{l_uploadnew}</li>
	</ul>
	<!-- /Navigation bar -->
	
	<div class="tabs-content">
		<div class="clear" id="list">
		<form action="{php_self}" method="get" name="options_bar">
			<input type="hidden" name="mod" value="images" />
			<input type="hidden" name="action" value="list" />
			<input type="hidden" name="area" value="{area}" />
			<dl class="fl">
				<dt><label class="fl" for="author">{l_author}</label></dt>
				<dd><select name="author" id="author"><option value="">- {l_all} -</option>{authorlist}</select></dd>
				[status]
				<dt><label class="fl" for="">{l_category} </label></dt>
				<dd>{dirlistcat}</dd>
				[/status]
			</dl>
			<dl class="fl">
				<dt><label class="fl" for="postdate">{l_month}</label></dt>
				<dd><select name="postdate" id="postdate"><option selected value="">- {l_all} -</option>{dateslist}</select></dd>
				<dt><label class="fl" for="npp">{l_per_page}</label></dt>
				<dd>
					<input class="fl" type="text" name="npp" id="npp" value="{npp}" />
					<input class="fr" type="submit" value="{l_show}" />
				</dd>
			</dl>
			<label class="clear w_100"><input type="checkbox" onclick="setCookie('img_preview',this.checked?1:0); document.location=document.location;" {box_preview}/> {l_show_preview}</label>
		</form>
		</div>
			
		<form action="{php_self}?mod=images" method="post" name="imagedelete" id="delform">
			<input type="hidden" name="area" value="{area}" />
			<input type="hidden" name="subaction" value="" />
			<table class="table-resp table-images" id="entries">
				<thead>
				<tr>
					<th><input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="check_uncheck_all(imagedelete)" /></th>
					<th>ID</th>
					<th>{l_name}</th>
					<th>{l_header.view}</th>
					<th>{l_header.insert}</th>
					<th>{l_category}</th>
					<th>{l_author}</th>
					<th>{l_size}</th>
					<th>{l_action}</th>
				</tr>
				</thead>
					{entries}
			</table>

			<div class="content-footer clear">
				[status]
				<input type="submit" class="fr btn btn-danger" onclick="setStatus('delete');" value="{l_delete}" />
				<div class="div-resp">
					<div class="input-group">
						{dirlist}
						<span>
							<button type="submit" onclick="setStatus('move');">{l_move}</button>
						</span>
					</div>
				</div>
				[/status]
			</div>
		</form>
		{pagesss}
	</div>
		
	[status]
	<div class="tabs-content" id="categories">
		<div class="div-resp">
			<h3 class="content-title">{l_addnewcat}</h3>
			<form action="{php_self}?mod=images" method="post" name="newcat">
				<input type="hidden" name="area" value="{area}" />
				<input type="hidden" name="subaction" value="newcat" />
				
				<div class="input-group">
					<input type="text" name="newfolder" required />
					<span>
						<button type="submit">Применить</button>
					</span>
				</div>
			</form>
		</div>
		
		<div class="div-resp">
			<h3 class="content-title">{l_delcat}</h3>
			<form action="{php_self}?mod=images" method="post" name="delcat">
				<input type="hidden" name="area" value="{area}" />
				<input type="hidden" name="subaction" value="delcat" />
				
				<div class="input-group">
					{dirlist}
					<span>
						<button type="submit">Применить</button>
					</span>
				</div>
			</form>
		</div>
	</div>
	[/status]

	<div class="tabs-content" id="uploadnew">
		<div class="div-resp">
			<h3 class="content-title">{l_upload_img}</h3>
			<form action="{php_self}?mod=images" method="post" enctype="multipart/form-data" name="sn">
				<input type="hidden" name="area" value="{area}" />
				<input type="hidden" name="subaction" value="upload" />
				
				<div class="input-group">
					<span class="input-group-check">
						<i class="fa fa-folder-open-o"></i>
					</span>
					{dirlistS}
				</div>
				<div class="input-group">
					<span class="input-group-check"><input type="checkbox" name="replace" id="flagReplace" value="1"/></span>
					<label for="flagReplace">{l_do_replace}</label>
				</div>
				<div class="input-group">
					<span class="input-group-check"><input type="checkbox" name="rand" id="flagRand" value="1"/></span>
					<label for="flagRand">{l_do_rand}</label>
				</div>
				<div class="input-group">
					<span class="input-group-check"><input type="checkbox" name="thumb" id="flagThumb" value="1" {thumb_mode}{thumb_checked}/></span>
					<label for="flagThumb">{l_do_preview}</label>
				</div>
				<div class="input-group">
					<span class="input-group-check"><input type="checkbox" name="shadow" value="1" id="flagShadow" {shadow_mode}{shadow_checked} /></span>
					<label for="flagShadow">{l_do_shadow}</label>
				</div>
				<div class="input-group">
					<span class="input-group-check"><input type="checkbox" name="stamp" value="1" id="flagStamp" {stamp_mode}{stamp_checked} /></span>
					<label for="flagStamp">{l_do_wmimage}</label>
				</div>

				<table id="imageup" class="upload table table-condensed">
					<tr id="row">
						<td>
							<div class="input-group">
								<div id="preview0"></div>
								<span class="input-group-check">1</span>
								<div class="btn btn-default btn-fileinput">
									<span id="spanfile0"><i class="fa fa-plus"></i> Add files...</span>
									<span id="spansize0"></span>
									<input type="file" name="userfile[]" id="userfile[0]" onchange="checkImage(this, 0);" multiple />
								</div>
								<code></code>
							</div>
						</td>
					</tr>
				</table>

				<div class="clear" id="showRemoveAddButtoms">
					<button class="btn btn-danger" type="button" onClick="RemoveImages();return false;" >-</button>
					<button class="btn btn-success" type="button" onClick="AddImages();return false;" >+</button>
					<button class="btn btn-primary" type="submit"><i class="fa fa-upload"></i> {l_uploadnew}</button>
				</div>
			</form>
			
			<script language="javascript" type="text/javascript">
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

					$(where).closest('.input-group').children('code').html('');
					
					//if (where.files.length>1) {
						var htext = '';
						var hsize = '';
						for (var i = 0; i < where.files.length; i++) {
							htext += where.files[i].name+' (<i class="fr">'+formatSize(where.files[i].size)+'</i>)<br />';
							hsize = Number(where.files[i].size) + Number(hsize);
						}
						$(where).closest('.input-group').children('code').html(htext);
						$(where).closest('.btn-fileinput').children('span').eq(0).html('<b>Выбрано файлов: '+where.files.length+'</b>');
						$(where).closest('.btn-fileinput').children('span').eq(1).html(' (<i class="fr">'+formatSize(hsize)+'</i>)');
					//}
					/*if (where.files.length==1) {
						validateFile(where, idnumber);
					}*/
					if (where.files.length==0) {
						$(where).closest('.btn-fileinput').children('span').eq(0).html('<i class="fa fa-plus"></i> Add files...');
						$(where).closest('.btn-fileinput').children('span').eq(1).html('');
					}
					
				}
				function AddImages() {
					var tbl = document.getElementById('imageup');
					var lastRow = tbl.rows.length;
					var iteration = lastRow+1;
					var row = tbl.insertRow(lastRow);
					var cellRight = row.insertCell(0);
					cellRight.innerHTML = '<div class="input-group"><div id="preview' + lastRow + '"></div><span class="input-group-check">'+ iteration +'</span>\
					<div class="btn btn-default btn-fileinput"><span id="spanfile' + lastRow + '"><i class="fa fa-plus"></i> Add files...</span><span id="spansize' + lastRow + '"></span><input type="file" name="userfile[]" id="userfile[' + lastRow + ']" onchange="checkImage(this, ' + lastRow + ');" multiple /></div><code></code></div>';
					var cellRight = row.insertCell(1);
					cellRight.innerHTML = '<div class="input-group"><div id="preview' + lastRow + '"></div><span class="input-group-check">'+ iteration +'</span>\
					<div class="btn btn-default btn-fileinput"><span id="spanfile' + lastRow + '"><i class="fa fa-plus"></i> Add files...</span><span id="spansize' + lastRow + '"></span><input type="file" name="userfile[]" id="userfile[' + lastRow + ']" onchange="checkImage(this, ' + lastRow + ');" multiple /></div><code></code></div>';
				}
				function RemoveImages() {
					var tbl = document.getElementById('imageup');
					var lastRow = tbl.rows.length;
					if (lastRow > 1){
						tbl.deleteRow(lastRow - 1);
					} else {
						AddImages();
						tbl.deleteRow(lastRow);
						
					}
				}
				
				removePreview = function(element) {
					queue = queue.filter(function(file) {
						return file.element != element;
					});

					element.parentNode.removeChild(element);
					checkValidity();
				}
			</script>
		</div>
		
		<div class="div-resp">
			<!-- UPLOAD_FILE_URL -->
			<h3 class="content-title">{l_upload_img_url}</h3>
			<form action="{php_self}?mod=images" method="post" name="snup">
				<input type="hidden" name="subaction" value="uploadurl" />
				<input type="hidden" name="area" value="{area}" />
				
				<div class="input-group">
					<span class="input-group-check">
						<i class="fa fa-folder-open-o"></i>
					</span>
					{dirlist}
				</div>
				<div class="input-group">
					<span class="input-group-check"><input type="checkbox" name="replace" id="replace2" value="1"/></span>
					<label for="replace2">{l_do_replace}</label>
				</div>
				<div class="input-group">
					<span class="input-group-check"><input type="checkbox" name="rand" id="rand2" value="1"/></span>
					<label for="rand2">{l_do_rand}</label>
				</div>
				<div class="input-group">
					<span class="input-group-check"><input type="checkbox" name="thumb" id="thumb2" value="1" {thumb_mode}{thumb_checked}/></span>
					<label for="thumb2">{l_do_preview}</label>
				</div>
				<div class="input-group">
					<span class="input-group-check"><input type="checkbox" name="shadow" id="shadow2" value="1" {shadow_mode}{shadow_checked} /></span>
					<label for="shadow2">{l_do_shadow}</label>
				</div>
				<div class="input-group">
					<span class="input-group-check"><input type="checkbox" name="stamp" id="stamp2" value="1" {stamp_mode}{stamp_checked} /></span>
					<label for="stamp2">{l_do_wmimage}</label>
				</div>
				<table class="upload" id="imageup2">
					<tr id="row">
						<td>
							<div class="input-group">
								<span class="input-group-check">1</span>
								<input type="url" name="userurl[0]" required />
							</div>
						</td>
					</tr>
				</table>

				<div class="clear">
					<button class="btn btn-danger fl" type="button" onClick="RemoveImages2();return false;" >-</button>
					<button class="btn btn-success fl" type="button" onClick="AddImages2();return false;" >+</button>
					<button class="fr" type="submit"><i class="fa fa-upload"></i></button>
				</div>
				
				<script language="javascript" type="text/javascript">
					function AddImages2() {
						var tbl = document.getElementById('imageup2');
						var lastRow = tbl.rows.length;
						var iteration = lastRow+1;
						var row = tbl.insertRow(lastRow);
						var cellRight = row.insertCell(0);
						cellRight.innerHTML = '<div class="input-group"><span class="input-group-check">'+ iteration +'</span><input type="url" name="userurl[' + lastRow + ']" required /></div>';
					}
					function RemoveImages2() {
						var tbl = document.getElementById('imageup2');
						var lastRow = tbl.rows.length;
						if (lastRow > 1){
							tbl.deleteRow(lastRow - 1);
						}
					}
				</script>
			</form>
		</div>
		<div class="clear"></div>
		<div class="sysinfo_common">Информация<div class="info" id="mfs"></div></div><script type="text/javascript">$('#mfs').html('Максимальный размер изображения: ' + formatSize({maxSize})+'<br />Допустимые расширения изображений: {listExt}');</script>
	</div>
</div>