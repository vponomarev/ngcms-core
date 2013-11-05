<span id="bookmarks_{news}"><a href="{link}" title="{link_title}">{text}</a> {counter}</span> 
<script>
	var el = document.getElementById('bookmarks_{news}').getElementsByTagName('a')[0];
	el.setAttribute('href', '#');
	el.setAttribute('onclick', 'bookmarks("{url}","{news}","{action}"); return false;');
</script>