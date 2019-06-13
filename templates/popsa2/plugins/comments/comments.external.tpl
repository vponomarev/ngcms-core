<article>
	<div class="short">
		<header><a href="{link}"><h2 class="green_l">{title}</h2></a></header>
		<div class="telo">
			[comheader]<p>Все комментарии посетителей к данной новости</p>[/comheader]
		</div>
	</div>
</article>
<div class="comm">
	<div class="telo"><h3 id="comments">Комментарии посетителей</h3></div>
</div>
<div id="new_comments_rev"></div>
{entries}
<div id="new_comments"></div>
<div class="clear20"></div>
[more_comments]
<nav>
	<div class="w_box">
		<!-- pagination START - вывод постраничной навигации (pages.tpl & variables.ini) -->
		<div id="pagination">
			{more_comments}
			<div class="clear"></div>
		</div>
		<!-- pagination END -->
	</div>
</nav>
[/more_comments]
[regonly]
<div class="msgi">Только зарегистрированные пользователи могут оставлять в данной новости свои комментарии.
</div>[/regonly]
[commforbidden]
<div class="msge">Комментирование данной новости запрещено!</div>[/commforbidden]
{form}