<div class="panlist">
    <div class="panel">
        <div class="warp">
            <h2><?php echo $manga->name; ?></h2>
        </div>
    </div>
    <div class="panel">
        <div class="warp">
            <b>Chapters:</b>
        </div>
        <div>
            <?php foreach($order as $ord): ?>
                <?php $chapter = $chapters[$ord]; ?>
                <div class="list">
                    <a href="<?php echo baseUrl().'manga/'.$manga->friendly_name.'/chapter/'.$chapter->friendly_name; ?>"
                        class="clearfix">
                        <div class="left hwarp">
                            <?php echo $ord; ?>
                        </div>
                        <div class="right desc">
                            <?php echo page()->date->relative($chapter->added_at); ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="paninfo">
    <div class="panel">
        <div class="warp">
            <div><b>Rank:</b> <?php echo $manga->rankings; ?></div>
            <div><b>Views:</b> <?php echo $manga->views; ?> times</div>
        </div>
        <div class="warp">
            <div class="desc">
                <?php echo page()->date->relative($manga->update_at); ?>
            </div>
        </div>
        <div class="warp">
            <?php $this->view('MangaTag', ['manga'=>$manga]); ?>
        </div>
    </div>
    <div class="panel">
        <div class="warp center">
            <?php echo inputButton('Begin Reading', 'alt w250'); ?>
        </div>
    </div>
    <?php if (page()->auth->isLoggedIn()): ?>
        <?php $this->view('History', ['manga'=>$manga]); ?>
    <?php endif; ?>
</div>
