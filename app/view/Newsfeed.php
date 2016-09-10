<?php if (!isset($nofeed) || $nofeed==='0'): ?>
<div class="clearfix feed">
<?php endif; ?>
<?php $limit = 0; ?>
<?php while ($row = $feed->row()): ?>
    <?php if ($limit++ > 25) {break;} ?>
    <div class="panel feed-item">
        <div class="warp">
            <a href="<?php echo baseUrl().'manga/'.$row->fmanga; ?>" class="title">
                <?php echo $row->manga; ?>
            </a>
            <div class="warp content">
                <div>
                    <a href="<?php echo baseUrl().'manga/'.$row->fmanga.'/chapter/'.$row->friendly_name ?>">
                        <?php echo page()->mangalib->nameFix($row->name, $row->manga); ?>
                    </a>
                </div>
                <?php $maxCount = 0; ?>
                <?php while ($nrow = $feed->row()): ?>
                    <?php if ($nrow->idmanga != $row->idmanga): ?>
                        <?php $feed->seek($feed->position()-1); ?>
                        <?php break; ?>
                    <?php endif; ?>
                    <?php if ($maxCount < 9): ?>
                        <div>
                            <a href="<?php echo baseUrl().$nrow->fmanga.'/chapter/'.$nrow->friendly_name ?>">
                                <?php echo page()->mangalib->nameFix($nrow->name, $nrow->manga); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php $maxCount++; ?>
                    <?php if ($maxCount == 10): ?>
                        <a href="<?php echo baseUrl().'manga/'.$row->fmanga; ?>">
                            ~ Read More
                        </a>
                    <?php endif; ?>
                <?php endwhile; ?>
            </div>
        </div>
        <div>
            <?php $res = page()->manga->getImages($row->idmanga, $row->id, 0, 2); ?>
            <?php while ($img = $res->row()): ?>
            <img src="<?php echo page()->image->getContentCrop($mangapath . '/' .
                $row->manga . '/' .
                $row->name . '/' .
                $img->name,
                ($res->count()>1?157:314),($res->count()>1?157:314)); ?>" />
            <?php endwhile; ?>
        </div>
    </div>
<?php endwhile; ?>
<?php if (!isset($nofeed) || $nofeed==='0'): ?>
    <?php echo inputSubmit('Load More...', 'load-more hidden'); ?>
    <div class="panel load-loading">
        <div class="center">
            <img src="<?php echo baseUrl(); ?>public/img/ripple.gif" />
        </div>
    </div>
</div>
<?php endif; ?>
