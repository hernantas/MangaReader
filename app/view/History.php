<div class="panel <?php echo (isset($single)?'single':''); ?>">
    <div class="warp">
        <b>My Reading History</b>
    </div>
    <div class="<?php echo (isset($single)?'':'warp'); ?>">
    <?php while ($row = $history->row()): ?>
        <div <?php echo (isset($single)?'class="list"':''); ?>>
            <a href="<?php echo baseUrl().
                "manga/$row->fmanga/chapter/$row->fchapter" ?>"
                <?php echo (isset($single)?'class="clearfix"':''); ?>>
            <?php if (isset($single)): ?>
                <div class="left hwarp">
                    <?php echo $row->chapter; ?>
                </div>
                <div class="right desc">
                    <?php echo page()->date->relative($row->update_at); ?>
                </div>
            <?php else: ?>
                <?php echo $row->chapter;?>
            <?php endif; ?>
            </a>
        </div>
    <?php endwhile; ?>
    </div>
</div>
