{% extends "index.volt" %}

{% block title %}
    我的用户列表
{% endblock %}
{% block content %}
    <div class="container">
        <h1>用户汇总<span class="badge">{{ page.total_items }}</span></h1>
        <p>所有注册的用户如下所示：</p>
        <div class="row">
        {% if page.total_items > page.limit %}
            <nav>
                <ul class="pager">
                    <li class="previous"><a href="{{ url.get(['for':'users.index','page':page.before]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                    <li class="next"><a href="{{ url.get(['for':'users.index','page':page.next]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                </ul>
            </nav>
        {% endif %}
            <table class="table table-hover">
                <tr>
                    <th>#</th>
                    <th>用户</th>
                    <th>Email</th>
                    <th>注册时间</th>
                    <th>上一次登录时间</th>
                    <th colspan="2"><div align="center">操作</div></th>
                </tr>
                {% for user in page.items %}
                    <tr>
                        <td>{{user.id}}</td>
                        <td><span><a href="{{ url(['for':'users.show','user':user.id]) }}">{{ user.name }}</a></span></td>
                        <td>{{user.email}}</td>
                        <td>{{ user.created_at.diffForHumans() }}</td>
                        <td>{{ user.getLastLoginTime() }}</td>
                        <td><span><a href="{{ url(['for':'users.edit','user':user.id]) }}" ><div align="center">修改</div></a></span></td>
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