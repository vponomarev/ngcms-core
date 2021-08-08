{% if (flags.found) %}
<div class="short">
	<div class="pad20">
		<header><h1>Результаты поиска</h1></header>
		По запросу - <b>{{ search }}</b> найдено <b class="green_t">{{ count }}</b> материалов
	</div>
</div>
{% endif %}
{% if (flags.notfound) %}
<div class="short">
	<div class="pad20">
		<div class="msge">{{ lang['search.notfound'] }}</div>
	</div>
</div>
{% endif %}
{% if (flags.error) %}
<div class="short">
	<div class="pad20">
		<div class="msge">Ошибка: {{ lang['search.error'] }}</div>
	</div>
</div>
{% endif %}
<div class="short">
	<div class="telo">
		<h3>Расширенный поиск</h3>
		<form action="{{ form_url }}" method="get">
			<div class="input">
				<label>{{ lang['search.filter.author'] }}</label>
				<input type="text" name="author" value="{{ author }}"/>
			</div>
			<div>
				<label>{{ lang['search.filter.category'] }}</label>
				{{ catlist }}
			</div>
			<div>
				<label>{{ lang['search.filter.date'] }}</label>
				<select name="postdate">
					<option value=""></option>
					{{ datelist }}
				</select>
			</div>
			<div class="input">
				<input type="text" name="search" value="{{ search }}" class="story"/>
				<div class="clear10"></div>
				<input class="btn btn-primary btn-large" type="submit" value="{{ lang['search.submit'] }}"/>
			</div>
		</form>
		<div class="clear20"></div>
	</div>
</div>
<div class="full">
	<ul class="plugin">
		{{ entries }}
	</ul>
</div>
