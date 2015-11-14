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