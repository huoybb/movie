<div class="row">
    <h2>添加相关链接</h2>
    <?php $addLinkFormUrl = "movies/".$movie->id."/addlink" ?>

    {{ form(addLinkFormUrl, "method": "post") }}

    {{ hidden_field("movieId", "value": movie.id) }}

    <!--content Form Input-->
    <div class="form-group">
        {{ text_field("url",'class':'form-control') }}

    </div>
    <!--Comment Form Submit Button-->
    <div class="form-group">
        {{ submit_button('添加','class':'btn btn-primary form-control') }}
    </div>
    {{ endform() }}

</div>