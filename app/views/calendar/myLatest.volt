{% extends "index.volt" %}

{% block title %}
    {{ auth.name }}关注的剧目更新列表
{% endblock %}
{% block content %}
    <div class="container">
        <h1>我关注的更新列表,<a href="{{ url(['for':'calendar.latest']) }}">全部的更新列表</a></h1>
        <p>更新剧目如下：</p>
        <div class="row">
            {% include "calendar/partials/movielist.volt" %}
        </div>

    </div>
{% endblock %}