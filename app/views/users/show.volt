{% extends "index.volt" %}

{% block title %}
    用户：{{ user.name }}
{% endblock %}
{% block content %}


    <div class="container">
        <h1>用户：{{ user.name }}</h1>

        <p>标签创建日期：{{ user.created_at.diffForHumans() }}</p>
        <P>操作：<a href="{{ url(['for':'users.edit','user':user.id]) }}">修改</a> <a href="#">删除</a></P>

        {% for key,count in user.getStatistics() %}
            <p><a href="http://myphalcon2/users/{{ user.id }}/{{ key }}">{{ key }}</a>:{{ count }}</p>

        {% endfor %}


    </div>
{% endblock %}

{% block footer %}
    <div class="container">
        <div class="row">
            <?php echo xdebug_time_index();?>
        </div>
    </div>
{% endblock %}