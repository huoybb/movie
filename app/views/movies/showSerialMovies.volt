{% extends "index.volt" %}

{% block title %}
    季数修改-{{ movie.title }}
{% endblock %}
{% block content %}


    <div class="container">
        <h1>季数修改</h1>

        <p>{{ flash.output() }}</p>
        <div><span class="label label-primary">共计<?php echo count($movies);?>部电影</span></div>


        <table class="table table-hover">
            <tr>
                <th>#</th>
                <th>片名</th>
                <th>Season #</th>
                <th>上传时间</th>
                <th>操作</th>
                <th>操作</th>
            </tr>
            {% for row in movies %}
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
{% endblock %}

{% block footer %}
    <div class="container">
        <div class="row">
            <?php echo xdebug_time_index();?>
        </div>
    </div>
{% endblock %}