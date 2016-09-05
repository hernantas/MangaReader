<?php echo $prevLink . '<br />'; ?>
<?php echo $nextLink . '<br />'; ?>
<?php $i = 0; ?>
<?php foreach ($images as $image): ?>
    <div class="single panel">
        <?php if ($i==0): ?>
            <a href="<?php echo baseUrl().$prevLink; ?>">
        <?php elseif ($i==9): ?>
            <a href="<?php echo baseUrl().$nextLink; ?>">
        <?php endif; ?>
        <img src="<?php echo page()->image->getContent($path . '/' .
            $manga->name . '/' . $image->chapter . '/' . $image->name); ?>" />
        <?php if ($i==0 || $i==9): ?>
            </a>
        <?php endif; ?>
        <?php $i++; ?>
    </div>
<?php endforeach; ?>
