<?php $histCount = page()->manga->getHistoryCount(page()->auth->getUserId(),
    $row->id); ?>
<div class="panel card <?php echo ($row->cnt == $histCount) ? 'faded' : ''; ?>">
    <a href="<?php echo baseUrl(); ?>manga/<?php echo $row->friendly_name; ?>">
        <div>
            <?php $res = page()->manga->getImage($row->id); ?>
            <img src="<?php echo page()->image->getContentCrop($mangapath . '/' .
                $res->first()->manga_name . '/' .
                $res->first()->chapter_name . '/' .
                $res->first()->name, 208,208); ?>" />
        </div>
        <div class="warp">
            <b><?php echo $row->name; ?></b>
        </div>
        <div class="warp">
            <?php $this->view('MangaTag', ['manga'=>$row]); ?>
        </div>
        <div class="warp">
            <div>
                Chapters: <?php echo $row->cnt; ?>
            </div>
            <div>
                Views: <?php echo $row->views; ?>
            </div>
            <div class="desc">
                <?php echo page()->date->relative($row->update_at); ?>
            </div>
        </div>
    </a>
</div>