<div class="panel single">
    <div class="warp">
        <h1>Requirements Warning</h1>
        <div>
            Before you can install the website, please fix this requirements error first.
        </div>
        <div class="panel warp">
            <?php if (isset($warning)): ?>
                <?php $i = 1; ?>
                <?php foreach ($warning as $val) : ?>
                    <div class="panel warning warp">
                        <?php echo "Warning #$i: " . $val; ?>
                    </div>
                    <?php $i++; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>