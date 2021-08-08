# Шаблон `search.form.tpl`

Шаблон отвечает за генерацию краткой поисковой формы, отображаемой на всех страницах сайта.

Фактически этот шаблон – часть шаблона [main.tpl](templates/main.tpl.md), но для удобства работы поисковая форма была вынесена в отдельный `.tpl` файл.

Шаблон должен содержать форму (тег `<form>`, метод запроса – *GET* или *POST*), которая позволит вводить параметры поиска.

## Доступные блоки / переменные

### Поля ввода

В форме поддерживаются следующие поля ввода с именами:

- `search` (тип: `text`) – строка для поиска;

### Переменные:

- `form_url` – URL поисковой формы.

Для более детального описания см. шаблон [search.table.tpl](templates/search.table.tpl.md).

## Пример заполнения шаблона

В примере показан минимально набор для полнофункциональной работы:

```html
<form name="short_search" action="{{ form_url }}" method="GET">
    <input type="hidden" name="category" value="" />
    <input type="hidden" name="postdate" value="" />

    <input type="text" name="search"/>

    <input type="submit" class="button" value="Search" />
</form>
```
