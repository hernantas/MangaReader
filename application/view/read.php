<!DOCTYPE html>
<html>
<head>
<title>Read <?=$data['title']?> - Read Manga</title>
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
<body class="night">
<div class="header">
	<div class="center">
		<a href="<?=$path?>">Home</a>
		<a href="<?=$path?>/manga/<?=$data['id_manga']?>"><?=$data['manganame']?></a>
		<a href="<?=$path?>/manga/<?=$data['id_manga']?>/<?=$data['id_chapter']?>"><?=$data['title']?></a>
	</div>
</div>
<div class="content center">
	<h1><?=$data['manganame']?></h1>
	<div>
		<select id="selectnavigation">
		<?php 
			$before_id = 0;
			$after_id = 0;
			$last_sort = "";
			foreach($data['sort'] as $sort) {
				if ($after_id != 0 && $before_id == 0)
					$before_id = $data['manga'][$sort]['id'];
		?>
			<option <?=($data['manga'][$sort]['id']==$data['id_chapter']?'selected="selected"':'')?> 
				value="<?=$data['manga'][$sort]['id_manga']?>/<?=$data['manga'][$sort]['id']?>">
				<?=$data['manga'][$sort]['name']?>
			</option>
		<?php 
				if ($data['manga'][$sort]['id']==$data['id_chapter'])
				{
					if ($last_sort == "")
						$after_id = -1;
					else
						$after_id = $data['manga'][$last_sort]['id'];
				}
				$last_sort = $sort;
			}
		?>
		</select>
	</div>
</div>
<div class="read">
<?php 
	$first = false;
	while ($dat = $data['picture']->get_next()) { $open = false;
?>
	<div>
		<?php  if (!$first) { $first = true; $open = true; ?>
		<a href="<?=$path?>/manga/<?=$data['id_manga']?>/<?=$before_id<=0?'':$before_id?>">
		<?php } elseif ($data['picture']->is_last()) { $open = true; ?>
		<a href="<?=$path?>/manga/<?=$data['id_manga']?>/<?=$after_id<=0?'':$after_id?>">
		<?php } ?>
			<img src="<?=$path?>/image/<?=$dat['id'];?>" />
		<?php if ($open) { ?>
		</a>
		<?php } ?>
	</div>
<?php } ?>
</div>
<div class="panel nowarp btnmore center">
	<a href="<?=$path?>/manga/<?=$data['id_manga']?>/<?=$after_id?>">More</a>
</div>
<script language="javascript" type="text/javascript">bpath = "<?=$path?>";</script>
</body>
</html>