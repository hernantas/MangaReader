<?php if ((int)$manga->rankings < 100 && $manga->rankings != '0'): ?>
    <span class="tag red">hot</span>
<?php endif; ?>
<?php if (time()-(int)$manga->added_at < 604800): ?>
    <span class="tag orange">new</span>
<?php elseif (time()-(int)$manga->update_at < 604800): ?>
    <span class="tag orange">updated</span>
<?php endif; ?>
