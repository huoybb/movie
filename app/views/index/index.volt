{% extends "index.volt" %}

{% block title %}
    我的电影列表
{% endblock %}
{% block content %}
    <div class="container">
        <h1>恭喜你到这里了！</h1>

        <p>原来volt也支持模板继承，很好的东西呀！</p>
        <p>估计速度也能够更快一些吧！</p>

        <ul>
            {% for movie in movies %}
            <li><a href="movies/{{ movie.id }}">{{ movie.title }}</a></li>
            {% endfor %}
        </ul>

        <h1>也许用这个来重构图书系统能够更快一些吧！将来的方向吧！</h1>
    </div>
{% endblock %}

{% block footer %}
    <div class="container">
        <div class="row">
            <?php echo xdebug_time_index();?>
        </div>
    </div>
{% endblock %}