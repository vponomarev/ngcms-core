<strong>����� �� �����:</strong> {{ all }}<br>
<strong>��������:</strong> {{ num_guest }}<br>
<strong>����������������:</strong> {{ num_auth }}<br>
<strong>������� �����:</strong> {{ num_team }}<br>
<strong>������������:</strong> {{ num_users }}<br>
<strong>��������� �������:</strong> {{ num_bot }}<br>
{% if (entries_team.true) %}<br />{{entries_team.print}}{% endif %}
{% if (entries_user.true) %}<br />{{entries_user.print}}{% endif %}
{% if (today.true) %}<br />{{today.print}}{% endif %}
{% if (entries_bot.true) %}<br />{{entries_bot.print}}{% endif %}