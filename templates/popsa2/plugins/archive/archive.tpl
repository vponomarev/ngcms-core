<ul class="plugin">
	{% for entry in entries %}
		{% if (loop.index <= 6) %}
			<li><a href="{{ entry.link }}">{{ entry.title }} {% if (entry.counter) %}({{ entry.cnt }}){% endif %}</a>
			</li>
		{% elseif (loop.index > 6) %}
			<li class="showhide">
				<a href="{{ entry.link }}">{{ entry.title }} {% if (entry.counter) %}({{ entry.cnt }}){% endif %}</a>
			</li>
		{% endif %}
	{% endfor %}
	{% if (entries|length >=7) %}
		<li><a href="javascript://" id="show_all_archive">Показать весь архив</a></li>{% endif %}
</ul>
<script>
	$(document).ready(function () {
		$(".showhide").hide();
	});
	$("#show_all_archive").click(function () {
		$(".showhide").toggle("fast");
	});
</script>