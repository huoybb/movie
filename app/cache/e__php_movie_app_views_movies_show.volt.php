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
            <div class="col-md-10">
                <h2><?php echo $movie->title; ?></h2>
                <div class="row">
                    <div class="col-md-4">
                        <img src="<?php echo $this->url->getBaseUri(); ?><?php echo $movie->poster; ?>" alt="电影海报" width="250" height="370" />
                    </div>
                    <div class="col-md-8">
                        <?php $list = array('director' => '导演', 'screenwriter' => '编剧', 'casts' => '主演', 'other_names' => '又名', 'IMDb_link' => 'IMDb链接', 'doubanid' => '豆瓣链接', 'release_time' => '上映日期', 'created_at' => '收藏时间'); ?>
                        <?php foreach ($list as $key => $value) { ?>
                            <?php if ($movie->getHtml($key)) { ?>
                                <div class="row">
                                    <div class="col-md-2" align="right"><span><?php echo $value; ?></span>:</div>
                                    <div class="col-md-10"><span><?php echo $movie->getHtml($key); ?></span></div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($movie->isSerialable()) { ?>
                        <div class="row">
                            <div class="col-md-2" align="right">
                                <a href="<?php echo $this->url->get(array('for' => 'tvserials.show', 'tvserial' => $movie->getTVSerial()->id)); ?>"><span>季数</span></a>
                            </div>
                            <div class="col-md-10">
                                <select id="season">
                                    <?php foreach ($movie->getSerialMovieList() as $row) { ?>
                                    <option value="<?php echo $row->movie_id; ?>" <?php if ($row->movie_id == $movie->id) { ?>selected="selected"<?php } ?>><?php echo $row->year; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-md-2" align="right"><span>操作</span>:</div>
                            <div class="col-md-10">
                                <span><a href="<?php echo $this->url->getBaseUri(); ?>movies/<?php echo $movie->id; ?>/updateInfoFromDouban">豆瓣同步</a></span>
                                <span><a href="<?php echo $this->url->getBaseUri(); ?>movies/<?php echo $movie->id; ?>/edit">修改本片</a></span>
                                <span><a href="<?php echo $this->url->getBaseUri(); ?>movies/<?php echo $movie->id; ?>/delete">删除本片</a></span>
                                <span><a href="<?php echo $this->url->get(array('for' => 'movies.addToList', 'movie' => $movie->id)); ?>">加入影单</a></span>
                                <?php if (!$movie->isSerialable()) { ?>
                                    <span><a href="<?php echo $this->url->get(array('for' => 'movies.becomeSerial', 'movie' => $movie->id)); ?>">变电视剧</a></span>
                                <?php } ?>

                                <?php if (!$movie->hasWatchList() || $movie->getWatchList()->status == 'done') { ?>
                                    <span><a href="<?php echo $this->url->get(array('for' => 'movies.addToWatchList', 'movie' => $movie->id)); ?>">追看此剧</a></span>
                                <?php } ?>
                                <?php if ($movie->hasWatchList() && $movie->getWatchList()->status != 'done') { ?>
                                    <span><a href="<?php echo $this->url->get(array('for' => 'movies.closeWatchList', 'movie' => $movie->id, 'watchList' => $movie->getWatchList()->id)); ?>">结束追看</a></span>
                                <?php } ?>

                            </div>
                        </div>
                        <?php if ($movie->links()->count()) { ?>
                            <div class="row">
                                <div class="col-md-2" align="right"><span><a href="/movies/<?php echo $movie->id; ?>/links">视频链接</a></span>:</div>
                                <div class="col-md-10">
                                    <?php foreach ($movie->links() as $link) { ?>
                                        <span><a href="<?php echo $link->url; ?>" target="_blank"><?php echo $link->site()->name; ?></a></span>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($movie->tags()->count()) { ?>
                            <div class="row">
                                <div class="col-md-2" align="right"><span><a href="/movies/<?php echo $movie->id; ?>/tags">标签链接</a></span>:</div>
                                <div class="col-md-10">
                                    <?php foreach ($movie->tagsWithCounts() as $mytag) { ?>
                                        <span><a href="<?php echo $this->url->get(array('for' => 'tags.show', 'tag' => $mytag->id)); ?>"><?php echo $mytag->name; ?></a>(<?php echo $mytag->counts; ?>)</span>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="row">
                            <button type="button" class="btn btn-default btn-lg" id="favorite-button">
                                <span class="<?php if ($this->auth->hasFavoredThis($movie)) { ?>glyphicon glyphicon-heart<?php } else { ?>glyphicon glyphicon-heart-empty<?php } ?> " aria-hidden="true" id="favorite"></span> 收藏
                            </button>
                        </div>
                        <?php if ($movie->getUsersFavoriteThis()->count()) { ?>
                            <div class="row">
                                <div class="col-md-2">收藏者</div>
                                <div class="col-md-10">
                                    <?php foreach ($movie->getUsersFavoriteThis() as $user) { ?>
                                        <span><a href="<?php echo $this->url->get(array('for' => 'users.show', 'user' => $user->id)); ?>"><?php echo $user->name; ?></a></span>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-md-2">关键词</div>
                            <div class="col-md-10"><?php echo $movie->keywords(); ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <nav>
                        <ul class="pager">
                            <li class="previous"><a href="<?php echo $this->url->get(array('for' => 'movies.show', 'movie' => $movie->getPrevious()->id)); ?>"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                            <li class="next"><a href="<?php echo $this->url->get(array('for' => 'movies.show', 'movie' => $movie->getNext()->id)); ?>">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                        </ul>
                    </nav>
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

                    <?php if ($movie->isSerialable()) { ?>
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
                    <?php } ?>

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

                <div class="row">
                    <h2>可能相关的电影</h2>
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>片名</th>
                            <th>上传时间</th>
                            <th>成为序列</th>
                        </tr>
                        <?php foreach ($movie->getRelatedMovies() as $row) { ?>
                            <?php if ($row->id != $movie->id) { ?>
                                <tr>
                                    <td><?php echo $row->id; ?></td>
                                    <td><span><a href="<?php echo $this->url->get(array('for' => 'movies.show', 'movie' => $row->id)); ?>"><?php echo $row->title; ?></a></span></td>
                                    <td><?php echo $row->created_at->diffForHumans(); ?></td>
                                    <td><a href="<?php echo $this->url->get(array('for' => 'movies.addSerialTo', 'movie' => $movie->id, 'anotherMovie' => $row->id)); ?>">addSerial</a></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>

                    </table>
                </div>


            </div>

            <div class="col-md-2">
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

                <?php if ($movie->getWatchedRecords()->count()) { ?>
                    <div class="row">
                        <h2>观看记录</h2>
                        <ul>
                            <?php foreach ($movie->getWatchedRecords() as $record) { ?>
                                <li><?php echo $record->watchlist->updated_at->diffForHumans(); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
                <?php if ($movie->isBeingWatched()) { ?>
                    <div class="row">
                        <h2>Last Watched</h2>
                        <div class="col-md-5">
                            <?php echo $movie->getWatchList()->updated_at->format('l M d'); ?>
                        </div>
                        <div class="col-md-7">
                            S<?php echo sprintf('%\'.02d', $movie->getSerial()->serial_num); ?>E<?php echo sprintf('%\'.02d', $movie->getLastWatchedEpisode()->num); ?> <br>
                            <a href="<?php echo $this->url->get(array('for' => 'movies.showEpisode', 'movie' => $movie->id, 'episode' => $movie->getLastWatchedEpisode()->id)); ?>"><?php echo $movie->getLastWatchedEpisode()->title; ?></a>
                        </div>
                    </div>
                    <div class="row">
                        <h2 >See Next</h2>
                        <div>
                            S<?php echo sprintf('%\'.02d', $movie->getSerial()->serial_num); ?>E<?php echo sprintf('%\'.02d', $movie->getLastWatchedEpisode()->getNext()->num); ?>:
                            <a href="<?php echo $this->url->get(array('for' => 'movies.showEpisode', 'movie' => $movie->id, 'episode' => $movie->getLastWatchedEpisode()->getNext()->id)); ?>"><?php echo $movie->getLastWatchedEpisode()->getNext()->title; ?></a>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($movie->isSerialable() && $movie->getEpisodeNextOnTV()) { ?>
                    <div class="row">
                        <h2>Next On TV</h2>
                        <div class="row">
                            <div class="col-md-5">
                                <?php echo $movie->getEpisodeNextOnTV()->date->format('l M d'); ?>
                            </div>
                            <div class="col-md-7">
                                S<?php echo sprintf('%\'.02d', $movie->getSerial()->serial_num); ?>E<?php echo sprintf('%\'.02d', $movie->getEpisodeNextOnTV()->num); ?> <br>
                                <a href="<?php echo $this->url->get(array('for' => 'movies.showEpisode', 'movie' => $movie->id, 'episode' => $movie->getEpisodeNextOnTV()->id)); ?>"><?php echo $movie->getEpisodeNextOnTV()->title; ?></a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
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


                <?php if ($movie->getRelatedLists()->count()) { ?>
                <div class="row">
                    <h2>相关影单列表</h2>
                    <ul>
                    <?php foreach ($movie->getRelatedLists() as $list) { ?>
                        <li><a href="<?php echo $this->url->get(array('for' => 'lists.show', 'list' => $list->id)); ?>"><?php echo $list->name; ?></a></li>
                    <?php } ?>
                    </ul>
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