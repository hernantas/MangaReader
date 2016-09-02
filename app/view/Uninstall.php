<div class="panel single">
    <h1>Uninstall</h1>
    <?php if (isset($alternative)): ?>
        <div class="center warp">
            <b>Last Warning: </b>Are you sure you?
        </div>
        <div class="center warp">
            <a href="<?php echo baseUrl(); ?>">
                <?php echo inputButton('No'); ?>
            </a>
        </div>
        <div class="center warp">
            <?php echo formOpen('admin/uninstall/warning'); ?>
                <?php echo inputHidden('uninstall', 'yes'); ?>
                <?php echo inputSubmit('Yes', 'alt'); ?>
            <?php echo formClose(); ?>
        </div>

    <?php else: ?>
        <div class="warp">
            <b>Warning:</b> Be careful, when uninstallation progress begin, it can't be reverted.
        </div>
        <div class="warp">
            Are you sure you want to uninstall this website application?
        </div>
        <div class="center warp">
            <a href="<?php echo baseUrl(); ?>">
                <?php echo inputButton('No'); ?>
            </a>
        </div>
        <div class="center warp">
            <a href="<?php echo baseUrl(); ?>admin/uninstall/warning">
                <?php echo inputButton('Yes', 'alt'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>
