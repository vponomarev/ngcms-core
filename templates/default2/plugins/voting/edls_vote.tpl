<div id="zz_voting_{voteid}">
	<div class="post">
		<div class="post-header">
			<div class="post-title">{votename}</div>
		</div>
		<div style="height: 10px;"></div>
		<div class="post-text">
			<p>
			<form action="{post_url}" method="get" id="voteForm_{voteid}">
				<input type=hidden name=action value=vote/>
				<input type=hidden name=voteid value="{voteid}"/>
				<input type=hidden name=referer value="{REFERER}"/>
				{votelines}<br/><input class="btn" type="submit" onclick="return make_voteL(0,{voteid});" value="Голосовать"/>
				<input class="btn" type="button" onclick="document.location='{post_url}?mode=show&voteid={voteid}';" value="Результаты"/>
			</form>
			</p>
		</div>
	</div>
</div>