{% extends "index.volt" %}

{% block title %}
    电视剧列表
{% endblock %}
{% block content %}


    <div class="container">
        <p>{{ flash.output() }}</p>

        <h1>电视剧列表</h1>


        <div class="row">
            <div class="col-md-10">
                <div><span class="label label-primary">共计{{ page.total_items }}部电影</span>--<span class="label label-primary">第{{ page.current }}页/总{{ page.total_pages }}页</span></div>
                {% if page.total_pages %}
                    <nav>
                        <ul class="pager">
                            <li class="previous"><a href="{{ url.get(['for':'tvserials.index.page','page':page.before]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                            <li class="next"><a href="{{ url.get(['for':'tvserials.index.page','page':page.next]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                        </ul>
                    </nav>
                {% endif %}
                <table class="table table-hover">
                    <tr>
                        <td>#</td>
                        <td>电视剧名</td>
                        <td>季数</td>
                        <td>开播</td>
                        <td>停播</td>
                        <td>上传时间</td>
                    </tr>
                    {% for tv in page.items %}
                        <tr>
                            <td>{{tv.tvserials.id}}</td>
                            <td><span>{{ link_to(['for':'tvserials.show','tvserial':tv.tvserials.id],tv.tvserials.title) }}</span></td>
                            <td><span>{{ tv.counts }}</span></td>
                            <td><span>{{ tv.tvserials.start }}</span></td>
                            <td><span>{{ tv.tvserials.end }}</span></td>
                            <td> <span>{{ tv.tvserials.created_at.diffForHumans() }}</span></td>
                        </tr>
                    {% endfor %}
                </table>
                {% if page.total_pages %}
                    <nav>
                        <ul class="pager">
                            <li class="previous"><a href="{{ url.get(['for':'tvserials.index.page','page':page.before]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                            <li class="next"><a href="{{ url.get(['for':'tvserials.index.page','page':page.next]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                        </ul>
                    </nav>
                {% endif %}
            </div>
            <div class="col-md-2">
                <h2>标签</h2>
                {% for mytag in registry.allTags %}
                    <span><a href="{{ url(['for':'tags.show','tag':mytag.id]) }}">{{ mytag.name }}</a>({{ mytag.movieCounts }})</span>
                {% endfor %}
            </div>

        </div>



    </div>
{% endblock %}
