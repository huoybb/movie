<hr>

<h2>分集评论:</h2>
<ul>
    <?php foreach ($movie->getEpisodesComments() as $row) { ?>
        <li>
            <div> <span>by <a href="<?php echo $this->url->get(array('for' => 'users.showComments', 'user' => $row->comments->user()->id)); ?>"> <?php echo $row->comments->user()->name; ?></a></span>--<span>at: <?php echo $row->comments->updated_at->diffForHumans(); ?></span>
                <?php if ($this->auth->has($row->comments)) { ?>
                    <span><a href="<?php echo $row->comments->getEditURL(); ?>">edit</a></span>
                    <span><a href="<?php echo $row->comments->getDeleteURL(); ?>">delete</a></span>
                <?php } ?>
                --<span>评：<a href="<?php echo $this->url->get(array('for' => 'movies.showEpisode', 'movie' => $row->episodes->getMovie()->id, 'episode' => $row->episodes->id)); ?>">第<?php echo $row->episodes->num; ?>集：<?php echo $row->episodes->title; ?></a></span>
            </div>
            <div>
                <?php echo nl2br($row->comments->content); ?>
            </div>
        </li>
    <?php } ?>
</ul>