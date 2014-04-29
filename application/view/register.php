<div class="login">
	<div class="panel">
		<div class="warp">
			<h1>Create your Account</h1>
			<form method="post" action="<?=$path?>/user/register">
				<div class="opt"><b>Username</b></div>
				<div class="dt">
					<input type="text" name="username" placeholder="Username" 
					value="<?=@$_POST['username']?>" />
				</div>
				<div class="opt desc">Must be a letter, a-z and/or A-Z.</div>
				<div class="opt"><b>Password</b></div>
				<div class="dt"><input type="password" name="password" 
					placeholder="Password" /></div>
				<div class="dt"><input type="password" name="repassword" 
					placeholder="Confirm your Password" /></div>
				<div class="opt desc">Must be a letter or a number, a-z, A-Z, 
					and/or 0-9 </div>
				<div class="dt">
					<input type="submit" value="Create account" />
				</div>
			</form>
			<div class="opt"><a href="<?=$path?>">Cancel</a></div>
		</div>
	</div>
</div>

