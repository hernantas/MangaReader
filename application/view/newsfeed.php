<div id="article" class="clearfix">
	<?php while ($newsfeed = $data["newsfeed"]->get_next()) { ?>
	<div id="feed" class="panel nf">
		<div class="title">
			<a href="<?=$path;?>/manga/<?=$newsfeed["id_manga"]; ?>"><?=$newsfeed["name_manga"]; ?></a>
		</div>
		<div class="chapter">
			<a href="<?=$path;?>/manga/<?=$newsfeed["id_manga"]; ?>/<?=$newsfeed["id"]; ?>"><?=$newsfeed["name"]; ?></a><br />
		</div>
		<div class="thumb">
			<img src="<?=$path;?>/image/<?=$data["picture"][$newsfeed["id"]];?>/1" /><!--
			--><img src="<?=$path;?>/image/<?=($data["picture"][$newsfeed["id"]]+1);?>/1" />
		</div>
		<div class="opt">
			<input type="submit" class="white" value="Chapter List" />
		</div>
	</div>
	<?php } ?>
</div>
<?php if(!$data['ajax']) { ?>
<div class="panel nowarp btnmore"><a id="newsfeedmore" href="<?=$path?>">More...</a></div>
<?php } ?>