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