// JavaScript Document

var finished_time = 0.0;
var script_time = 0.0;
var percent = 0;
var curPercent = 0;

function loadBoxMover() {
	curPercent+=(percent-curPercent)*0.02;
	document.getElementById('load_box').style.width = curPercent + "%";
	//if (curPercent < 99.99) {
		setTimeout("loadBoxMover()",10);
	//}
}

function sc_thumb_gen()
{
	var sStart = (new Date()).getTime();
	document.getElementById("result").innerHTML = "<h1 class=\"red\">Generating Thumbnail...</h1>";
	$.ajax({ url:"handler/thumb_generator.php", success: function(msg) {
		var sEnd = (new Date()).getTime();
		var calTimeDif = (((sEnd-sStart) / 1000)).toFixed(2);
		finished_time += parseFloat(calTimeDif);
		document.getElementById("result").innerHTML = "<h1 class=\"green\">Completed ("+(finished_time.toFixed(2))+"s + "+(script_time.toFixed(2))+"s)</h1>";
		setTimeout("document.location = \"index.php\"",1000);
	}});
}

function sc_finishing() {
	var sStart = (new Date()).getTime();
	document.getElementById("result").innerHTML = "<h1 class=\"red\">Deleting removed manga, chapter, picture, and history...</h1>";
	$.ajax({ url:"handler/scan_finishing.php", success: function(msg) {
		var sEnd = (new Date()).getTime();
		var calTimeDif = (((sEnd-sStart) / 1000)-msg).toFixed(2);
		finished_time += parseFloat(calTimeDif);
		sc_thumb_gen();
	}});
}

var scan_index = 0;
var chkObj = new Array();
var lblObj = new Array();

function getList() {
	percent = ((scan_index/scan_num)*100.0);
	//document.getElementById('load_box').style.width = ((scan_index/scan_num)*100.0) + "%";
	document.title = ((scan_index/scan_num)*100).toFixed(0) + "% scanned";
	if (scan_index < scan_num) {
		var lbl = lblObj[scan_index].innerHTML;
		var sStart = (new Date()).getTime();
		if (chkObj[scan_index].checked == 1) {
			lblObj[scan_index].innerHTML = lbl + " <font class=\"red\">Scaning</font>";
			
			$.ajax({url:"handler/scan_chapter.php?manga="+encodeURIComponent(lbl), success: function(msg) {
				
				if (scan_index > 0)	$(lblObj[scan_index-1].parentNode).hide(750);
				
				var sEnd = (new Date()).getTime();
				var calTimeDif = (((sEnd-sStart) / 1000)-msg).toFixed(2);
				
				lblObj[scan_index].innerHTML = lbl + " <font class=\"white\">(" + msg + "s+" + calTimeDif + "s)</font>";
				chkObj[scan_index].parentNode.setAttribute("class","opt scan_completed");
				
				finished_time += parseFloat(msg);
				script_time += parseFloat(calTimeDif);
				scan_index++;
				getList();
			}});
		} else {
			if (scan_index > 0)	$(lblObj[scan_index-1].parentNode).hide(750);
			
			var sEnd = (new Date()).getTime();
			var calTimeDif = ((sEnd-sStart) / 1000).toFixed(2);
			
			lblObj[scan_index].innerHTML = lbl + " <font class=\"red\">(Skiped "+calTimeDif+"s)</font>";
			script_time += parseFloat(calTimeDif);
			scan_index++;
			getList();
		}
	} else {
		// Completed Action
		$(lblObj[scan_index-1].parentNode).hide(750, function() {
			sc_finishing();
		});
	}
	//}
}

function startScan() {
	scan_index = 1;
	percent = 0;
	curPercent = 0;
	document.getElementById('scan_btn').disabled = "disabled";
	document.getElementById('scan_btn').removeAttribute("onclick");
	document.getElementById('loading').removeAttribute("style");
	for (var i=0;i<scan_num;i++) {
		chkObj[i] = document.getElementById("chk_"+(i+1));
		chkObj[i].disabled = true;
		lblObj[i] = document.getElementById("lbl_"+(i+1));
	}
	
	$.ajax({url:"handler/scan_init.php", success: function(msg) {
		// document.getElementById("result").innerHTML += msg;
		scan_index = 0;
		getList();
		loadBoxMover();
	}});
}