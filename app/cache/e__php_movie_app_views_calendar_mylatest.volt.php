<?php echo $this->getContent(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>
    <?php echo $this->auth->name; ?>关注的剧目更新列表
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
        <h1>我关注的更新列表,<a href="<?php echo $this->url->get(array('for' => 'calendar.latest')); ?>">全部的更新列表</a></h1>
        <p>更新剧目如下：</p>
        <div class="row">
            <table class="table table-hover">
    <tr>
        <th>#</th>
        <th>更新时间</th>
        <th>星期几</th>
        <th>Seasons</th>
        <th>Episodes</th>
        <th>评论</th>
        <th>链接</th>
    </tr>
    <?php foreach ($episodes as $row) { ?>
        <tr>
            <td><?php echo $row->episodes->id; ?></td>
            <td><?php echo $row->episodes->date->diffForHumans(); ?></td>
            <td><?php echo $row->episodes->date->format('l'); ?></td>
            <td><a href="<?php echo $this->url->get(array('for' => 'movies.show', 'movie' => $row->movies->id)); ?>"><?php echo $row->movies->title; ?></a></td>
            <td><a href="<?php echo $this->url->get(array('for' => 'movies.showEpisode', 'movie' => $row->movies->id, 'episode' => $row->episodes->id)); ?>">第<?php echo $row->episodes->num; ?>集:<?php echo $row->episodes->title; ?></a></td>
            <td>
                <?php echo $row->commentsCount; ?>
            </td>
            <td>
                <?php if ($row->movies->getFirstTTMJLink()) { ?>
                    <a href="<?php echo $row->movies->getFirstTTMJLink()->url; ?>" target="_blank">天天美剧</a>
                <?php } ?>
                <?php if ($row->links->id != null) { ?>
                    <a href="<?php echo $row->links->url; ?>" target="_blank"><?php echo $row->links->site()->name; ?></a>
                <?php } ?>
            </td>

        </tr>
    <?php } ?>
</table>
        </div>

    </div>

        
            <div class="container">
                <div class="row">
                    <?php echo xdebug_time_index();?>
                </div>
            </div>
        
    </body>
</html>