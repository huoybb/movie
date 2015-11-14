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