<div class="clearfix feed">
<?php while ($row = $feed->row()): ?>
    <div class="panel feed-item">
        <div class="warp">
            <a href="<?php echo baseUrl().$row->fmanga; ?>" class="title">
                <?php echo $row->manga; ?>
            </a>
            <div class="warp">
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
            
        </div>
    </div>
<?php endwhile; ?>
</div>
