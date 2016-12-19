<div class="panel single">
    <?php echo formOpen('user/signin'); ?>
    <h1 class="warp">Sign In</h1>
    <div class="warp center">
        <?php echo inputText('username', 'Username'); ?>
    </div>
    <div class="warp center">
        <?php echo inputPassword('password', 'Password'); ?>
    </div>
    <div class="warp center">
        <?php echo inputCheckbox('keep', 'Remember Me'); ?>
    </div>
    <div class="warp center">
        <?php echo inputSubmit('Sign In'); ?>
    </div>
    <div class="warp center">
        Don't have an account yet? <a href="<?php echo baseUrl(); ?>user/signup">Create an Account</a>
    </div>
    <?php echo formClose(); ?>
</div>
