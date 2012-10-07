<style type="text/css">
.pconf h1 {
	background-color: yellow;
	margin-top: 8px;
	margin-bottom: 3px;
	padding-top: 5px;
	padding-bottom: 5px;
	margin-top: 1px;
	margin-bottom: 1px;
	padding-left: 5px;
}

.pconf h2 {
	background-color: #EEEEEE;
	padding-top: 5px;
	padding-bottom: 5px;
	margin-top: 1px;
	margin-bottom: 1px;
	padding-left: 5px;
}

.pconf .content TD {
 background-color: #F0F0F0;

}

.pChanged {
	background-color: red;
}

</style>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width=100% colspan="5" class="contentHead"><img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="?mod=perm">Управление правами доступа</a></td>
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
<form id="permSubmit" name="permSubmit" method="POST">
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
  <div><i>Управление правами группы пользователей: <b>{{ group.title }}</b></i></div>
  <br/>

{% for block in CONFIG %}
  <div class="pconf">
   <h1>{{ block.title }}</h1>
{% if (block.description) %}   <i>{{ block.description }}</i><br/>{% endif %}

{% for area in block.items %}
   <h2>{{ area.title }}</h2>
{% if (area.description) %}   <i>{{ area.description }}</i><br/><br/>{% endif %}

   <table width="100%" class="content">
    <thead><tr class="contentHead"><td><b>#ID</b></td><td><b>Описание</b></td><td width="90"><b>Доступ</b></td></td></thead>
{% for entry in area.items %}
    <tr class="contentEntry1">
     <td><i>{{entry.id}}</i></td><td>{{ entry.title }}</td>
     <td>
	  <select name="{{ entry.name }}|{{group.id}}" onchange="onUpdatePerm('{{ entry.name }}|{{group.id}}');" value="{% if isSet(entry.perm[group.id]) %}{% if (entry.perm[group.id]) %}1{% else %}0{% endif %}{% else %}-1{% endif %}">
	   <option value="-1">--</option>
	   <option value="0"{% if (isSet(entry.perm[group.id]) and (not entry.perm[group.id])) %} selected="selected"{% endif %}>Нет</option>
	   <option value="1"{% if (isSet(entry.perm[group.id]) and (entry.perm[group.id])) %} selected="selected"{% endif %}>Да</option>
	  </select>
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

<input type="submit" value="Сохранить изменения" onclick="return onUpdateSubmit();" />
</form>
