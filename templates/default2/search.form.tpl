<form method="post" action="{form_url}">
	<input type="hidden" name="category" value=""/>
	<input type="hidden" name="postdate" value=""/>
	<input type="text" name="search" value="{l_search.site_search}" class="searchtext" onblur="if(this.value=='') this.value='{l_search.site_search}';" onfocus="if(this.value=='{l_search.site_search}') this.value='';">
	<input type="submit" value="" class="searchbtn">
</form>