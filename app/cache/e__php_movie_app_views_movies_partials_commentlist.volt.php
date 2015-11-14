<hr>

<h2>Comments:</h2>
<ul>
    <?php foreach ($comments as $comment) { ?>
    <li>
        <div>
            <span>
                by <a href="<?php echo $this->url->get(array('for' => 'users.showComments', 'user' => $comment->user()->id)); ?>"> <?php echo $comment->user()->name; ?></a>
            </span>
            --
            <span>
                at: <?php echo $comment->updated_at->diffForHumans(); ?>
            </span>
            <?php if ($this->auth->has($comment)) { ?>
            <span><a href="<?php echo $comment->getEditURL(); ?>">edit</a></span>
            <span><a href="<?php echo $comment->getDeleteURL(); ?>">delete</a></span>
            <?php } ?>
            <?php if (!$comment->isVotedBy()) { ?>
                <span><a href="<?php echo $this->url->get(array('for' => 'users.voteForComment', 'comment' => $comment->id, 'YesOrNo' => 'no')); ?>">反对</a></span>
                <span><a href="<?php echo $this->url->get(array('for' => 'users.voteForComment', 'comment' => $comment->id, 'YesOrNo' => 'yes')); ?>">赞成</a></span>
            <?php } ?>
            <span>yes：(<?php echo $comment->countVotes()['support']; ?>)</span>--<span>no：(<?php echo $comment->countVotes()['deny']; ?>)</span>
        </div>
        <div>
            <?php echo nl2br($comment->content); ?>
        </div>
    </li>
    <?php } ?>
</ul>