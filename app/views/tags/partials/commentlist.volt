<hr>

<h2>Comments:</h2>
<ul>
    {% for comment in comments %}
    <li>
        <div> <span>by <a href="{{ url(['for':'users.showComments','user':comment.user().id]) }}">{{ comment.user().name }}</a></span>--<span>at: {{ comment.updated_at.diffForHumans() }}</span>
            <span><a href="http://myphalcon2/tags/{{ mytag.id }}/comments/{{ comment.id }}/edit">edit</a></span>
            <span><a href="http://myphalcon2/tags/{{ mytag.id }}/comments/{{ comment.id }}/delete">delete</a></span>
        </div>
        <div>
            {{comment.content|nl2br}}
        </div>
    </li>
    {% endfor %}
</ul>