<?php echo doctype(); ?>
<html>
<head>
    <meta charset="utf-8" lang="en" />
    <?php echo headerTitle('Media', isset($title) ? $title : ''); ?>
    <?php echo css('style'); ?>
    <?php echo js('jquery-3.1.0.min'); ?>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="title">
                <img src="<?php echo baseUrl(); ?>public/img/logo48.png" />
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

    <div class="body">
        <div class="container">
            <?php $this->view('Message');  ?>
            <?php $this->fetchView(); ?>
        </div>
    </div>
</body>
</html>
