<div class="comm" id="comment_{id}">
	<div class="k160">
		<div class="ava"><a href="{profile_link}" target="_blank" title="{l_profile}">{avatar}</a></div>
	</div>
	<div class="k480">
		<div class="pad_comm">
			<ul class="meta">
				<li class="no_pad"> #{comnum}</li>
				<li class="date">{date}</li>
				<li class="author">[profile]<a href="{profile_link}" target="_blank" title="{l_profile}">[/profile]<b>
							{author} </b>[profile]</a>[/profile]
				</li>
				<li class="no_pad">[quote]<a href="" onclick="quote('{author}'); return false;" title="{l_quote}">
						{l_quote}</a>[/quote]
				</li>
				[if-have-perm]
				<li class="no_pad">[edit-com]{l_addanswer}[/edit-com]</li>
				<li class="no_pad">[del-com]{l_comdelete}[/del-com]</li>
				[/if-have-perm]
			</ul>
			<div class="clear"></div>
			<p>{comment-short}[comment_full]<span id="comment_full{comnum}" style="display: none;">{comment-full}</span>
			<p style="text-align: right;"><a href="javascript:ShowOrHide('comment_full{comnum}');">{l_showhide}</a></p>
			[/comment_full]</p>
			[answer]<p>
			<div class="answer"><i>{l_answer}</i> <b>{name}</b><br/>{answer}</div>
			</p>[/answer]
		</div>
	</div>
	<div class="clear"></div>
</div>