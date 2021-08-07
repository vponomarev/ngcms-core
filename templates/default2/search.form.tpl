<form action="{{ form_url }}" method="post">
	<input type="hidden" name="category" value="" />
	<input type="hidden" name="postdate" value="" />

	<input
		type="text"
		name="search"
		value="{{ lang['search.site_search'] }}"
		class="searchtext"
		onblur="if(this.value=='') this.value='{{ lang['search.site_search'] }}';"
		onfocus="if(this.value=='{{ lang['search.site_search'] }}') this.value='';" />

	<input type="submit" value="" class="searchbtn" />
</form>
