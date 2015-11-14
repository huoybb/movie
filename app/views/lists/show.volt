{% extends "index.volt" %}

{% block title %}
    我的影单:{{ list.name }}
{% endblock %}
{% block content %}


    <div class="container">
        <p>{{ flash.output() }}</p>

        <h1>影单：{{ list.name }}</h1>


        <div class="row">
            <div class="col-md-10">
                {% include 'movies/partials/movielist2.volt' %}
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