<?php echo $this->getContent(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>
    <?php echo $this->auth->name; ?>追看的电影或电视剧
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
        <h1>我追看的列表</h1>
        <p>追看列表如下：</p>
        <div class="row">
            <h2>追看列表</h2>
            <table class="table table-hover">
                <tr>
                    <th>#</th>
                    <th>时间</th>
                    <th>电影</th>
                    <th>更新</th>
                    <th>看到</th>
                    <th>状态</th>
                </tr>
                <?php foreach ($movies as $row) { ?>
                    <tr>
                        <td><?php echo $row->watchlist->id; ?></td>
                        <td><?php echo $row->watchlist->updated_at->diffForHumans(); ?></td>
                        <td><a href="<?php echo $this->url->get(array('for' => 'movies.show', 'movie' => $row->movies->id)); ?>"><?php echo $row->movies->title; ?></a></td>
                        <td>
                            <?php if ($row->movies->getLastUpdatedEpisode()->id != null) { ?>
                                <a href="<?php echo $this->url->get(array('for' => 'movies.showEpisode', 'movie' => $row->movies->id, 'episode' => $row->movies->getLastUpdatedEpisode()->id)); ?>">第<?php echo $row->movies->getLastUpdatedEpisode()->num; ?>集:<?php echo $row->movies->getLastUpdatedEpisode()->title; ?></a>
                            <?php } else { ?>
                                \
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($row->episodes->id != null) { ?>
                                <a href="<?php echo $this->url->get(array('for' => 'movies.showEpisode', 'movie' => $row->movies->id, 'episode' => $row->episodes->id)); ?>">第<?php echo $row->episodes->num; ?>集:<?php echo $row->episodes->title; ?></a>
                            <?php } else { ?>
                                \
                            <?php } ?>
                        </td>
                        <td><?php echo $row->watchlist->status; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="row">
            <h2>看过的记录</h2>
            --------------
        </div>

    </div>

        
            <div class="container">
                <div class="row">
                    <?php echo xdebug_time_index();?>
                </div>
            </div>
        
    </body>
</html>