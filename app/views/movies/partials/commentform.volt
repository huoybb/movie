<hr/>
{{ form(form.Url, "method": "post") }}
<!--content Form Input-->
<div class="form-group">
    <p>{{ flash.output() }}</p>
    <label for="content">请在下面框中输入你的评论：</label>
    {{ form.render('content',['rows':6,'class':'form-control']) }}

</div>
<!--Comment Form Submit Button-->
<div class="form-group">
    {{ form.render('Add Comment',['class':'btn btn-primary form-control']) }}
</div>
{{ endform() }}