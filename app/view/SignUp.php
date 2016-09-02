<div class="panel single">
    <?php echo formOpen('user/signup'); ?>
    <h1>Create Account</h1>
    <div class="warp center">
        <?php echo inputText('username', 'Username, 5-16 characters.'); ?>
    </div>
    <div class="warp center">
        <?php echo inputPassword('password', 'Password, 6-32 characters.'); ?>
    </div>
    <div class="warp center">
        <?php echo inputPassword('rpassword', 'Retype your password.'); ?>
    </div>
    <div class="warp center">
        <?php echo inputSubmit('Create my account'); ?>
    </div>
    <div class="warp center">
        Already have account? <a href="<?php echo baseUrl(); ?>user/signin">Sign In</a>
    </div>
    <?php echo formClose(); ?>
</div>
