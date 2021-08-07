<div class="blue_search">
	<form action="{{ form_url }}" method="post">
		<input type="hidden" name="category" value="" />
		<input type="hidden" name="postdate" value="" />

		<input type="text" name="search" placeholder="{{ lang['search.enter'] }}" />

		<input class="search_btn" type="image" src="{{ tpl_url }}/images/clean.png" />
	</form>
	<div class="clear"></div>
</div>
