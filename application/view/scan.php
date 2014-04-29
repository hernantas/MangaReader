<script language="javascript" type="text/javascript" src="<?=$path;?>/js/scan.js"></script>
<div class="dt">
	<div class="panel">
		<div class="warp">
			<h1>Scan Directory</h1>
			<div class="desc">
				Chose Scaning Folder, use CTRL+F to find manga name you desire.
			</div>
			<div class="desc">
				Uncheck if manga is Completed or Canceled for faster Scan (It 
				wont be included in scaning proccess).
			</div>
			<div class="desc">
				Please don't modify anything from your manga folder or there
				will be error occured.
			</div>
			<div class="pwarp">
				<input id="btn_scan" type="button" value="Scan <?=$this->controller->config->get('path','manga') ?>" />
				<div id="loadbar" class="loading-bar">
					<div class="current">
						
					</div>
				</div>
			</div>
			<?php foreach($data['folder'] as $key => $val) { ?>
				<div class="file-list">
					<input type="checkbox" 
						<?=((array_key_exists($val, $data['manga']) 
							&& $data['manga'][$val]['completed']==0)?
							"style=\"display:none;\" checked=\"checked\"":"");?>
						id="label_<?=$key;?>" />
					<label <?php if (array_key_exists($val, $data['manga']) 
							&& $data['manga'][$val]['completed']!=0) { ?>for="label_<?=$key;?>" <?php } ?>><?=$val;?></label>
				</div>
			<?php } ?>
			<div id="result">
				
			</div>
			<script language="javascript" type="text/javascript"><?="max_scan=".$data['counter']?></script>
		</div>
	</div>
</div>