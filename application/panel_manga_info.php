<?php
	$id=$_GET['id'];
	$sql = "";
	if ($login)
		$sql = "select manga_name.*, COUNT(history.id) as your_count from manga_name, history where manga_name.id='".$id."' and manga_name.id=history.id_manga and history.user='".$dUser['id']."' limit 0,1";
	else 
		$sql = "select * from manga_name, history where manga_name.id='".$id."' and manga_name.id=history.id_manga limit 0,1";
	$query = $db->Query($sql);
	$data = mysql_fetch_array($query);
?>
<div class="page-divider black">
	<div class="panel">
        <h3 class="warp-text">Manga Info</h3>
        <ul class="list-panel no-link">
            <li><b>Status:</b> <?php echo $data['completed']==1?"<img src=\"images/book.PNG\" width=\"16\" height=\"16\" class=\"image-text\" /> Completed":"Ongoing"; ?></li>
            <li><b>Add in:</b> <?php echo date("d-F-Y",$data['add_time']); ?></li>
            <li><b>Last Update:</b> <?php echo date("d-F-Y",$data['last_update']); ?></li>
            <?php if ($login) ?> <li><b>You read:</b> <?php echo $data['your_count']; ?> times</li>
            <li><b>Read Count:</b> <?php echo $data['read_count']; ?> times</li>
        </ul>
    </div>
</div>