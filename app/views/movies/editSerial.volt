{% extends "index.volt" %}
{% block title %}
    季数修改：{{ movie.title }}
{% endblock %}

{% block content %}
    <div class="container">
        <h1>季数修改：{{ movie.title }}</h1>
        <p>{{ flash.output() }}</p>
        {{ form("method": "post") }}

        {% for item in form.fields %}
            <div class="form-group">
                {{ item }}:{{ form.render(item,['class':'form-control']) }}<br/>
            </div>
        {% endfor %}

        <div class="form-group">
            {{ form.render('修改',['class':'btn btn-primary form-control']) }}
        </div>
        {{ endform() }}

    </div>

{% endblock %}

{% block footer %}
    <div class="container">
        <div class="row">
            <?php echo xdebug_time_index();?>
        </div>
    </div>
{% endblock %}