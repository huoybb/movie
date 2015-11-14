<hr>

<h2>分集评论:</h2>
<ul>
    {% for row in movie.getEpisodesComments() %}
        <li>
            <div> <span>by <a href="{{ url(['for':'users.showComments','user':row.comments.user().id]) }}"> {{ row.comments.user().name }}</a></span>--<span>at: {{ row.comments.updated_at.diffForHumans() }}</span>
                {% if auth.has(row.comments) %}
                    <span><a href="{{ row.comments.getEditURL() }}">edit</a></span>
                    <span><a href="{{ row.comments.getDeleteURL() }}">delete</a></span>
                {% endif %}
                --<span>评：<a href="{{ url(['for':'movies.showEpisode','movie':row.episodes.getMovie().id,'episode':row.episodes.id]) }}">第{{ row.episodes.num }}集：{{ row.episodes.title }}</a></span>
            </div>
            <div>
                {{row.comments.content|nl2br}}
            </div>
        </li>
    {% endfor %}
</ul>