<div class="navigation">
    <?php foreach ($nav as $sect): ?>
    <div class="section">
        <?php foreach($sect as $list): ?>
        <a href="<?php echo baseUrl().$list['url']; ?>">
            <img src="<?php echo baseUrl(); ?>public/img/<?php echo $list['img']; ?>" />
            <div class="text">
                <?php echo $list['text']; ?>
            </div>
            <?php if (isset($list['opt'])): ?>
            <div class="opt">
                <?php echo $list['opt']; ?>
            </div>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</div>
