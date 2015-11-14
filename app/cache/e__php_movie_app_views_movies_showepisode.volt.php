<?php echo $this->getContent(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>
    <?php echo $movie->title; ?>
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
        <div class="row">
            <div class="col-md-9">
                <h4><?php echo $movie->title; ?></h4>
                <div class="row">
                    <div class="col-md-12">
                        <h2>S<?php echo sprintf('%\'.02d', $episode->getSerial()->serial_num); ?>E<?php echo sprintf('%\'.02d', $episode->num); ?>：<?php echo $episode->title; ?></h2>
                        <div>发布时间：<?php echo $episode->date->format('l M d, Y'); ?></div>
                        <div>链接：
                            <a href="<?php echo $episode->getDoubanLink(); ?>" target="_blank">豆瓣链接</a>
                            <?php if ($movie->getFirstTTMJLink()) { ?>
                                <a href="<?php echo $movie->getFirstTTMJLink()->url; ?>" target="_blank">天天美剧</a>
                            <?php } ?>
                            <?php if ($episode->getSerial()->getTVSerial()->getFirstKATLink()) { ?>
                                <a href="<?php echo $episode->getSerial()->getTVSerial()->getFirstKATLink()->url; ?>" target="_blank">KAT</a>
                            <?php } ?>
                        </div>
                        <div>操作：
                            <?php if ($movie->hasWatchList() && $movie->getWatchList()->currentEpisode_id != $episode->id) { ?>
                                <span><a href="<?php echo $this->url->get(array('for' => 'movies.updateWatchList', 'movie' => $movie->id, 'episode' => $episode->id, 'watchList' => $movie->getWatchList()->id)); ?>">看到此处</a></span>
                            <?php } ?>

                        </div>

                        <div class="row">
                            <nav>
                                <ul class="pager">
                                    <li class="previous"><a href="<?php echo $this->url->get(array('for' => 'movies.showEpisode', 'movie' => $movie->id, 'episode' => $episode->getPrevious()->id)); ?>"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                                    <li class="next"><a href="<?php echo $this->url->get(array('for' => 'movies.showEpisode', 'movie' => $movie->id, 'episode' => $episode->getNext()->id)); ?>">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                                </ul>
                            </nav>
                        </div>

                        <?php if ($episode->links()->count()) { ?>
                            <h2>视频链接</h2>
                            <div class="row">
                                <table class="table table-hover">
                                    <tr>
                                        <th>#</th>
                                        <th>链接</th>
                                        <th>上传者</th>
                                        <th>上传时间</th>
                                        <th>网站</th>
                                        <th><div align="center">操作</div></th>
                                    </tr>
                                    <?php foreach ($episode->links() as $link) { ?>
                                        <tr>
                                            <td><?php echo $link->id; ?></td>
                                            <td><span><a href="<?php echo $link->url; ?>" target="_blank">链接</a></span></td>
                                            <td><a href="<?php echo $this->url->get(array('for' => 'users.showLinks', 'user' => $link->user()->id)); ?>"><?php echo $link->user()->name; ?></a></td>
                                            <td><?php echo $link->created_at->diffForHumans(); ?></td>
                                            <td><span><a href="<?php echo $this->url->get(array('for' => 'sites.show', 'site' => $link->site()->id)); ?>"><?php echo $link->site()->name; ?></a></span></td>
                                            <?php if ($this->auth->has($link)) { ?>
                                                <td><a href="<?php echo $this->url->get(array('for' => 'movies.deleteLinkFromEpisode', 'movie' => $movie->id, 'episode' => $episode->id, 'link' => $link->id)); ?>" ><div align="center">删除</div></a></td>
                                            <?php } else { ?>
                                                <td><div align="center">删除</div></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                </div>
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
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-5">
                        <a href="http://myphalcon2/movies/<?php echo $movie->id; ?>"><img src="<?php echo $this->url->getBaseUri(); ?><?php echo $movie->poster; ?>" alt="电影海报" width="100" height="148" /></a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo $this->url->get(array('for' => 'movies.show', 'movie' => $movie->id)); ?>"><?php echo $movie->title; ?></a>
                    </div>
                </div>
                <div class="row">
    <h2>添加相关链接</h2>
    <?php $addLinkFormUrl = "movies/".$movie->id."/Episodes/".$episode->id.'/addlink' ?>

    <?php echo $this->tag->form(array($addLinkFormUrl, 'method' => 'post')); ?>

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
                <?php if ($movie->getEpisodes()) { ?>
<div class="row">
    <h2>剧集
        <?php if ($movie->isSerialable() && $movie->isLastSeason() && $movie->isAiring()) { ?>
            <span><a href="<?php echo $this->url->get(array('for' => 'movies.updateEpisodesInfo', 'movie' => $movie->id)); ?>">更新</a></span>
            <?php if ($movie->getTVSerial()->getFirstKATLink()) { ?>
                <span><a href="<?php echo $movie->getTVSerial()->getFirstKATLink()->url; ?>" target="_blank">KAT</a></span>
            <?php } ?>
        <?php } ?>
    </h2>
    <?php foreach ($movie->getEpisodes() as $myEpisode) { ?>
        <?php if (isset($episode) && $myEpisode->id == $episode->id) { ?>
            <?php $buttonStyle = 'btn btn-primary disabled'; ?>
        <?php } else { ?>
            <?php if ($myEpisode->isPublished()) { ?>
                <?php $buttonStyle = 'btn btn-success'; ?>
            <?php } else { ?>
                <?php $buttonStyle = 'btn btn-default'; ?>
            <?php } ?>
        <?php } ?>
        <a  class="<?php echo $buttonStyle; ?>" style="margin-top: 4px" href="<?php echo $this->url->get(array('for' => 'movies.showEpisode', 'movie' => $movie->id, 'episode' => $myEpisode->id)); ?>" role="button" title="<?php echo $myEpisode->title; ?>: <?php echo $myEpisode->date->format('l M d, Y'); ?>"><?php echo sprintf('%\'.02d', $myEpisode->num); ?></a>
    <?php } ?>
</div>
<?php } ?>

            </div>


        </div>

    </div>


        
            <div class="container">
                <div class="row">
                    <?php echo xdebug_time_index();?>
                </div>
            </div>
        
    </body>
</html>