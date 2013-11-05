{% for entry in entries %}
<div class="label label-table" id="xfl_{{entry.id}}">
	<label>{{entry.title}}:</label>
	<span class="xprofile">{{entry.input}}</span>
</div>
{% endfor %}