{% extends "index.volt" %}
{% block title %}
    {{ movie.title }}
{% endblock %}

{% block content %}
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h2>{{ movie.title }}</h2>
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ url.getBaseUri() }}{{ movie.poster }}" alt="电影海报" width="250" height="370" />
                    </div>
                    <div class="col-md-8">
                        {% set list = ['director':'导演','screenwriter':'编剧','casts':'主演','other_names':'又名','IMDb_link':'IMDb链接','doubanid':'豆瓣链接','release_time':'上映日期','created_at':'收藏时间'] %}
                        {% for key,value in list %}
                            {% if movie.getHtml(key) %}
                                <div class="row">
                                    <div class="col-md-2" align="right"><span>{{value}}</span>:</div>
                                    <div class="col-md-10"><span>{{ movie.getHtml(key) }}</span></div>
                                </div>
                            {% endif %}
                        {% endfor %}
                        {% if movie.isSerialable() %}
                        <div class="row">
                            <div class="col-md-2" align="right">
                                <a href="{{ url(['for':'tvserials.show','tvserial':movie.getTVSerial().id]) }}"><span>季数</span></a>
                            </div>
                            <div class="col-md-10">
                                <select id="season">
                                    {% for row in movie.getSerialMovieList() %}
                                    <option value="{{ row.movie_id }}" {% if row.movie_id == movie.id %}selected="selected"{% endif %}>{{ row.year }}</option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        {% endif %}

                        <div class="row">
                            <div class="col-md-2" align="right"><span>操作</span>:</div>
                            <div class="col-md-10">
                                <span><a href="{{ url.getBaseUri() }}movies/{{ movie.id }}/updateInfoFromDouban">豆瓣同步</a></span>
                                <span><a href="{{ url.getBaseUri() }}movies/{{ movie.id }}/edit">修改本片</a></span>
                                <span><a href="{{ url.getBaseUri() }}movies/{{ movie.id }}/delete">删除本片</a></span>
                                <span><a href="{{ url(['for':'movies.addToList','movie':movie.id]) }}">加入影单</a></span>
                                {% if not movie.isSerialable() %}
                                    <span><a href="{{ url(['for':'movies.becomeSerial','movie':movie.id]) }}">变电视剧</a></span>
                                {% endif %}

                                {% if not movie.hasWatchList() OR movie.getWatchList().status is 'done' %}
                                    <span><a href="{{ url(['for':'movies.addToWatchList','movie':movie.id]) }}">追看此剧</a></span>
                                {% endif %}
                                {% if movie.hasWatchList() AND movie.getWatchList().status is not 'done' %}
                                    <span><a href="{{ url(['for':'movies.closeWatchList','movie':movie.id,'watchList':movie.getWatchList().id]) }}">结束追看</a></span>
                                {% endif %}

                            </div>
                        </div>
                        {% if movie.links().count() %}
                            <div class="row">
                                <div class="col-md-2" align="right"><span><a href="/movies/{{movie.id}}/links">视频链接</a></span>:</div>
                                <div class="col-md-10">
                                    {% for link in movie.links() %}
                                        <span><a href="{{link.url}}" target="_blank">{{link.site().name}}</a></span>
                                    {% endfor %}
                                </div>
                            </div>
                        {% endif %}
                        {% if movie.tags().count() %}
                            <div class="row">
                                <div class="col-md-2" align="right"><span><a href="/movies/{{movie.id}}/tags">标签链接</a></span>:</div>
                                <div class="col-md-10">
                                    {% for mytag in movie.tagsWithCounts() %}
                                        <span><a href="{{ url(['for':'tags.show','tag':mytag.id]) }}">{{mytag.name}}</a>({{ mytag.counts }})</span>
                                    {% endfor %}
                                </div>
                            </div>
                        {% endif %}

                        <div class="row">
                            <button type="button" class="btn btn-default btn-lg" id="favorite-button">
                                <span class="{% if auth.hasFavoredThis(movie) %}glyphicon glyphicon-heart{% else %}glyphicon glyphicon-heart-empty{% endif %} " aria-hidden="true" id="favorite"></span> 收藏
                            </button>
                        </div>
                        {% if movie.getUsersFavoriteThis().count() %}
                            <div class="row">
                                <div class="col-md-2">收藏者</div>
                                <div class="col-md-10">
                                    {% for user in movie.getUsersFavoriteThis() %}
                                        <span><a href="{{ url(['for':'users.show','user':user.id]) }}">{{ user.name }}</a></span>
                                    {% endfor %}
                                </div>
                            </div>
                        {% endif %}

                        <div class="row">
                            <div class="col-md-2">关键词</div>
                            <div class="col-md-10">{{ movie.keywords() }}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <nav>
                        <ul class="pager">
                            <li class="previous"><a href="{{ url.get(['for':'movies.show','movie':movie.getPrevious().id]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                            <li class="next"><a href="{{ url.get(['for':'movies.show','movie':movie.getNext().id]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                        </ul>
                    </nav>
                </div>
                <div class="row">
                    {% include 'movies/partials/commentlist.volt' %}

                    {% if movie.isSerialable() %}
                    {% include 'movies/partials/EpisodeCommentList.volt' %}
                    {% endif %}

                    {% include'movies/partials/commentform.volt' %}

                </div>

                <div class="row">
                    <h2>可能相关的电影</h2>
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>片名</th>
                            <th>上传时间</th>
                            <th>成为序列</th>
                        </tr>
                        {% for row in movie.getRelatedMovies() %}
                            {% if row.id != movie.id %}
                                <tr>
                                    <td>{{row.id}}</td>
                                    <td><span><a href="{{ url(['for':'movies.show','movie':row.id]) }}">{{ row.title }}</a></span></td>
                                    <td>{{ row.created_at.diffForHumans() }}</td>
                                    <td><a href="{{ url(['for':'movies.addSerialTo','movie':movie.id,'anotherMovie':row.id]) }}">addSerial</a></td>
                                </tr>
                            {% endif %}
                        {% endfor %}

                    </table>
                </div>


            </div>

            <div class="col-md-2">
                {% include 'movies/partials/_addlink_form.volt' %}
                {% if movie.isBeingWatched() %}
                    <div class="row">
                        <h2>Last Watched</h2>
                        <div class="col-md-5">
                            {{ movie.getWatchList().updated_at.format('l M d') }}
                        </div>
                        <div class="col-md-7">
                            S{{ "%'.02d"|format(movie.getSerial().serial_num) }}E{{"%'.02d"|format(movie.getLastWatchedEpisode().num) }} <br>
                            <a href="{{ url(['for':'movies.showEpisode','movie':movie.id,'episode':movie.getLastWatchedEpisode().id]) }}">{{ movie.getLastWatchedEpisode().title }}</a>
                        </div>
                    </div>
                    <div class="row">
                        <h2 >See Next</h2>
                        <div>
                            S{{ "%'.02d"|format(movie.getSerial().serial_num) }}E{{"%'.02d"|format(movie.getLastWatchedEpisode().getNext().num) }}:
                            <a href="{{ url(['for':'movies.showEpisode','movie':movie.id,'episode':movie.getLastWatchedEpisode().getNext().id]) }}">{{ movie.getLastWatchedEpisode().getNext().title }}</a>
                        </div>
                    </div>
                {% endif %}
                {% if movie.isSerialable() AND movie.getEpisodeNextOnTV() %}
                    <div class="row">
                        <h2>Next On TV</h2>
                        <div class="row">
                            <div class="col-md-5">
                                {{ movie.getEpisodeNextOnTV().date.format('l M d') }}
                            </div>
                            <div class="col-md-7">
                                S{{ "%'.02d"|format(movie.getSerial().serial_num) }}E{{"%'.02d"|format(movie.getEpisodeNextOnTV().num) }} <br>
                                <a href="{{ url(['for':'movies.showEpisode','movie':movie.id,'episode':movie.getEpisodeNextOnTV().id]) }}">{{ movie.getEpisodeNextOnTV().title }}</a>
                            </div>
                        </div>
                    </div>
                {% endif %}
                {% include 'movies/partials/episodeslist.volt' %}
                {% include 'movies/partials/addTagForm.volt' %}


                {% if movie.getRelatedLists().count() %}
                <div class="row">
                    <h2>相关影单列表</h2>
                    <ul>
                    {% for list in movie.getRelatedLists() %}
                        <li><a href="{{ url(['for':'lists.show','list':list.id]) }}">{{ list.name }}</a></li>
                    {% endfor %}
                    </ul>
                </div>
                {% endif %}



            </div>

        </div>


    </div>

{% endblock %}
