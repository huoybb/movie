{% extends "index.volt" %}

{% block title %}
    {{ auth.name }}追看的电影或电视剧
{% endblock %}
{% block content %}
    <div class="container">
        <h1>我追看的列表</h1>
        <p>追看列表如下：</p>
        <div class="row">
            <h2>追看列表</h2>
            <table class="table table-hover">
                <tr>
                    <th>#</th>
                    <th>时间</th>
                    <th>电影</th>
                    <th>Episodes</th>
                    <th>状态</th>
                </tr>
                {% for row in movies %}
                    <tr>
                        <td>{{ row.watchlist.id }}</td>
                        <td>{{ row.watchlist.updated_at.diffForHumans() }}</td>
                        <td><a href="{{ url(['for':'movies.show','movie':row.movies.id]) }}">{{ row.movies.title }}</a></td>
                        <td>
                            {% if row.episodes.id is not null %}
                                <a href="{{ url(['for':'movies.showEpisode','movie':row.movies.id,'episode':row.episodes.id]) }}">第{{ row.episodes.num }}集:{{ row.episodes.title}}</a>
                            {% else %}
                                \
                            {% endif %}
                        </td>
                        <td>{{ row.watchlist.status }}</td>
                    </tr>
                {% endfor %}
            </table>
        </div>
        <div class="row">
            <h2>看过的记录</h2>
            --------------
        </div>

    </div>
{% endblock %}