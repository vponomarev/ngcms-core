<script type="text/javascript">
	var vajax = new sack();
	function make_vote(mode) {
		var form = document.getElementById('voteForm');
		var choice = -1;

		// Return true (to run normal mode) if AJAX failed
		if (vajax.failed)
			return true;

		for (i = 0; i < form.elements.length; i++) {
			var elem = form.elements[i];
			if (elem.type == 'radio') {
				if (elem.checked == true) {
					choice = elem.value;
				}
			}
		}

		var voteid = form.voteid.value;
		if (mode && (choice < 0)) {
			alert('Сначала необходимо выбрать вариант!');
			return false;
		}


		if (mode) {
			vajax.setVar("mode", "vote");
			vajax.setVar("choice", choice);
		} else {
			vajax.setVar("mode", "show");
		}
		vajax.setVar("style", "ajax");
		vajax.setVar("voteid", voteid);
		vajax.setVar("list", 0);
		vajax.requestFile = "{post_url}";
		vajax.method = 'POST';
		vajax.element = 'voting_ng';
		vajax.runAJAX();
		return false;
	}
</script>
<div id="voting_ng">
	<form action="{post_url}" method="post" id="voteForm">
		<div><b>{votename}</b></div>
		<div>
			{votelines}
			<input type="hidden" name="mode" value="vote"/>
			<input type="hidden" name="voteid" value="{voteid}"/>
			<input type="hidden" name="referer" value="{REFERER}"/>
			<div align="center">
				<button class="btn btn-primary btn-large" type="submit" onclick="return make_vote(1);">
					<span>Голосовать</span></button>
				<button class="btn btn-primary btn-large" type="button" onclick="return make_vote(0);">
					<span>Результаты</span></button>
			</div>
		</div>
	</form>
</div>