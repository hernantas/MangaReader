<div class="panel single">
    <span class="hwarp">
        Jump:
    </span>
    <select class="jump">
        <?php foreach ($chapterOrder as $order): ?>
            <option value="<?php echo 'manga/'.$manga->friendly_name.'/chapter/'.$chapters[$order]->friendly_name; ?>"
                <?php echo ($order == $chapterCurrent) ? 'selected="selected"' : ''; ?>>
                <?php echo page()->mangalib->nameFix($chapters[$order]->name, $manga->name);  ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
<?php
    $i = 0;
    $lastChapter = '';
?>
<?php foreach ($images as $image): ?>
    <?php if ($lastChapter != $image->fchapter): ?>
        <div class="single panel">
            <h3 class="hwarp">
                <a href="<?php echo baseUrl().'manga/'.$manga->friendly_name; ?>">
                    <?php echo $manga->name; ?></a>
                \
                <?php echo $image->chapter; ?>
            </h3>
        </div>
        <?php $lastChapter = $image->fchapter; ?>
    <?php endif; ?>
    <div class="single panel">
        <?php if ($i==0): ?>
            <a href="<?php echo baseUrl().$prevLink; ?>">
        <?php elseif ($i==($count-1)): ?>
            <a href="<?php echo baseUrl().$nextLink; ?>">
        <?php endif; ?>
        <img class="img_flex" src="<?php echo page()->image->getContent64($path . '/' .
            $manga->name . '/' . $image->chapter . '/' . $image->name); ?>" />
        <?php if ($i==0 || $i==($count-1)): ?>
            </a>
        <?php endif; ?>
        <?php $i++; ?>
    </div>
<?php endforeach; ?>
<script type="text/javascript">
    var prevPage = "<?php echo baseUrl().$prevLink; ?>";
    var nextPage = "<?php echo baseUrl().$nextLink; ?>";
</script>
<div class="container">
    <div class="clearfix warp">
        <div class="split-left">
            <a href="<?php echo baseUrl().$prevLink; ?>">
                <?php echo inputButton('Previous Page'); ?>
            </a>
        </div>
        <div class="split-right">
            <a href="<?php echo baseUrl().$nextLink; ?>">
                <?php echo inputButton('Next Page'); ?>
            </a>
        </div>
    </div>
</div>
