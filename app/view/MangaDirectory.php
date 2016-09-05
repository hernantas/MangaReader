<div class="clearfix">
    <?php while ($row = $mangalist->row()): ?>
    <div class="panel card">
        <a href="<?php echo baseUrl(); ?>manga/<?php echo $row->friendly_name; ?>">
            <div>
                <?php $res = page()->manga->getImage($row->id); ?>
                <img src="<?php echo page()->image->getContentResize($mangapath . '/' .
                    $res->first()->manga_name . '/' .
                    $res->first()->chapter_name . '/' .
                    $res->first()->name, 208,208); ?>" />
            </div>
            <div class="warp">
                <b><?php echo $row->name; ?></b>
            </div>
            <div class="warp">
                <?php if ($row->completed === '1'): ?>
                    <span class="tag black">completed</span>
                <?php endif; ?>
                <?php if ((int)$row->rankings < 100 && $row->rankings != '0'): ?>
                    <span class="tag red">hot</span>
                <?php endif; ?>
                <?php if (time()-(int)$row->added_at < 604800): ?>
                    <span class="tag orange">new</span>
                <?php elseif (time()-(int)$row->update_at < 604800): ?>
                    <span class="tag orange">updated</span>
                <?php endif; ?>
            </div>
            <div class="warp">
                <div>
                    Chapters: <?php echo $row->cnt; ?>
                </div>
                <div>
                    Views: <?php echo $row->views; ?>
                </div>
                <div class="desc">
                    <?php echo page()->date->relative($row->update_at); ?>
                </div>
            </div>
        </a>
    </div>
    <?php endwhile; ?>
</div>
<div class="warp clearfix">
    <?php foreach ($page as $p): ?>
        <div class="panel page">
            <?php if ($p[0]==$curpage): ?>
                <b class="empty">
            <?php elseif ($p[0] != '...'): ?>
                <a href="<?php echo baseUrl().'manga/directory/page/'.$p[1]; ?>">
            <?php else: ?>
                <span class="empty">
            <?php endif; ?>
            <?php echo $p[0]; ?>

            <?php if ($p[0]==$curpage): ?>
                </b>
            <?php elseif ($p[0] != '...'): ?>
                </a>
            <?php else: ?>
                </span>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
