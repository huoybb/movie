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