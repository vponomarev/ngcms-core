<script language="javascript">
function markNameEdit() {
 var e = document.getElementById('bk_editImageName');
 var d = e.innerHTML;
 e.innerHTML = '<input type="text" name="newname" value="'+escape(d)+'"/>';
}

</script>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width=100% colspan="5" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">�������������</td>
</tr>
</table>
<br/>
<a href="{link_back}"><strong>��������� � ������</strong></a><br/>
<br/>
<form method="post" action="admin.php">
<input type="hidden" name="mod" value="images"/>
<input type="hidden" name="subaction" value="editApply"/>
<input type="hidden" name="id" value="{id}"/>

<table class="contentNav" border="0" cellspacing="0" cellpadding="2" width="100%">
<tr class="contRow2"><td width="300">�������� ��� �����������:</td><td>{orig_name}</td></tr>
<tr class="contRow1"><td>��� �����������:</td><td><span id="bk_editImageName">{name}</span> <a href="#" onclick="markNameEdit(); this.style.display='none';">[ ������������� ]</a></td></tr>
<tr class="contRow1"><td>������� URL �����������:</td><td><a target="_blank" href="{fileurl}">{fileurl}</a></td></tr>
<tr class="contRow2"><td>���� ��������:</td><td>{date}</td></tr>
<tr class="contRow2"><td>�����:</td><td>{author}</td></tr>
<tr class="contRow2"><td>������ �����������:</td><td>{width} x {height} ( {size} )</td></tr>
<tr class="contRow2"><td>���������:</td><td>{category}</td></tr>
<tr class="contRow1"><td>����� �� �����������:</td><td>[have_stamp]��������[/have_stamp][no_stamp]<label><input type="checkbox" name="createStamp" value="1"/> ��������[/no_stamp]</td></tr>
<tr class="contRow1"><td>����������� �����:</td>
 <td>
   ������: {preview_status}
  [preview]<br/>������: {preview_width} x {preview_height} ( {preview_size} )[/preview]<br/><br/>
  <a href="#" onclick="document.getElementById('bk_createPreview').style.display='block'; this.style.display='none';">[ ������� / �������� ]</a>
  <div id="bk_createPreview" style="display: none;">
  <fieldset>
  <legend>���������� ����������� ������</legend>
  <table>
   <tr><td colspan="2"><label><input type="checkbox" name="flagPreview" value="1" /> �������/�������� ����������� �����</label></td></tr>
   <tr><td>������ (�� �����):</td><td><input size="4" name="thumbSizeX" value="{thumb_size_x}"/> x <input size="4" name="thumbSizeY" value="{thumb_size_y}"/> ��������</td></tr>
   <tr><td>�������� JPEG:</td><td><input size="2" name="thumbQuality" value="{thumb_quality}"/> %</td></tr>
   <tr><td>�����:</td><td><label><input type="checkbox" name="flagStamp" value="1" /> ����������</label></td></tr>
   <tr><td>����:</td><td><label><input type="checkbox" name="flagShadow" value="1" /> ��������</label></td></tr>
  </table>
  </fieldset>
  </div>
 </td></tr>
[preview]<tr class="contRow1"><td>������� URL ����������� �����:</td><td><a target=_blank" href="{thumburl}">{thumburl}</a></td></tr>
<tr class="contRow1"><td>&nbsp;</td><td><img src="{thumburl}" border="0"/></td></tr>[/preview]
<tr class="contRow1"><td>��������:</td><td><textarea name="description" cols="80" rows="2">{description}</textarea></td></tr>
<tr class="contRow1"><td colspan="2"><input type="submit" style="width: 300px;" value="��������� ���������" class="button"/></td></tr>
</table>
</form>
