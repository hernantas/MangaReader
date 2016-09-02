<div class="panel single">
    <h1>Scan</h1>
    <div class="warp">
        Scan manga directory (<?php echo $path; ?>) for new manga or chapter.
    </div>
    <div class="warp">
        <b>Scan Option:</b>
        <div class="hwarp">
            <?php echo inputRadio('option', 'fast',
                'Fast <span class="desc">(Scan manga only)</span>'); ?>
        </div>
        <div class="hwarp">
            <?php echo inputRadio('option', 'normal',
                'Normal <span class="desc">(Scan manga and chapter)</span>'); ?>
        </div>
        <div class="hwarp">
            <?php echo inputRadio('option', 'slow',
                "Slow <span class=\"desc\">(Scan manga, chapter and it's image.)</span>"); ?>
        </div>
    </div>
    <?php foreach ($mangaList as $manga): ?>
        <div class="warp">
            <?php echo inputCheckbox('scan', $manga['name'], $manga['num']); ?>
        </div>
    <?php endforeach; ?>
</div>
