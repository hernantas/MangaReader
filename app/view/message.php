<?php if (isset($msg)): ?>
    <?php foreach ($msg as $type=>$list): ?>
        <div class="single panel <?php echo $type; ?>">
            <div class="warp">
                <div>
                    <b><?php echo strtoupper($type); ?>!</b>
                </div>
                <ul>
                    <?php foreach ($list as $value): ?>
                        <li><?php echo $value; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
