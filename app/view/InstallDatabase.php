<div class="single panel">
    <div class="warp">
        <?php echo formOpen('install/database'); ?>
        <h1>Setup Database</h1>
        <div>
            Now, you must input your database configuration.
        </div>
        <div>
            <div><b>Database Host</b></div>
            <?php echo inputText('host', '', 'localhost'); ?>
            <div class="desc small">Database host, example: localhost</div>
        </div>
        <div>
            <div><b>Database Username</b></div>
            <?php echo inputText('username', '', 'root'); ?>
            <div class="desc small">Database username, example: root</div>
        </div>
        <div>
            <div><b>Database Password</b></div>
            <?php echo inputPassword('password', '', ''); ?>
            <div class="desc small">Your database password</div>
        </div>
        <div>
            <div><b>Database Name</b></div>
            <?php echo inputText('name', '', 'mediadb'); ?>
            <div class="desc small">Database name, example: mediadb</div>
        </div>
        <div>
            <span></span>

            <span></span>
        </div>
        <div>
            <?php echo inputSubmit('Continue'); ?>
        </div>
        <?php echo formClose(); ?>
    </div>
</div>
