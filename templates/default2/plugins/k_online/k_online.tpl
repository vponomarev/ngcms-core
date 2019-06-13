<strong>Всего на сайте:</strong> {{ all }}<br>
<strong>Анонимов:</strong> {{ num_guest }}<br>
<strong>Авторизированных:</strong> {{ num_auth }}<br>
<strong>Команда сайта:</strong> {{ num_team }}<br>
<strong>Пользователи:</strong> {{ num_users }}<br>
<strong>Поисковых роботов:</strong> {{ num_bot }}<br>
{% if (entries_team.true) %}<br/>{{ entries_team.print }}{% endif %}
{% if (entries_user.true) %}<br/>{{ entries_user.print }}{% endif %}
{% if (today.true) %}<br/>{{ today.print }}{% endif %}
{% if (entries_bot.true) %}<br/>{{ entries_bot.print }}{% endif %}