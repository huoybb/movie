{% extends "index.volt" %}

{% block title %}
    我的电影列表
{% endblock %}
{% block content %}


    <div class="container">
        <h1>电影列表</h1>

        <p>{{ flash.output() }}</p>
        <div><span class="label label-primary">共计{{ page.total_items }}部电影</span>--<span class="label label-primary">第{{ page.current }}页/总{{ page.total_pages }}页</span></div>
        {% if page.total_pages %}
            <nav>
                <ul class="pager">
                    <li class="previous"><a href="{{ url.get(['for':'movies.search.page','page':page.before,'search':search]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                    <li class="next"><a href="{{ url.get(['for':'movies.search.page','page':page.next,'search':search]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                </ul>
            </nav>
        {% endif %}

        {% include 'movies/partials/movielist3.volt' %}

        {% if page.total_pages %}
            <nav>
                <ul class="pager">
                    <li class="previous"><a href="{{ url.get(['for':'movies.search.page','page':page.before,'search':search]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                    <li class="next"><a href="{{ url.get(['for':'movies.search.page','page':page.next,'search':search]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                </ul>
            </nav>
        {% endif %}



    </div>
{% endblock %}

{% block footer %}
    <div class="container">
        <div class="row">
            <?php echo xdebug_time_index();?>
        </div>
    </div>
{% endblock %}