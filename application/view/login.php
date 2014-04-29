<div class="login">
	<div class="panel">
		<div class="dt"><h1>Sign In</h1></div>
		<div class="dt"><img src="<?=$path?>/images/avatar_2x.png" /></div>
		<form method="post" action="<?=$path?>/user/login">
			<div class="dt"><input type="text" name="username" placeholder="Username" value="<?=@$_POST['username']?>" /></div>
			<div class="dt"><input type="password" name="password" placeholder="Password" /></div>
			<div class="dt">
				<input type="submit" value="Log In" />
			</div>
			<div class="opt">
				<input type="checkbox" name="stay" id="stay" value="stay" /><label for="stay">Stay signed in</label>
				<a class="right" href="<?=$path?>/help">Need Help?</a>
			</div>
			<div class="opt">
				<a class="dt" href="<?=$path?>/user/register">Create an account</a>
			</div>
		</form>
	</div>
</div>