<div class="single panel">
    <div class="warp">
        <?php echo formOpen('install/path'); ?>
        <h1>Set Up Path</h1>
        <div>
            <div><b>Manga Directory</b></div>
            <?php echo inputText('path', 'Example: E:/Manga'); ?>
            <div class="desc small">Path to your manga directory</div>
        </div>
        <div>
            <?php echo inputSubmit('Continue'); ?>
        </div>
        <?php echo formClose(); ?>
    </div>
</div>
