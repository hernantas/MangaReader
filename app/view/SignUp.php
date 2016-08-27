<div class="single">
    <div class="panel warp">
        <?php echo formOpen('user/signup'); ?>
        <h1>Sign Up</h1>
        <div>
            <?php echo inputText('username', 'Username, 4-16 characters.'); ?>
        </div>
        <div>
            <?php echo inputPassword('password', 'Password, 6-32 characters.'); ?>
        </div>
        <div>
            <?php echo inputPassword('rpassword', 'Retype your password.'); ?>
        </div>
        <div>
            <?php echo inputSubmit('Sign In'); ?>
        </div>
        <div>
            Already have account? <a href="<?php echo baseUrl(); ?>user/signup">Sign In</a>
        </div>
        <?php echo formClose(); ?>
    </div>
</div>
