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