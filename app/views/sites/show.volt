{% extends "index.volt" %}

{% block title %}
    {{ site.name }}--链接列表
{% endblock %}
{% block content %}
    <div class="container">

        <h1>网站：{{ site.name }}<span class="badge">{{ page.total_items }}</span></h1>
        <p>网址格式：{{ site.format }}</p>
        <P>操作：<a href="{{ url(['for':'sites.edit','site':site.id]) }}">修改</a> <a href="#">删除</a></P>
        {% if page.last > page.first %}
            <nav>
                <ul class="pager">
                    <li class="previous"><a href="{{ url.get(['for':'sites.show.page','site':site.id,'page':page.before]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                    <li class="next"><a href="{{ url.get(['for':'sites.show.page','site':site.id,'page':page.next]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                </ul>
            </nav>
        {% endif %}

        {% if page.total_items > 0 %}
            <div class="row">
                <table class="table table-hover">
                    <tr>
                        <th>#</th>
                        <th>电影</th>
                        <th>链接</th>
                        <th>上传时间</th>
                        <th colspan="2"><div align="center">操作</div></th>
                    </tr>
                    {% for link in page.items %}
                        <tr>
                            <td>{{link.id}}</td>
                            <td><span><a href="{{ url(['for':'movies.show','movie':link.movie_id]) }}">{{ link.title }}</a></span></td>
                            <td><span><a href="{{link.url}}" target="_blank">链接</a></span></td>
                            <td>{{ carbon.parse(link.created_at).diffForHumans() }}</td>
                            <td><span><a href="/links/{{link.id}}/edit" ><div align="center">修改</div></a></span></td>
                            <td><span><a href="/links/{{link.id}}/delete" ><div align="center">删除</div></a></span></td>
                        </tr>
                    {% endfor %}
                </table>
            </div>
        {% endif %}

        <div class="row">
            {% include 'movies/partials/commentlist.volt' %}
            {% include'movies/partials/commentform.volt' %}
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