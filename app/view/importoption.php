<div class="single panel">
    <div class="hwarp">
        <h1>Import/Export Data</h1>
    </div>
    <div class="warp">
        <div>
            <b>Import</b> is for getting/restore data from previous version or backup file.
        </div>
        <div>
            <b>Export</b> is for storing data on file so it can be restored later (backup).
        </div>
    </div>
</div>
<div class="hidden">
    <div class="single panel data-progress">
        <div class="center">
            <img src="<?php echo baseUrl(); ?>public/img/ripple.gif" />
        </div>
        <div class="data-time warp">

        </div>
    </div>
</div>
<div class="data-action">
    <div class="single panel">
        <h3>Import from v0.x</h3>
        <div class="warp">
            Import data from pervious version 0.x
        </div>
        <div class="warp center">
            <?php echo inputText('dbname', 'Database name', 'Manga', false, 'import0Text'); ?>
        </div>
        <div class="warp center">
            <?php echo inputSubmit('Import', 'import0'); ?>
        </div>
    </div>


    <div class="single panel">
        <h3>Export Partial</h3>
        <div class="warp">
            Only export some part of database such as user history, manga and chapter added/updated time.
            User account must still be created and manga/chapter must be scanned again before importing this export.
        </div>
        <div class="warp center">
            <a href="<?php echo baseUrl(); ?>admin/import/export"><?php echo inputSubmit('Export'); ?></a>
        </div>
    </div>
</div>
