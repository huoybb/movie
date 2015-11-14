a:7:{i:0;s:75:"<?php echo $this->getContent(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>";s:5:"title";N;i:1;s:2407:"</title>
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
        ";s:7:"content";N;i:2;s:9:"
        ";s:6:"footer";a:1:{i:0;a:4:{s:4:"type";i:357;s:5:"value";s:175:"
            <div class="container">
                <div class="row">
                    <?php echo xdebug_time_index();?>
                </div>
            </div>
        ";s:4:"file";s:50:"E:\php\movie\app\config/../../app/views/index.volt";s:4:"line";i:20;}}i:3;s:20:"
    </body>
</html>";}