<?php
	$folder = scandir($CFG['MANGA_PATH']);
	
	$manga_data = array();
	
	$qs = $db->Query("select * from manga_name");
	while ($dat = mysql_fetch_array($qs))
	{
		$manga_data[$dat['name']] = $dat;
	}
?>
<div class="warp panel dt">
    <div class="sc">
        <input id="scan_btn" style="float:right;" class="btn" type="submit" onclick="startScan();" value="Begin Scan" />
        <h1><?php echo L_SCAN_FOLDER; ?></h1>
        <!--
        <div class="desc">Scaning Setting</div>
        <table class="form" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="field">Select Speed:</td>
                <td class="input">
                    <select>
                        <option>Very Fast</option>
                        <option>Fast</option>
                        <option selected>Normal</option>
                        <option>Slow</option>
                        <option>Very Slow</option>
                    </select>
                </td>
            </tr>
        </table>
        Fast -> Scan only until chapter (no pict) except new Chapter
        Slow -> Scan Everything
        
        -->
        <div class="warp desc">
            <?php echo L_TOOLTIP_CHOSE_SCAN_; ?>
        </div>
        <div class="warp" id="scan-option">
            <b>Scan option: [ <a href="#" id="check-all">Check All</a> / <a id="uncheck-all" href="#">Uncheck All</a> ] </b><span class="desc">(Experimental)</span>
			<div>
				<label>
					<input id="mode-fast" type="radio" name="mode" value="mode-fast" /> Fast <span class="desc">Only check if there is new manga</span>
				</label><br />
				<label>
					<input id="mode-medium" checked="checked" type="radio" name="mode" value="mode-medium" /> Medium <span class="desc">Check if there is a new manga or chapter, but will not check picture</span>
				</label><br />
				<label>
					<input id="mode-slow" type="radio" name="mode" value="mode-slow" /> Slow <span class="desc">Check for everything, slower but all the database index will be same as your folder</span>
				</label><br />
            </div>
			<!--
			<div>
				<label>
					<input type="checkbox" id="img-flush" value="Flush" /> Flush
					<span class="desc">Destroy all image index, you won't be able to read when scan in this mode</span>
				</label>
			</div>
			-->
        </div>
        <div id="loading" class="loading-box" style="display:none;">
            <div id="load_box" class="loading"></div>
        </div>
        <div id="result" class="warp">
		    <div class="list clearfix">
		        <?php	
		            $inc = 0;
		            foreach($folder as $fld) {
		                if (is_dir($CFG['MANGA_PATH'] . "\\" . $fld) && $fld != '.' && $fld != '..') 
						{
		                    $inc++;
		                    //$qs = $db->Query("select * from `manga_name` where `name`=\"" . $fld . "\" limit 0,1");
		                    // $mNum = mysql_num_rows($qs);
		                    //$mId = mysql_fetch_array($qs);
		                    // $isNew = 1;
		                    // ($mId['scan']||$mNum == 0?"checked=\"checked\"":"")
							$bchek = false;
							if (array_key_exists($fld,$manga_data)) 
							{
								$bchek = $manga_data[$fld]['completed'];
							}
		                    echo "<div class=\"opt\"><input type=\"checkbox\" ".(!$bchek?"checked=\"checked\" style=\"visibility:hidden;\"":"")." id=\"chk_" . $inc . "\" name=\"folder\" value=\"" . $fld . "\" /><label id=\"lbl_" . $inc . "\" for=\"chk_" . $inc . "\">" . $fld . "</label></div>";
		                }
		            }
		        ?>
		    </div>
		</div>
    </div>
</div>
<script language="javascript" type="text/javascript">
    <?php
        echo "var scan_num = " . $inc . ";";
    ?>
</script>
<script language="javascript" type="text/javascript" src="js/scan.js"></script>