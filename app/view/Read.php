<?php
    $i = 0;
    $lastChapter = '';
?>
<?php foreach ($images as $image): ?>
    <?php if ($lastChapter != $image->fchapter): ?>
        <div class="single panel">
            <h3 class="hwarp"><?php echo $image->chapter; ?></h3>
        </div>
        <?php $lastChapter = $image->fchapter; ?>
    <?php endif; ?>
    <div class="single panel">
        <?php if ($i==0): ?>
            <a href="<?php echo baseUrl().$prevLink; ?>">
        <?php elseif ($i==($count-1)): ?>
            <a href="<?php echo baseUrl().$nextLink; ?>">
        <?php endif; ?>
        <img src="<?php echo page()->image->getContent($path . '/' .
            $manga->name . '/' . $image->chapter . '/' . $image->name); ?>" />
        <?php if ($i==0 || $i==($count-1)): ?>
            </a>
        <?php endif; ?>
        <?php $i++; ?>
    </div>
<?php endforeach; ?>
