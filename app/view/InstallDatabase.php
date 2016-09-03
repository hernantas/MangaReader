<div class="single panel">
    <div class="warp">
        <?php echo formOpen('install/database'); ?>
        <h1>Set Up Database</h1>
        <div class="warp">
            Now, you must input your database configuration.
        </div>
        <div class="warp center">
            <div><b>Database Host</b></div>
            <?php echo inputText('host', '', 'localhost'); ?>
            <div class="desc small">Database host, example: localhost</div>
        </div>
        <div class="warp center">
            <div><b>Database Username</b></div>
            <?php echo inputText('username', '', 'root'); ?>
            <div class="desc small">Database username, example: root</div>
        </div>
        <div class="warp center">
            <div><b>Database Password</b></div>
            <?php echo inputPassword('password', '', ''); ?>
            <div class="desc small">Your database password</div>
        </div>
        <div class="warp center">
            <div><b>Database Name</b></div>
            <?php echo inputText('name', '', 'mediadb'); ?>
            <div class="desc small">Database name, example: mediadb</div>
        </div>
        <div class="warp center">
            <?php echo inputSubmit('Continue'); ?>
        </div>
        <?php echo formClose(); ?>
    </div>
</div>
