<div class="panel">
    <div class="warp">
        <h3>My Reading List</h3>
    </div>
</div>
<div class="clearfix">
    <?php while ($row = $mangas->row()): ?>
    <div class="panel card mini">
        <?php $res = page()->manga->getImage($row->id); ?>
        <a href="<?php echo baseUrl().'manga/'.$row->friendly_name; ?>">
            <img src="<?php echo page()->image->getContentCrop($mangapath . '/' .
                $res->first()->manga_name . '/' .
                $res->first()->chapter_name . '/' .
                $res->first()->name, 208,208); ?>" width="208" height="208" />
            <div class="warp">
                <b><?php echo $row->name; ?></b>
            </div>
        </a>
    </div>
    <?php endwhile; ?>
</div>
<?php $this->view('history', [
    'history'=>$history,
    'single'=>true
]); ?>
