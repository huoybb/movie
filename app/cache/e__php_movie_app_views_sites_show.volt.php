<?php echo $this->getContent(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>
    <?php echo $site->name; ?>--链接列表
</title>
        <link rel="stylesheet" href="<?php echo $this->url->getBaseUri(); ?>css/app.css">
        <script language="JavaScript" type="text/javascript" src="<?php echo $this->url->getBaseUri(); ?>js/jquery-2.1.4.min.js"></script>
        <script language="JavaScript" type="text/javascript" src="<?php echo $this->url->getBaseUri(); ?>js/keymaster.js"></script>
        <script language="JavaScript" type="text/javascript" src="<?php echo $this->url->getBaseUri(); ?>js/my.js"></script>
	</head>
	<body>
        <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">赵兵的电影</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">

                <?php foreach (array('movies' => '电影', 'tags' => '标签', 'sites' => '链接', 'tvserials' => '电视剧', 'calendar/MyLatest' => '更新日历', 'calendar/MyWatchList' => '追看列表', 'users' => '用户') as $key => $value) { ?>
                <li><a href="http://myphalcon2/<?php echo $key; ?>"><?php echo $value; ?></a></li>
                <?php } ?>

            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <form id="search-form" class="navbar-form navbar-left" role="search" action="<?php echo $this->url->get(array('for' => 'movies.search')); ?>" method="post">
                        <div class="form-group">
                            <?php $search = isset($search) ?$search: null?>
                            <?php echo $this->tag->textField(array('search', 'class' => 'form-control', 'placeholder' => 'Search', 'value' => $search)); ?>
                        </div>
                        <button type="submit" class="btn btn-default">查询</button>
                    </form>
                </li>

            </ul>
        </div>
    </div>
</nav>
        
    <div class="container">

        <h1>网站：<?php echo $site->name; ?><span class="badge"><?php echo $page->total_items; ?></span></h1>
        <p>网址格式：<?php echo $site->format; ?></p>
        <P>操作：<a href="<?php echo $this->url->get(array('for' => 'sites.edit', 'site' => $site->id)); ?>">修改</a> <a href="#">删除</a></P>
        <?php if ($page->last > $page->first) { ?>
            <nav>
                <ul class="pager">
                    <li class="previous"><a href="<?php echo $this->url->get(array('for' => 'sites.show.page', 'site' => $site->id, 'page' => $page->before)); ?>"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                    <li class="next"><a href="<?php echo $this->url->get(array('for' => 'sites.show.page', 'site' => $site->id, 'page' => $page->next)); ?>">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                </ul>
            </nav>
        <?php } ?>

        <?php if ($page->total_items > 0) { ?>
            <div class="row">
                <table class="table table-hover">
                    <tr>
                        <th>#</th>
                        <th>电影</th>
                        <th>链接</th>
                        <th>上传时间</th>
                        <th colspan="2"><div align="center">操作</div></th>
                    </tr>
                    <?php foreach ($page->items as $link) { ?>
                        <tr>
                            <td><?php echo $link->id; ?></td>
                            <td><span><a href="<?php echo $this->url->get(array('for' => 'movies.show', 'movie' => $link->movie_id)); ?>"><?php echo $link->title; ?></a></span></td>
                            <td><span><a href="<?php echo $link->url; ?>" target="_blank">链接</a></span></td>
                            <td><?php echo $this->carbon->parse($link->created_at)->diffForHumans(); ?></td>
                            <td><span><a href="/links/<?php echo $link->id; ?>/edit" ><div align="center">修改</div></a></span></td>
                            <td><span><a href="/links/<?php echo $link->id; ?>/delete" ><div align="center">删除</div></a></span></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        <?php } ?>

        <div class="row">
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
        </div>

    </div>

        
    <div class="container">
        <div class="row">
            <?php echo xdebug_time_index();?>
        </div>
    </div>

    </body>
</html>