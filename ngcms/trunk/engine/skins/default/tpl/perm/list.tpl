<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width=100% colspan="5" class="contentHead"><img src="{{ skins_url }}/images/nav.gif" hspace="8" /><a href="?mod=perm">���������� ������� �������</a></td>
</tr>
</table>

<script type="text/javascript">
var permDefault = {{ DEFAULT_JSON }};

function onUpdatePerm(name) {
 var f = document.getElementById('permSubmit');
 var v = permDefault[name];

 if (f[name].value != v) {
 	f[name].className = 'pChanged';
 } else {
 	f[name].className = '';
 }
 //alert(f[name].value);
}

function onUpdateSubmit() {
 return true;

 var f = document.getElementById('permSubmit');
 for (var i = 0; i < f.elements.length; i++) {
 	if (f.elements[i].value != permDefault[f.elements[i].name]) {
		alert(f.elements[i].name+': '+permDefault[f.elements[i].name]+ ' => '+f.elements[i].value);
	}
 	if (i > 10) { break; }
 }
}
</script>

<!-- Form header -->
<form id="permSubmit" name="permSubmit" method="post" action="admin.php?mod=perm">
<input type="hidden" name="save" value="1"/>
<input type="hidden" name="token" value="{{ token }}"/>
<!-- /Form header -->


<!-- Group menu header -->
<div id="userTabs">
 <ul>
{% for group in GRP %}  <li><a href="#userTabs-{{ group.id }}">{{ group.title }}</a></li>
{% endfor %}
 </ul>

 <!-- Group content header -->
{% for group in GRP %}
 <!-- Content for group [{{ group.id }}] {{ group.title }} -->
 <div id="userTabs-{{ group.id }}">
  <div><i>���������� ������� ������ �������������: <b>{{ group.title }}</b></i></div>
  <br/>

{% for block in CONFIG %}
  <div class="pconf">
   <h1>{{ block.title }}</h1>
{% if (block.description) %}   <i>{{ block.description }}</i><br/>{% endif %}

{% for area in block.items %}
   {% if (area.title != '') %}<h2>{{ area.title }}</h2>{% endif %}
{% if (area.description) %}   <i>{{ area.description }}</i><br/><br/>{% endif %}

   <table width="100%" class="content">
    <thead><tr class="contHead"><td><b>#ID</b></td><td><b>��������</b></td><td width="90"><b>������</b></td></thead>
{% for entry in area.items %}
    <tr class="contentEntry1">
     <td width="180"><strong>{{entry.id}}</strong></td><td>{{ entry.title }}</td>
     <td width="220">
     {% if (entry.type == '') %}
	  <select name="{{ entry.name }}|{{group.id}}" onchange="onUpdatePerm('{{ entry.name }}|{{group.id}}');">
	   <option value="-1">--</option>
	   <option value="0"{% if (isSet(entry.perm[group.id]) and (not entry.perm[group.id])) %} selected="selected"{% endif %}>���</option>
	   <option value="1"{% if (isSet(entry.perm[group.id]) and (entry.perm[group.id])) %} selected="selected"{% endif %}>��</option>
	  </select>
	  {% endif %}
	  {% if entry.type == 'listCategoriesSelector' %}
      <select style="width: 200px;" size="8" id="{{ entry.uniqId }}_{{ group.id }}" name="{{ entry.name }}|{{group.id}}[]" multiple="multiple">
      <!-- {{ debugValue(entry.perm[group.id]) }} -->
      {% for opt in entry.generatedOptions %}
      	<option value="{{ opt.k }}"{% if (entry.perm[group.id][opt.k]) %} selected="selected"{% endif %}>{{ opt.v }}</option>
      {% endfor %}
      </select>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#{{ entry.uniqId }}_{{ group.id }}").multiselect();
		});
	</script>
	  {% endif %}
	 </td>
    </tr>
{% endfor %}
   </table>
<br/>
{% endfor %}
  </div>
{% endfor %}

 </div>
<!-- /Content for group [{{ group.id }}] {{ group.title }} -->
{% endfor %}
</div>

<script type="text/javascript">
$(function(){
  $("#userTabs").tabs();
});
</script>
<br/>

<input type="submit" value="��������� ���������" onclick="return onUpdateSubmit();" />
</form>