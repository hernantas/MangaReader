<!DOCTYPE html>
<html>
<head>
<title><?=(isset($data['title'])?$data['title'].' - ':'')?>Read Manga</title>
<link href="<?=$path;?>/css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" 
						src="<?=$path;?>/js/jquery-ui-1.10.3/jquery-1.9.1.js"></script>
<script language="javascript" type="text/javascript" 
						src="<?=$path;?>/js/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
<script language="javascript" type="text/javascript" 
						src="<?=$path;?>/js/jquery.animate-shadow.js"></script>
<script language="javascript" type="text/javascript" 
						src="<?=$path;?>/js/common.js"></script>
</head>
<body>
<div class="header">
	<div class="search">
		<input type="text" placeholder="Search manga, chapter, history..." /><!--
		--><button>
			<img src="<?=$path;?>/images/search.png" />
		</button>
	</div>
</div>
<div class="controlpanel">
	<a href="<?=$path;?>"><img class="logo" src="<?=$path;?>/images/snowflake.png"+ /></a>
	<?php if ($this->controller->user->is_loggedin()) { ?>
		<a href="<?=$path;?>/user/<?=$this->controller->user->get_username();?>"><?=$this->controller->user->get_username();?></a>
	<?php } ?>
	<ul class="first">
		<li class="selected"><a href="<?=$path;?>"><img class="text-image" src="<?=$path?>/images/home.png" /> Home</a></li>
	</ul>
	<ul>
		<li><a href="<?=$path;?>/manga/directory"><img class="text-image" src="<?=$path?>/images/library.png" /> Manga Directory</a></li>
		<li><a href="<?=$path;?>/manga/latest"><img class="text-image" src="<?=$path?>/images/star.png" /> Latest Manga</a></li>
		<li><a href="<?=$path;?>/manga/popular"><img class="text-image" src="<?=$path?>/images/heart.png" /> Popular Manga</a></li>
	</ul>
	<?php if (!$this->controller->user->is_loggedin()) { ?>
		<ul>
			<li><a href="<?=$path;?>/user/login"><img class="text-image" src="<?=$path?>/images/sign-in.png" /> Log In</a></li>
		</ul>
	<?php } else { ?>
		<?php if ($this->controller->user->privilege() == 0) { ?>
			<ul>
				<li><a href="<?=$path;?>/scan"><img class="text-image" src="<?=$path?>/images/publish.png" /> Scan Directory</a></li>
				<li><a href="<?=$path;?>/server/error"><img class="text-image" src="<?=$path?>/images/featured.png" /> Error Report</a></li>
				<li><a href="<?=$path;?>/server/status"><img class="text-image" src="<?=$path?>/images/database.png" /> Server Status</a></li>
			</ul>
		<?php } ?>
		<ul>
			<li><a href="<?=$path;?>/user/history"><img class="text-image" src="<?=$path?>/images/full-time.png" /> History</a></li>
			<li><a href="<?=$path;?>/user/logout"><img class="text-image" src="<?=$path?>/images/sign-out.png" /> Log Out</a></li>
		</ul>
	<?php } ?>
</div>
<div class="content center" id="body">
	<?php
		if (isset($data['error']))
		{
			?>
				<div class="panel red">
					<?php 
						if (is_array($data['error']))
						{
							foreach ($data['error'] as $value) {
								echo "<li>".$value."</li>";
							}
						}
						else {
							echo $data['error'];
						}
					
					?>
				</div>
			<?php
		}
		$this->controller->loader->view($data['view'], $data);
	?>
	<div class="panel">
		<div class="warp">
			<h1>DEBUG:</h1>
			<div>
				<?php print_r($data) ?>
			</div>
			<div>Script time: <?=$this->controller->benchmark->end(); ?>s</div>
			<div>Memory Usage: <?=$this->controller->benchmark->get_memory(); ?>b</div>
		</div>
	</div>
</div>
</body>
</html>