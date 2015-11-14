<hr/>
<?php echo $this->tag->form(array($form->Url, 'method' => 'post')); ?>
<!--content Form Input-->
<div class="form-group">
    <p><?php echo $this->flash->output(); ?></p>
    <label for="content">请在下面框中输入你的评论：</label>
    <?php echo $form->render('content', array('rows' => 6, 'class' => 'form-control')); ?>

</div>
<!--Comment Form Submit Button-->
<div class="form-group">
    <?php echo $form->render('Add Comment', array('class' => 'btn btn-primary form-control')); ?>
</div>
<?php echo $this->tag->endform(); ?>