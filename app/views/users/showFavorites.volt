{% extends "index.volt" %}

{% block title %}
    用户：{{ user.name }}
{% endblock %}
{% block content %}


    <div class="container">
        <h1>用户：<a href="{{ url(['for':'users.show','user':user.id]) }}">{{ user.name }}</a></h1>

        <p>创建日期：{{ user.created_at.diffForHumans() }}</p>
        <P>操作：<a href="#">修改</a> <a href="#">删除</a></P>

        <div class="row">
            {% if page.total_pages %}
                <nav>
                    <ul class="pager">
                        <li class="previous"><a href="{{ url.get(['for':'users.showFavorites','user':user.id,'page':page.before]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                        <li class="next"><a href="{{ url.get(['for':'users.showFavorites','user':user.id,'page':page.next]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                    </ul>
                </nav>
            {% endif %}
            <table class="table table-hover">
                <tr>
                    <th>#</th>
                    <th>电影</th>
                    <th>时间</th>
                    <th><div align="center">操作</div></th>
                </tr>
                {% for favorite in page.items %}
                    <tr>
                        <td>{{favorite.id}}</td>
                        <td><a href="{{ url(['for':'movies.show','movie':favorite.movie_id]) }}">{{favorite.movie_title}}</a></td>
                        <td>{{ carbon.parse(favorite.created_at).diffForHumans() }}</td>
                        {% if auth.has(favorite) %}
                            <td><span><a href="#" ><div align="center">删除</div></a></span></td>
                        {% else %}
                            <td>删除</td>
                        {% endif %}
                    </tr>
                {% endfor %}
            </table>
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