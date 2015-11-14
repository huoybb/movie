{% extends "index.volt" %}

{% block title %}
    标签：{{ mytag.name }}
{% endblock %}
{% block content %}


    <div class="container">
        <div class="col-md-10">
            <div class="row">
                <h1>标签：{{ mytag.name }} <span class="badge">{{ page.total_items }}</span></h1>

                <p>标签创建日期：{{ mytag.created_at.diffForHumans() }}</p>
                <P>操作：<a href="{{ url.get(['for':'tags.edit','tag':mytag.id]) }}">修改</a> <a href="#">删除</a></P>

                <h2>电影</h2>
                <div><span class="label label-primary">共计{{ page.total_items }}部电影</span>--<span class="label label-primary">第{{ page.current }}页/总{{ page.total_pages }}页</span></div>
                {% if page.total_pages > 1 %}
                    <nav>
                        <ul class="pager">
                            <li class="previous"><a href="{{ url.get(['for':'tags.show.page','page':page.before,'tag':mytag.id]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                            <li class="next"><a href="{{ url.get(['for':'tags.show.page','page':page.next,'tag':mytag.id]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                        </ul>
                    </nav>
                {% endif %}
                {% include 'movies/partials/movielist3.volt' %}
                {% if page.total_pages > 1 %}
                    <nav>
                        <ul class="pager">
                            <li class="previous"><a href="{{ url.get(['for':'tags.show.page','page':page.before,'tag':mytag.id]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                            <li class="next"><a href="{{ url.get(['for':'tags.show.page','page':page.next,'tag':mytag.id]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                        </ul>
                    </nav>
                {% endif %}
            </div>


            {% include 'tags/partials/commentlist.volt' %}
            {% include'tags/partials/commentform.volt' %}
        </div>
        <div class="col-md-2">
            <div class="row">
                <h2>谁在用</h2>
                <div>
                    {% for user in mytag.users() %}
                        <span><a href="{{ url(['for':'users.showTags','user':user.id]) }}">{{ user.name }}</a></span>
                    {% endfor %}
                </div>
            </div>
            <div class="row">
                <h2>全部标签</h2>
                <div>
                    {% for mytag in registry.allTags %}
                        <span><a href="{{ url(['for':'tags.show','tag':mytag.id]) }}">{{ mytag.name }}</a>({{ mytag.movieCounts }})</span>
                    {% endfor %}
                </div>
            </div>
        </div>



    </div>
{% endblock %}

{% block footer %}
    <div class="container">
        <div class="row">
            <?php echo xdebug_time_index();?>
        </div>
    </div>
{% endblock %}