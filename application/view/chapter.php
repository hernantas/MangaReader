<div class="dt">
	<div class="panel">
		<h1 class="warp"><?=$data['manganame'];?></h1>
		<div class="command">
			<a href="<?=$path?>/manga/<?=$data['id_manga']?>/readall">Read All</a>
			<?php if ($this->controller->user->is_loggedin()) { ?>
				<a href="<?=$path?>/manga/<?=$data['id_manga']?>/complete">Mark as Completed</a>
			<?php } ?>
		</div>
		<table class="pwarp">
			<thead>
				<tr>
					<td class="first">Name</td>
					<td width="120">Date Add</td>
				</tr>	
			</thead>
			<tbody>
			<?php foreach($data['sort'] as $sort) { ?>
				<tr>
					<td class="first">
						<a href="<?=$path?>/manga/<?=$data['id_manga'];?>/<?=$data['manga'][$sort]['id']?>"><?=$data['manga'][$sort]['name']?></a>
					</td>
					<td class="desc"><?=date("M d,Y",$data['manga'][$sort]['date_add'])?></td>
				</tr>
			<?php } ?>		
			</tbody>
		</table>
	</div>
</div>