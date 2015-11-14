{% extends "index.volt" %}
{% block title %}
    最新更新剧目
{% endblock %}
{% block content %}
    <div class="container">
        <h1>全部更新的剧目，<a href="{{ url(['for':'calendar.MyLatest']) }}">我的关注列表</a></h1>
        <p>更新剧目如下：</p>
        <div class="row">
            {% include "calendar/partials/movielist.volt" %}
        </div>
    </div>
{% endblock %}