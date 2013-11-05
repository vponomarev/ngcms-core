<div id="zz_voting_{voteid}">
<div class="poll-block">
	<form action="{post_url}" method="get" id="voteForm_{voteid}">
		<div class="block-title">{votename}</div>
		<input type=hidden name=action value=vote />
		<input type=hidden name=voteid value="{voteid}" />
		<input type=hidden name=referer value="{REFERER}" />
		{votelines}<br /><input class="button" type="submit" onclick="return make_voteL(0,{voteid});" value="Голосовать" /> <input class="button" type="button" onclick="document.location='{post_url}?mode=show&voteid={voteid}';" value="Результаты" />
	</form>
</div>
</div>