<?php echo doctype(); ?>
<html>
<head>
    <meta charset="utf-8" lang="en" />
    <?php echo headerTitle('Media', isset($title) ? $title : ''); ?>
    <?php echo css('style'); ?>
    <?php echo jsutility(); ?>
    <?php echo js('jquery-3.1.0.min'); ?>
    <?php echo js('layout'); ?>
    <?php if (isset($additionalJs)): ?>
        <?php foreach ($additionalJs as $js): ?>
            <?php echo js($js); ?>
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body <?php echo isset($readMode) ? 'class="read"' : ''; ?>>
    <div class="header <?php echo !isset($simpleMode) ? 'hasnav' : '' ?>">
        <div class="container">
            <div class="title">
                <a href="<?php echo baseUrl(); ?>">
                    <img src="<?php echo baseUrl(); ?>public/img/logo48.png" />
                </a>
            </div>
            <?php if (!isset($simpleMode)): ?>
            <div class="search">
                <?php echo inputSearch('Search', 'Search for Manga, People or Page...'); ?>
            </div>
            <?php else: ?>
            <div class="search">
                <?php echo inputText('Info', isset($title) ? $title : '', '', true); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!isset($simpleMode)): ?>
        <?php $this->view('Navigation'); ?>
    <?php endif; ?>

    <div class="body <?php echo !isset($simpleMode) ? 'hasnav' : '' ?>">
        <div <?php echo (!isset($readMode) ? 'class="container"' : '') ?>>
            <?php $this->view('Message');  ?>
            <?php $this->fetchView(); ?>
        </div>
    </div>
</body>
</html>
