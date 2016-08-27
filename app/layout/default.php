<?php echo doctype(); ?>
<html>
<head>
    <meta charset="utf-8" lang="en" />
    <?php echo header_title('Media', isset($page) ? $page : ''); ?>
    <?php echo css('style'); ?>
    <?php echo js('style'); ?>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="title">
                <img src="public/img/logo512.png" />
            </div>
            <div class="search">
                <?php echo input_search('Search', 'Search for Manga, People or Page...'); ?>
            </div>
        </div>
    </div>
    <div class="navigation">

    </div>
    <div class="body">
        <?php $this->fetchView(); ?>
    </div>
</body>
</html>
