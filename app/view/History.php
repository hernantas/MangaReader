<div class="panel">
    <div class="warp">
        <b>My Reading History</b>
    </div>
    <div class="warp">
    <?php while ($row = $history->row()): ?>
        <div>
            <a href="<?php echo baseUrl().
                "manga/$row->fmanga/chapter/$row->fchapter" ?>">
                <?php echo $row->chapter ?>
            </a>
        </div>
    <?php endwhile; ?>
    </div>
</div>
