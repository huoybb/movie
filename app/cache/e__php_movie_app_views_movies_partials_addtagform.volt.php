<div class="row">
    <h2>标签</h2>
    <?php $Url = "movies/".$movie->id."/addTag" ?>

    <?php echo $this->tag->form(array($Url, 'method' => 'post')); ?>

    <?php echo $this->tag->hiddenField(array('movieId', 'value' => $movie->id)); ?>

    <!--content Form Input-->
    <div class="form-group">
        <?php echo $this->tag->textField(array('tagName', 'class' => 'form-control')); ?>

    </div>

    <!--Comment Form Submit Button-->
    <div class="form-group">
        <?php echo $this->tag->submitButton(array('添加', 'class' => 'btn btn-primary form-control')); ?>
    </div>

    <div class="form-group">
        
        <?php foreach ($this->registry->allTags as $mytag) { ?>
        <span><a href="<?php echo $this->url->get(array('for' => 'tags.show', 'tag' => $mytag->id)); ?>"><?php echo $mytag->name; ?></a>(<?php echo $mytag->movieCounts; ?>)</span>
        <?php } ?>
        
    </div>
    <?php echo $this->tag->endform(); ?>

</div>