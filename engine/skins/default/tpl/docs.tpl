<div class="row">
    <div class="col-md-3">
        <div class="docs__menu mx-2 my-5 py-4" style="border: 1px solid #ccc;">{{ menu }}</div>
    </div>
    <div class="col-md-9">
        <div class="docs__contents mx-2 my-5">
            {% if docs %}
                {{ docs }}
            {% else %}
                404
            {% endif %}
        </div>
    </div>
</div>
