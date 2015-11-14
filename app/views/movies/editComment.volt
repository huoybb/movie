{% extends "index.volt" %}
{% block title %}
    评论：{{ movie.title }}
{% endblock %}

{% block content %}
    <div class="container">
        {% include 'movies/partials/commentform.volt' %}
    </div>
{% endblock %}