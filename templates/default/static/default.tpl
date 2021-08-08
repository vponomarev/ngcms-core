<div class="block-title">{{ title }}</div>
{{ content }}

<div>
    {% if (flags.canEdit) %}
        <a href="{{ edit_static_url }}">{{ lang['editstatic'] }}</a>
    {% endif %}

    <a href="{{ print_static_url }}">{{ lang['printstatic'] }}</a>
</div>
