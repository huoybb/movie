{% extends "index.volt" %}
{% block title %}
    {{ movie.title }}
{% endblock %}

{% block content %}
    {% include 'movies/partials/delete.volt' %}

{% endblock %}

{% block footer %}
    <div class="container">
        <div class="row">
            <?php echo xdebug_time_index();?>
        </div>
    </div>
{% endblock %}