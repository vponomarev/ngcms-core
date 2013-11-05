<article class="full-post">
	<h1 class="title"><a href="{link}">{title}</a></h1>
	<p><br />[comheader]Все комментарии посетителей к данной новости[/comheader]</p>
</article>

<div class="comments">
	<ul>
		<div id="new_comments_rev"></div>
		{entries}
		<div id="new_comments"></div>
	</ul>
</div>

<div class="pagination">
	<ul>
		<li>{more_comments}</li>
	</ul>
</div>

{form}

[regonly]
<div class="alert alert-info">
	Уважаемый посетитель, Вы зашли на сайт как незарегистрированный пользователь.<br />
	Мы рекомендуем Вам <a href="/register/">зарегистрироваться</a> либо <a href="/login/">войти</a> на сайт под своим именем.
</div>
[/regonly]

[commforbidden]
<div class="alert alert-info">
	Комментирование данной новости запрещено.
</div>
[/commforbidden]