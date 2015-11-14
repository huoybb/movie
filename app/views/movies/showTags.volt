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
                {% if movie.tags() %}
                    <div class="row">
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>标签</th>
                            <th>创建人</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        {% for mytag in movie.tags() %}
                        <tr>
                            <td>{{mytag.id}}</td>
                            <td><span><a href="http://myphalcon2/tags/{{ mytag.id }}">{{ mytag.name }}</a></span></td>
                            <td><a href="{{ url(['for':'users.showTags','user':mytag.user_id]) }}">{{ mytag.userName }}</a></td>
                            <td>{{ carbon.parse(mytag.created_at).diffForHumans() }}</td>
                            {% if auth.has(mytag) %}
                                <td><span><a href="/movies/{{movie.id}}/taggable/{{mytag.taggable_id}}/delete" >删除</a></span></td>
                            {% else %}
                                <td>删除</td>
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