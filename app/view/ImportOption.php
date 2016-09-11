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

<div class="single panel">
    <h3>Export Partial</h3>
    <div>
        Only export some part of database such as user history, manga and chapter added/updated time.
        User account must still be created and manga/chapter must be scanned again before importing this export.
    </div>
    <div class="warp center">
        <a href="<?php echo baseUrl(); ?>admin/import/export"><?php echo inputSubmit('Export'); ?></a>
    </div>
</div>
