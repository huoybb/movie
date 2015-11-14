<div class="row">
    <h2>添加相关链接</h2>
    <?php $addLinkFormUrl = "movies/".$movie->id."/addlink" ?>

    <?php echo $this->tag->form(array($addLinkFormUrl, 'method' => 'post')); ?>

    <?php echo $this->tag->hiddenField(array('movieId', 'value' => $movie->id)); ?>

    <!--content Form Input-->
    <div class="form-group">
        <?php echo $this->tag->textField(array('url', 'class' => 'form-control')); ?>

    </div>
    <!--Comment Form Submit Button-->
    <div class="form-group">
        <?php echo $this->tag->submitButton(array('添加', 'class' => 'btn btn-primary form-control')); ?>
    </div>
    <?php echo $this->tag->endform(); ?>

</div>