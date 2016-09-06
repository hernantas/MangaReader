<div class="panel single">
    <div class="hwarp">
        <h1><?php echo $manga->name; ?></h1>
    </div>
    <div class="warp center">
        <?php $this->view('MangaTag', ['manga'=>$manga]); ?>
    </div>
    <div class="warp">
        <div>Rank: <?php echo $manga->rankings; ?></div>
        <div>Views: <?php echo $manga->views; ?> times</div>
        <div class="desc">
            <?php echo page()->date->relative($manga->update_at); ?>
        </div>
    </div>
</div>

<div class="panel single">
    <div class="hwarp">
        <h4>Chapters:</h4>
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
