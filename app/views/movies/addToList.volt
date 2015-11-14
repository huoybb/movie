{% extends "index.volt" %}
{% block title %}
    {{ movie.title }}
{% endblock %}

{% block content %}
    <div class="container">
        <div class="col-md-10">
            <h1>将 <a href="{{ url(['for':'movies.show','movie':movie.id]) }}">{{ movie.title }}</a>:添加到影单</h1>
            {% if relatedLists.count() %}
            <div>包含本影片的影单</div>
            <table class="table table-hover">
                <tr>
                    <td>#</td>
                    <td>影单名称</td>
                    <td>描述</td>
                    <td>操作</td>
                </tr>
                {% for row in relatedLists %}
                    <tr>
                        <td>{{row.id}}</td>
                        <td><a href="{{ url(['for':'lists.show','list':row.id]) }}">{{ row.name }}</a></td>
                        <td><span>{{ row.description }}</span></td>
                        <td><a href="{{ url(['for':'movies.deleteFromList','movie':movie.id,'list':row.id]) }}">去除</a></td>
                    </tr>
                {% endfor %}
            </table>
            {% endif %}

            <div>选择已有影单</div>
            <table class="table table-hover">
                <tr>
                    <td>#</td>
                    <td>影单名称</td>
                    <td>描述</td>
                    <td>操作</td>
                </tr>
                {% for row in lists %}
                    <tr>
                        <td>{{row.id}}</td>
                        <td><a href="{{ url(['for':'lists.show','list':row.id]) }}">{{ row.name }}</a></td>
                        <td><span>{{ row.description }}</span></td>
                        <td><a href="{{ url(['for':'movies.addToExitedList','movie':movie.id,'list':row.id]) }}">添加</a></td>
                    </tr>
                {% endfor %}
            </table>


        </div>
        <div class="col-md-2">
            <h2>创建新影单</h2>
            <div class="row">
                {{ form(listForm.Url, "method": "post","id":"list-form") }}
                <!--content Form Input-->
                <div class="form-group">
                    <label for="name">名称：</label>
                    {{ listForm.render('name') }}

                </div>
                <div class="form-group">
                    <label for="description">描述：</label>
                    {{ listForm.render('description',['rows':2,'class':'form-control']) }}

                </div>
                <!--Comment Form Submit Button-->
                <div class="form-group">
                    {{ listForm.render('Add List',['class':'btn btn-primary form-control']) }}
                </div>
                {{ endform() }}
            </div>

        </div>
    </div>

{% endblock %}
