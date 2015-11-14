<?php echo $this->getContent(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>
    电视剧列表
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
        <p><?php echo $this->flash->output(); ?></p>

        <h1>电视剧列表</h1>


        <div class="row">
            <div class="col-md-10">
                <div><span class="label label-primary">共计<?php echo $page->total_items; ?>部电影</span>--<span class="label label-primary">第<?php echo $page->current; ?>页/总<?php echo $page->total_pages; ?>页</span></div>
                <?php if ($page->total_pages) { ?>
                    <nav>
                        <ul class="pager">
                            <li class="previous"><a href="<?php echo $this->url->get(array('for' => 'tvserials.index.page', 'page' => $page->before)); ?>"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                            <li class="next"><a href="<?php echo $this->url->get(array('for' => 'tvserials.index.page', 'page' => $page->next)); ?>">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                        </ul>
                    </nav>
                <?php } ?>
                <table class="table table-hover">
                    <tr>
                        <td>#</td>
                        <td>电视剧名</td>
                        <td>季数</td>
                        <td>开播</td>
                        <td>停播</td>
                        <td>上传时间</td>
                    </tr>
                    <?php foreach ($page->items as $tv) { ?>
                        <tr>
                            <td><?php echo $tv->tvserials->id; ?></td>
                            <td><span><?php echo $this->tag->linkTo(array(array('for' => 'tvserials.show', 'tvserial' => $tv->tvserials->id), $tv->tvserials->title)); ?></span></td>
                            <td><span><?php echo $tv->counts; ?></span></td>
                            <td><span><?php echo $tv->tvserials->start; ?></span></td>
                            <td><span><?php echo $tv->tvserials->end; ?></span></td>
                            <td> <span><?php echo $tv->tvserials->created_at->diffForHumans(); ?></span></td>
                        </tr>
                    <?php } ?>
                </table>
                <?php if ($page->total_pages) { ?>
                    <nav>
                        <ul class="pager">
                            <li class="previous"><a href="<?php echo $this->url->get(array('for' => 'tvserials.index.page', 'page' => $page->before)); ?>"><span aria-hidden="true">&larr;</span> 上一页</a></li>
                            <li class="next"><a href="<?php echo $this->url->get(array('for' => 'tvserials.index.page', 'page' => $page->next)); ?>">下一页 <span aria-hidden="true">&rarr;</span></a></li>
                        </ul>
                    </nav>
                <?php } ?>
            </div>
            <div class="col-md-2">
                <h2>标签</h2>
                <?php foreach ($this->registry->allTags as $mytag) { ?>
                    <span><a href="<?php echo $this->url->get(array('for' => 'tags.show', 'tag' => $mytag->id)); ?>"><?php echo $mytag->name; ?></a>(<?php echo $mytag->movieCounts; ?>)</span>
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