<div class="clearfix">
<?php foreach($data['sorted_manga'] as $sorted) {
	 $dat = $data['manga'][$sorted]; 
	 $dat['picture'] = $data['picture'][$dat['id']];
?>
	<div class="card">
		<div class="panel nowarp clearfix">
			<div class="thumb">
				<img src="<?=$path?>/image/<?=$dat['picture']?>/1" />
			</div>
			<div class="warp pwarp page">
				<div class="title"><a href="<?=$path;?>/manga/<?=$dat['id'];?>"><?=$dat['name'];?></a></div>
				<div class="pwarp">
					<div class="desc">Read <?=$dat['read_count']?> times</div>
					<div class="">
						Status:
						<?php if ($dat['completed']=='1') { ?>
							<img src="<?=$path?>/images/1387053256_book.png" class="text-image" />
							Completed
						<?php } else { ?>
							Ongoing
						<?php } ?>	
					</div>
					<div>Chapter: <?=$dat['chapter_count']?></div>
					<div>Last Update: <?=date('d-m-Y',$dat['last_update'])?></div>
				</div>
			</div>
			<div class="pwarp dt"><input type="button" class="white" value="Chapter List" /></div>
		</div>
	</div>
<?php } ?>
</div>
<div class="more">
<?php if ($data['cur_page'] > 2) { ?>
<a href="<?=$path?>/manga/directory/page/1"><input class="white" type="button" value="First" /></a>
<?php } if ($data['cur_page'] > 1) { ?>
<a href="<?=$path?>/manga/directory/page/<?=$data['prev_page']?>"><input class="white" type="button" value="Previous" /></a>
<?php } for ($i=$data['first_page_number'];$i<$data['last_page_number'];$i++) { ?>
<a href="<?=$path?>/manga/directory/page/<?=$i?>"><input class="white" type="button" value="<?=$i?>" /></a>
<?php } if ($data['cur_page'] >= 1 && $data['cur_page'] < $data['last_page']) { ?>
<a href="<?=$path?>/manga/directory/page/<?=$data['next_page']?>"><input class="white" type="button" value="Next" /></a>
<?php } if ($data['cur_page'] < $data['last_page']-1) { ?>
<a href="<?=$path?>/manga/directory/page/<?=$data['last_page']?>"><input class="white" type="button" value="Last" /></a>
<?php } ?>
</div>