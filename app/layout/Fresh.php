<?php echo doctype(); ?>
<html>
<head>
    <meta charset="utf-8" lang="en" />
    <?php echo headerTitle('Media', isset($page) ? $page : ''); ?>
    <?php echo css('style'); ?>
    <?php echo js('jquery-3.1.0.min'); ?>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="title">
                <img src="<?php echo baseUrl(); ?>public/img/logo48.png" />
            </div>
            <div class="search">
                <?php echo inputSearch('Search', 'Search for Manga, People or Page...'); ?>
            </div>
        </div>
    </div>
    <?php $this->view('Navigation'); ?>
    <div class="body">
        <div class="container">
            <?php $this->view('message');  ?>
            <?php $this->fetchView(); ?>
        </div>
    </div>
</body>
</html>
