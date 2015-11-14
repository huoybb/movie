<div class="row">
    <h2>添加相关链接</h2>
    <?php $addLinkFormUrl = "tvserials/".$TV->id."/addlink" ?>
    {{ form(addLinkFormUrl, "method": "post") }}

    {#{{ hidden_field("TVId", "value": TV.id) }}#}

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