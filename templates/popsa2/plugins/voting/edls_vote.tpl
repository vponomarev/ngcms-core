<div id="zz_voting_{voteid}">
	<div class="full">
		<h1>{votename}</h1>
		<div class="pad20_f">
			<form action="{post_url}" method="get" id="voteForm_{voteid}">
				<input type=hidden name=action value=vote/>
				<input type=hidden name=voteid value="{voteid}"/>
				<input type=hidden name=referer value="{REFERER}"/>
				{votelines}
				<div align="center">
					<button class="btn btn-primary btn-large" type="submit" onclick="return make_voteL(0,{voteid});">
						<span>Голосовать</span></button>
					<button class="btn btn-primary btn-large" type="button" onclick="document.location='{post_url}?mode=show&voteid={voteid}';">
						<span>Результаты</span></button>
				</div>
			</form>
		</div>
	</div>
</div>