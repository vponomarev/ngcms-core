<div id="comment_{id}" class="comment">
	<div class="avatar">
		<div class="num">{comnum}</div>
		{avatar}
	</div>
	<div class="data">
		<div class="info">
			<div class="title author">[profile]<a href="{profile_link}" target="_blank" title="{l_profile}">[/profile]{author}[profile]</a>[/profile]
			</div>
			<div class="date">{date}</div>
		</div>
		<div class="text">
			{comment-short}[comment_full]<span id="comment_full{comnum}" style="display: none;">{comment-full}</span>
			<p style="text-align: right;"><a href="javascript:ShowOrHide('comment_full{comnum}');">{l_showhide}</a></p>
			[/comment_full]
			[answer]<br/> --------------------------
			<div><i>{l_answer}</i> <b>{name}</b><br/>{answer}</div>
			[/answer]
			[quote]<a onclick="quote('{author}'); return false;" class="quotes">{l_quote}</a>[/quote]
		</div>
	</div>
</div>