{% extends "index.volt" %}
{% block title %}
    {{ movie.title }}
{% endblock %}

{% block content %}
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4>{{ movie.title }}</h4>
                <div class="row">
                    <div class="col-md-12">
                        <h2>S{{ "%'.02d"|format(episode.getSerial().serial_num) }}E{{"%'.02d"|format(episode.num) }}：{{ episode.title }}</h2>
                        <div>发布时间：{{ episode.date.format('l M d, Y') }}</div>
                        <div>链接：
                            <a href="{{ episode.getDoubanLink() }}" target="_blank">豆瓣链接</a>
                            {% if movie.getFirstTTMJLink() %}
                                <a href="{{ movie.getFirstTTMJLink().url }}" target="_blank">天天美剧</a>
                            {% endif %}
                            {% if episode.getSerial().getTVSerial().getFirstKATLink() %}
                                <a href="{{ episode.getSerial().getTVSerial().getFirstKATLink().url }}" target="_blank">KAT</a>
                            {% endif %}
                        </div>
                        <div>操作：
                            {% if movie.hasWatchList() and movie.getWatchList().currentEpisode_id is not episode.id %}
                                <span><a href="{{ url(['for':'movies.updateWatchList','movie':movie.id,'episode':episode.id,'watchList':movie.getWatchList().id]) }}">看到此处</a></span>
                            {% endif %}

                        </div>

                        <div class="row">
                            <nav>
                                <ul class="pager">
                                    <li class="previous"><a href="{{ url.get(['for':'movies.showEpisode','movie':movie.id,'episode':episode.getPrevious().id]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                                    <li class="next"><a href="{{ url.get(['for':'movies.showEpisode','movie':movie.id,'episode':episode.getNext().id]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                                </ul>
                            </nav>
                        </div>

                        {% if episode.links().count() %}
                            <h2>视频链接</h2>
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
                                    {% for link in episode.links() %}
                                        <tr>
                                            <td>{{link.id}}</td>
                                            <td><span><a href="{{link.url}}" target="_blank">链接</a></span></td>
                                            <td><a href="{{ url(['for':'users.showLinks','user':link.user().id]) }}">{{link.user().name}}</a></td>
                                            <td>{{ link.created_at.diffForHumans() }}</td>
                                            <td><span><a href="{{url(['for':'sites.show','site':link.site().id])}}">{{link.site().name}}</a></span></td>
                                            {% if auth.has(link) %}
                                                <td><a href="{{ url(['for':'movies.deleteLinkFromEpisode','movie':movie.id,'episode':episode.id,'link':link.id]) }}" ><div align="center">删除</div></a></td>
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
                <div class="row">
                    {% include 'movies/partials/commentlist.volt' %}
                    {% include'movies/partials/commentform.volt' %}
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-5">
                        <a href="http://myphalcon2/movies/{{ movie.id }}"><img src="{{ url.getBaseUri() }}{{ movie.poster }}" alt="电影海报" width="100" height="148" /></a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ url(['for':'movies.show','movie':movie.id]) }}">{{ movie.title }}</a>
                    </div>
                </div>
                {% include 'movies/partials/_addlink_form_episode.volt' %}
                {% include 'movies/partials/episodeslist.volt' %}

            </div>


        </div>

    </div>

{% endblock %}
