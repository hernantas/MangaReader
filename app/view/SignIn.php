<div class="single">
    <div class="panel warp">
        <h1>Sign In</h1>
        <div>
            <?php echo inputText('username', 'Username'); ?>
        </div>
        <div>
            <?php echo inputPassword('password', 'Password'); ?>
        </div>
        <div>
            <?php echo inputCheckbox('keep', 'Remember Me'); ?>
        </div>
        <div>
            <?php echo inputSubmit('Sign In'); ?>
        </div>
        <div>
            Don't have an account yet? <a href="<?php echo baseUrl(); ?>user/signup">Sign Up</a>
        </div>
    </div>
</div>
