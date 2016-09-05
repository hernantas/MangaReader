<div class="panel single">
    <div class="warp">
        <h1><?php echo $manga->name; ?></h1>
    </div>
    <div class="warp">
        <?php foreach($order as $ord): ?>
            <?php $chapter = $chapters[$ord]; ?>
            <div class="list">
                <a href="<?php echo baseUrl().'manga/'.$manga->friendly_name.'/chapter/'.$chapter->friendly_name; ?>"
                    class="clearfix">
                    <div class="left">
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
