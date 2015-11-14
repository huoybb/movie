<div class="row">
    <h2>标签</h2>
    <?php $Url = "movies/".$movie->id."/addTag" ?>

    {{ form(Url, "method": "post") }}

    {{ hidden_field("movieId", "value": movie.id) }}

    <!--content Form Input-->
    <div class="form-group">
        {{ text_field("tagName",'class':'form-control') }}

    </div>

    <!--Comment Form Submit Button-->
    <div class="form-group">
        {{ submit_button('添加','class':'btn btn-primary form-control') }}
    </div>

    <div class="form-group">
        {#<?php foreach($this->allTags as $mytag){?>#}
        {% for mytag in registry.allTags %}
        <span><a href="{{ url(['for':'tags.show','tag':mytag.id]) }}">{{ mytag.name }}</a>({{ mytag.movieCounts }})</span>
        {% endfor %}
        {#<?php }?>#}
    </div>
    {{ endform() }}

</div>