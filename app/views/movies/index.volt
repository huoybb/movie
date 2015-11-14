{% extends "index.volt" %}

{% block title %}
    我的电影列表
{% endblock %}
{% block content %}


    <div class="container">
        <p>{{ flash.output() }}</p>

        <h1>电影列表</h1>


        <div class="row">
            <div class="col-md-10">
                {% include 'movies/partials/movielist2.volt' %}
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

{% block footer %}
    <div class="container">
        <div class="row">
            <?php echo xdebug_time_index();?>
        </div>
    </div>
{% endblock %}