<div class="clearfix">
    <?php while ($row = $mangalist->row()): ?>
    <div class="panel card">
        <a href="<?php echo baseUrl(); ?>manga/chapter/<?php echo $row->friendly_name; ?>">
            <div class="warp">
                <b><?php echo $row->name; ?></b>
            </div>
            <div class="warp">
                <?php if ($row->completed === '1'): ?>
                    <span class="tag black">completed</span>
                <?php endif; ?>
                <?php if ((int)$row->rankings < 100): ?>
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
