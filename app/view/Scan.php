<div class="panel single">
    <h1>Scan</h1>
    <div class="warp">
        Scan manga directory for new manga or chapter.
    </div>
    <div class="warp">
        <b>Scan Option:</b>
        <div class="vwarp">
            <div class="hwarp">
                <?php echo inputRadio('option', 'fast',
                'Fast <span class="desc">(Scan for manga only)</span>'); ?>
            </div>
            <div class="hwarp">
                <?php echo inputRadio('option', 'normal',
                'Normal <span class="desc">(Scan for manga and chapter)</span>', true); ?>
            </div>
            <div class="hwarp">
                <?php echo inputRadio('option', 'slow',
                "Slow <span class=\"desc\">(Scan for manga, chapter and its image.)</span>"); ?>
            </div>
        </div>
        <div>
            <a href="<?php echo baseUrl(); ?>guide#scan">
                For more information about scan option, see this guide.
            </a>
        </div>
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
</div>
