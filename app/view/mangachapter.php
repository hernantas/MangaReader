<div class="panlist">
    <div class="flex">
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
                <div class="list <?php echo isset($markHistory[$chapter->friendly_name]) ? 'faded' : ''; ?>">
                    <a href="<?php echo baseUrl().'manga/'.$manga->friendly_name.'/chapter/'.$chapter->friendly_name; ?>"
                        class="clearfix">
                        <div class="left hwarp">
                            <?php echo page()->mangalib->nameFix($chapter->name, $manga->name); ?>
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
</div>
<div class="paninfo">
    <div class="panel">
        <div class="warp">
            <div><b>Rank:</b>
                <?php echo ($manga->rankings==='0'?'No Rank':$manga->rankings); ?></div>
            <div><b>Views:</b>
                <?php echo ($manga->views==='0'?'No one read this yet':$manga->views.' times'); ?></div>
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
        <?php if ($history->isEmpty()): ?>
            <div class="warp center">
                <a href="<?php echo baseUrl()."manga/$manga->friendly_name/chapter/".
                    $chapters[end($order)]->friendly_name.'/'; ?>"><?php
                    echo inputButton('Begin Reading', 'alt w250'); ?></a>
            </div>
        <?php elseif (count($chapters)==count($markHistory) &&
            $history->first()->fchapter == $chapters[reset($order)]->friendly_name): ?>
            <div class="warp center">
                <a href="<?php echo baseUrl()."manga/$manga->friendly_name/chapter/".
                    $chapters[end($order)]->friendly_name.'/'; ?>"><?php
                    echo inputButton('Read Again', 'alt w250'); ?></a>
            </div>
        <?php else: ?>
            <div class="warp center">
                <a href="<?php echo baseUrl()."manga/$manga->friendly_name/chapter/".
                    $chapters[$history->first()->chapter]->friendly_name.'/'; ?>"><?php
                    echo inputButton('Continue Reading', 'alt w250'); ?></a>
            </div>
        <?php endif; ?>
        <?php if (page()->auth->getUserOption('privilege') == 'admin'): ?>
            <?php if (page()->manga->getOption($manga->id, 'status')==='completed'): ?>
            <div class="warp center">
                <a href="<?php echo baseUrl()."manga/$manga->friendly_name/mark/ongoing"?>"><?php
                echo inputButton('Mark as Ongoing', 'alt w250'); ?></a>
            </div>
            <?php else: ?>
            <div class="warp center">
                <a href="<?php echo baseUrl()."manga/$manga->friendly_name/mark/completed"?>"><?php
                echo inputButton('Mark as Completed', 'alt w250'); ?></a>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php if (page()->auth->isLoggedIn() && !$history->isEmpty()): ?>
        <?php $this->view('History', ['history'=>$history]); ?>
    <?php endif; ?>
</div>
