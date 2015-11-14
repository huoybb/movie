{% extends "index.volt" %}

{% block title %}
    电视剧：{{ TV.title }}
{% endblock %}
{% block content %}


    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h1>电视剧：{{ TV.title }}</h1>

                <p>{{ flash.output() }}</p>


                <div class="row">
                    <h2>季数汇总</h2>
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>片名</th>
                            <th>Season #</th>
                            <th>上传时间</th>
                            <th>操作</th>
                            <th>操作</th>
                        </tr>
                        {% for row in TV.getSerialListMovies() %}
                            <tr>
                                <td>{{row.id}}</td>
                                <td>{{ link_to(['for':'movies.show','movie':row.id],row.title) }}</td>
                                <td>{{ row.serial_num}}</td>
                                <td> <span>{{ row.created_at.diffForHumans() }}</span></td>
                                <td><a href="{{ url(['for':'movies.deleteSerial','movie':row.id]) }}">删除</a></td>
                                <td><a href="{{ url(['for':'movies.editSerial','movie':row.id,'serial':row.serial_id]) }}">修改</a></td>
                            </tr>
                        {% endfor %}
                    </table>
                </div>
                {% if TV.links().count() %}
                <div class="row">
                    <h2>相关链接</h2>
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>链接</th>
                            <th>上传时间</th>
                            <th>操作</th>
                        </tr>
                        {% for row in TV.links() %}
                            <tr>
                                <td>{{row.id}}</td>
                                <td><a href="{{ row.url }}" target="_blank">{{ row.site().name}}</a></td>
                                <td> <span>{{ row.created_at.diffForHumans() }}</span></td>
                                <td><a href="{{ url(['for':'tvserials.updateEpisodes','link':row.id,'tvserial':TV.id]) }}">更新每集信息</a></td>
                            </tr>
                        {% endfor %}
                    </table>
                </div>
                {% endif %}

                    <div class="row">
                        <h2>Episodes Info</h2>

                        <table class="table table-hover">
                            {% for serial in TV.getSerialList() %}

                            <tr>
                                <th colspan="3">Season {{ serial.serial_num }}</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>分集名称</th>
                                <th>上映时间</th>
                            </tr>
                            {% for episode in serial.getEpisodes() %}
                                <tr>
                                    <td>{{ episode.num }}</td>
                                    <td><a href="{{ url(['for':'movies.showEpisode','episode':episode.id,'movie':serial.movie_id]) }}">{{ episode.title }}</a></td>
                                    <td>{{ episode.date.format('l M d, Y') }}</td>
                                </tr>
                            {% endfor %}
                            {% endfor %}
                        </table>
                    </div>
            </div>
            <div class="col-md-2">
                {% include 'tvserial/partials/_addlink_form.volt' %}
            </div>
        </div>


    </div>
{% endblock %}
