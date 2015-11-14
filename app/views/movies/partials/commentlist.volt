<hr>

<h2>Comments:</h2>
<ul>
    {% for comment in comments %}
    <li>
        <div>
            <span>
                by <a href="{{ url(['for':'users.showComments','user':comment.user().id]) }}"> {{ comment.user().name }}</a>
            </span>
            --
            <span>
                at: {{ comment.updated_at.diffForHumans() }}
            </span>
            {% if auth.has(comment) %}
            <span><a href="{{ comment.getEditURL() }}">edit</a></span>
            <span><a href="{{ comment.getDeleteURL() }}">delete</a></span>
            {% endif %}
            {% if not comment.isVotedBy() %}
                <span><a href="{{ url(['for':'users.voteForComment','comment':comment.id,'YesOrNo':'no']) }}">反对</a></span>
                <span><a href="{{ url(['for':'users.voteForComment','comment':comment.id,'YesOrNo':'yes']) }}">赞成</a></span>
            {% endif %}
            <span>yes：({{ comment.countVotes()['support'] }})</span>--<span>no：({{ comment.countVotes()['deny'] }})</span>
        </div>
        <div>
            {{comment.content|nl2br}}
        </div>
    </li>
    {% endfor %}
</ul>