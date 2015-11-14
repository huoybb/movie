<?php echo $this->getContent(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>
    电视剧：<?php echo $TV->title; ?>
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
                <h1>电视剧：<?php echo $TV->title; ?></h1>

                <p><?php echo $this->flash->output(); ?></p>


                <div class="row">
                    <h2>季数汇总</h2>
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>片名</th>
                            <th>Season #</th>
                            <th>上传时间</th>
                            <th>操作</th>
                            <th>操作</th>
                        </tr>
                        <?php foreach ($TV->getSerialListMovies() as $row) { ?>
                            <tr>
                                <td><?php echo $row->id; ?></td>
                                <td><?php echo $this->tag->linkTo(array(array('for' => 'movies.show', 'movie' => $row->id), $row->title)); ?></td>
                                <td><?php echo $row->serial_num; ?></td>
                                <td> <span><?php echo $row->created_at->diffForHumans(); ?></span></td>
                                <td><a href="<?php echo $this->url->get(array('for' => 'movies.deleteSerial', 'movie' => $row->id)); ?>">删除</a></td>
                                <td><a href="<?php echo $this->url->get(array('for' => 'movies.editSerial', 'movie' => $row->id, 'serial' => $row->serial_id)); ?>">修改</a></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <?php if ($TV->links()->count()) { ?>
                <div class="row">
                    <h2>相关链接</h2>
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>链接</th>
                            <th>上传时间</th>
                            <th>操作</th>
                        </tr>
                        <?php foreach ($TV->links() as $row) { ?>
                            <tr>
                                <td><?php echo $row->id; ?></td>
                                <td><a href="<?php echo $row->url; ?>" target="_blank"><?php echo $row->site()->name; ?></a></td>
                                <td> <span><?php echo $row->created_at->diffForHumans(); ?></span></td>
                                <td><a href="<?php echo $this->url->get(array('for' => 'tvserials.updateEpisodes', 'link' => $row->id, 'tvserial' => $TV->id)); ?>">更新每集信息</a></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <?php } ?>

                    <div class="row">
                        <h2>Episodes Info</h2>

                        <table class="table table-hover">
                            <?php foreach ($TV->getSerialList() as $serial) { ?>

                            <tr>
                                <th colspan="3">Season <?php echo $serial->serial_num; ?></th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>分集名称</th>
                                <th>上映时间</th>
                            </tr>
                            <?php foreach ($serial->getEpisodes() as $episode) { ?>
                                <tr>
                                    <td><?php echo $episode->num; ?></td>
                                    <td><a href="<?php echo $this->url->get(array('for' => 'movies.showEpisode', 'episode' => $episode->id, 'movie' => $serial->movie_id)); ?>"><?php echo $episode->title; ?></a></td>
                                    <td><?php echo $episode->date->format('l M d, Y'); ?></td>
                                </tr>
                            <?php } ?>
                            <?php } ?>
                        </table>
                    </div>
            </div>
            <div class="col-md-2">
                <div class="row">
    <h2>添加相关链接</h2>
    <?php $addLinkFormUrl = "tvserials/".$TV->id."/addlink" ?>
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