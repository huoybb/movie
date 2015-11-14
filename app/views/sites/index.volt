{% extends "index.volt" %}

{% block title %}
    我的链接列表
{% endblock %}
{% block content %}
    <div class="container">
        <h1>链接汇总<span class="badge">{{ page.total_items }}</span></h1>
        <p>所有链接按照站点分类如下：</p>
        <div class="row">
            <nav>
                <ul class="pager">
                    <li class="previous"><a href="{{ url.get(['for':'sites.index','page':page.before]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                    <li class="next"><a href="{{ url.get(['for':'sites.index','page':page.next]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                </ul>
            </nav>
            <table class="table table-hover">
                <tr>
                    <th>#</th>
                    <th>网站</th>
                    <th>格式</th>
                    <th>计数</th>
                    <th>上传时间</th>
                    <th colspan="2"><div align="center">操作</div></th>
                </tr>
                {% for site in page.items %}
                    <tr>
                        <td>{{site.id}}</td>
                        <td><span><a href="{{ url(['for':'sites.show','site':site.id]) }}">{{ site.name }}</a></span></td>
                        <td>{{site.format}}</td>
                        <td>{{ site.linkCounts }}</td>
                        <td>{{ carbon.parse(site.created_at).diffForHumans() }}</td>
                        <td><span><a href="{{ url(['for':'sites.edit','site':site.id]) }}" ><div align="center">修改</div></a></span></td>
                        <td><span><a href="#" ><div align="center">删除</div></a></span></td>
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