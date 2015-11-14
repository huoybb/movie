{% extends "index.volt" %}
{% block title %}
    {{ movie.title }}
{% endblock %}

{% block content %}
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h2>{{ movie.title }}</h2>
                <div class="col-md-4">
                    <a href="http://myphalcon2/movies/{{ movie.id }}"><img src="{{ url.getBaseUri() }}{{ movie.poster }}" alt="电影海报" width="250" height="370" /></a>
                </div>

                <div class="col-md-8">
                {% if movie.links() %}
                    <div class="row">
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>链接</th>
                            <th>上传者</th>
                            <th>上传时间</th>
                            <th>网站</th>
                            <th><div align="center">操作</div></th>
                        </tr>
                        {% for link in movie.links() %}
                        <tr>
                            <td>{{link.id}}</td>
                            <td><span><a href="{{link.url}}" target="_blank">链接</a></span></td>
                            <td><a href="{{ url(['for':'users.showLinks','user':link.user().id]) }}">{{link.user().name}}</a></td>
                            <td>{{ link.created_at.diffForHumans() }}</td>
                            <td><span><a href="{{url(['for':'sites.show','site':link.site().id])}}">{{link.site().name}}</a></span></td>
                            {% if auth.has(link) %}
                                <td><a href="/movies/{{movie.id}}/links/{{link.id}}/delete" ><div align="center">删除</div></a></td>
                            {% else %}
                                <td><div align="center">删除</div></td>
                            {% endif %}
                        </tr>
                        {% endfor %}
                    </table>
                    </div>
                {% endif %}
                </div>
            </div>
            <div class="col-md-2">
                {% include 'movies/partials/_addlink_form.volt' %}
                {% include 'movies/partials/addTagForm.volt' %}
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