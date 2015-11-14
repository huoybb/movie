<table class="table table-hover">
    <tr>
        <th>#</th>
        <th>更新时间</th>
        <th>星期几</th>
        <th>Seasons</th>
        <th>Episodes</th>
        <th>评论</th>
        <th>链接</th>
    </tr>
    {% for row in episodes %}
        <tr>
            <td>{{ row.episodes.id }}</td>
            <td>{{ row.episodes.date.diffForHumans() }}</td>
            <td>{{ row.episodes.date.format('l') }}</td>
            <td><a href="{{ url(['for':'movies.show','movie':row.movies.id]) }}">{{ row.movies.title }}</a></td>
            <td><a href="{{ url(['for':'movies.showEpisode','movie':row.movies.id,'episode':row.episodes.id]) }}">第{{ row.episodes.num }}集:{{ row.episodes.title}}</a></td>
            <td>
                {{ row.commentsCount }}
            </td>
            <td>
                {% if row.movies.getFirstTTMJLink() %}
                    <a href="{{ row.movies.getFirstTTMJLink().url }}" target="_blank">天天美剧</a>
                {% endif %}
                {% if row.links.id is not null %}
                    <a href="{{ row.links.url }}" target="_blank">{{ row.links.site().name }}</a>
                {% endif %}
            </td>

        </tr>
    {% endfor %}
</table>