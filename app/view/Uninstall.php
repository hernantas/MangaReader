<div  class="single">
    <div class="panel warp">
        <h1>Uninstall</h1>
        <?php if (isset($alternative)): ?>
            <div>
                <b>Last Warning: </b>Are you sure you?
            </div>
            <div>
                <a href="<?php echo baseUrl(); ?>">
                    <?php echo inputButton('No'); ?>
                </a>
            </div>
            <div>
                <?php echo formOpen('admin/uninstall/warning'); ?>
                    <?php echo inputHidden('uninstall', 'yes'); ?>
                    <?php echo inputSubmit('Yes', 'alt'); ?>
                <?php echo formClose(); ?>
            </div>

        <?php else: ?>
            <div>
                <b>Warning:</b> Be careful, when uninstallation progress begin, it can't be reverted.
            </div>
            <div>
                Are you sure you want to uninstall this website application?
            </div>
            <div>
                <a href="<?php echo baseUrl(); ?>">
                    <?php echo inputButton('No'); ?>
                </a>
            </div>
            <div>
                <a href="<?php echo baseUrl(); ?>admin/uninstall/warning">
                    <?php echo inputButton('Yes', 'alt'); ?>
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>
