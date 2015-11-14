<?php echo $this->getContent(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>
    我的电影列表
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

        <h1>电影列表</h1>


        <div class="row">
            <div class="col-md-10">
                <div><span class="label label-primary">共计<?php echo $page->total_items; ?>部电影</span>--<span class="label label-primary">第<?php echo $page->current; ?>页/总<?php echo $page->total_pages; ?>页</span></div>
<?php if ($page->total_pages) { ?>
<nav>
    <ul class="pager">
        <li class="previous"><a href="<?php echo $this->url->get(array('for' => 'index.page', 'page' => $page->before)); ?>"><span aria-hidden="true">&larr;</span> 上一页</a></li>
        <li class="next"><a href="<?php echo $this->url->get(array('for' => 'index.page', 'page' => $page->next)); ?>">下一页 <span aria-hidden="true">&rarr;</span></a></li>
    </ul>
</nav>
<?php } ?>



    
        
        
        
    
    
        
            
            
            
            
            
        
    



   <div class="row">
    <?php foreach ($page->items as $movie) { ?>
        <div class="col-md-5 movie-list">
            <div class="col-md-4">
                <a href="<?php echo $this->url->get(array('for' => 'movies.show', 'movie' => $movie->id)); ?>"><img class="src" src="<?php echo $this->url->getBaseUri(); ?><?php echo $movie->poster; ?>" alt="<?php echo $movie->title; ?>"/></a>
            </div>
            <div class="col-md-8">
                <div class="row"><h4><?php echo $movie->title; ?></h4></div>
                <div class="row"><?php echo $movie->director; ?></div>
                <div class="row"><?php echo $movie->release_time; ?></div>
            </div>

        </div>
    <?php } ?>
</div>



<?php if ($page->total_pages) { ?>
<nav>
    <ul class="pager">
        <li class="previous"><a href="<?php echo $this->url->get(array('for' => 'index.page', 'page' => $page->before)); ?>"><span aria-hidden="true">&larr;</span> 上一页</a></li>
        <li class="next"><a href="<?php echo $this->url->get(array('for' => 'index.page', 'page' => $page->next)); ?>">下一页 <span aria-hidden="true">&rarr;</span></a></li>
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