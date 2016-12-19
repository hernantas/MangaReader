<div class="panel single">
    <h1>Scan</h1>
    <div class="warp">
        Scan manga directory for new manga or chapter.
    </div>
    <div class="warp center loader">
        <?php if ($scanEmpty): ?>
            <a href="<?php echo baseUrl(); ?>scan/start">
                <?php echo inputButton('Start', 'scan_start'); ?>
            </a>
        <?php else: ?>
            <img src="<?php echo baseUrl(); ?>public/img/ripple.gif" />
        <?php endif; ?>
    </div>
    <div class="warp time_debug">

    </div>
    <div class="warp warning_debug">

    </div>
    <div class="warp info_debug">

    </div>
</div>
