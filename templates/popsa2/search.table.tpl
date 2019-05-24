[found]
<div class="short">
	<div class="pad20">
		<header><h1>Результаты поиска</h1></header>
		По запросу - <b>{search}</b> найдено <b class="green_t">{count}</b> материалов
	</div>
</div>
[/found]
[notfound]
<div class="short">
	<div class="pad20">
		<div class="msge">{l_search.notfound}</div>
	</div>
</div>
[/notfound]
[error]
<div class="short">
	<div class="pad20">
		<div class="msge">Ошибка: {l_search.error}</div>
	</div>
</div>
[/error]
<div class="short">
	<div class="telo">
		<h3>Расширенный поиск</h3>
		<form method="get" action="{form_url}">
			<div class="input">
				<label>{l_search.filter.author}</label>
				<input type="text" name="author" value="{author}"/>
			</div>
			<div>
				<label>{l_search.filter.category}</label>
				{catlist}
			</div>
			<div>
				<label>{l_search.filter.date}</label>
				<select name="postdate">
					<option value=""></option>
					{datelist}</select>
			</div>
			<div class="input">
				<input type="text" name="search" value="{search}" class="story"/>
				<div class="clear10"></div>
				<input class="btn btn-primary btn-large" type="submit" value="{l_search.submit}"/>
			</div>
		</form>
		<div class="clear20"></div>
	</div>
</div>
<div class="full">
	<ul class="plugin">
		{entries}
	</ul>
</div>