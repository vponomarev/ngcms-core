<nav>
	<div class="w_box">
		<!-- pagination START - вывод постраничной навигации (pages.tpl & variables.ini) -->
		<div id="pagination">
			{% if (flags.previous_page) %}
				<span class="prev">{{ previous_page }}</span>
			{% endif %}

			{{ pages }}

			{% if (flags.next_page) %}
				<span class="next">{{ next_page }}</span>
			{% endif %}
			<div class="clear"></div>
		</div>
		<!-- pagination END -->
	</div>
</nav>
