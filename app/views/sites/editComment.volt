{% extends "index.volt" %}
{% block title %}
    评论：{{ site.name }}
{% endblock %}

{% block content %}
    <div class="container">
        {% include 'movies/partials/commentform.volt' %}
    </div>
{% endblock %}