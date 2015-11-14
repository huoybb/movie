{% extends "index.volt" %}

{% block title %}
    用户：{{ user.name }}
{% endblock %}
{% block content %}


    <div class="container">
        <h1>用户：<a href="{{ url(['for':'users.show','user':user.id]) }}">{{ user.name }}</a></h1>

        <p>标签创建日期：{{ user.created_at.diffForHumans() }}</p>
        <P>操作：<a href="#">修改</a> <a href="#">删除</a></P>

        <ul>
            {% for comment in user.comments() %}
                <li>
                    <div> <span>for <a href="#">{{ comment.commentable().getClassName() }}</a>:<a href="#"> {{ comment.commentable().toString() }}</a></span>--<span>at: {{ comment.updated_at.diffForHumans() }}</span>

                    </div>
                    <div>
                        {{comment.content|nl2br}}
                    </div>
                </li>
            {% endfor %}
        </ul>


    </div>
{% endblock %}

{% block footer %}
    <div class="container">
        <div class="row">
            <?php echo xdebug_time_index();?>
        </div>
    </div>
{% endblock %}