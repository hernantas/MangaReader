// JavaScript Document

var finished_time = 0.0;
var script_time = 0.0;
var percent = 0;
var curPercent = 0;
var mode = 1;
var animSpeed = 750;
var dayLimit = 0;
var genNumber = 1;

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
	$.ajax({ url:"handler/thumb_generator.php?index="+genNumber, success: function(msg) 
	{
		console.log("handler/thumb_generator.php?index="+genNumber+"|"+msg);
		var sEnd = (new Date()).getTime();
		var calTimeDif = (((sEnd-sStart) / 1000)).toFixed(2);
		finished_time += parseFloat(calTimeDif);
		genNumber++;
		
		if (!$.isNumeric(msg) || msg == "")
		{
			sc_thumb_gen();
		}
		else
		{
			dayLimit++;
			
			if (dayLimit >= 7)
			{
				document.getElementById("result").innerHTML = "<h1 class=\"green\">Completed ("+(finished_time.toFixed(2))+"s + "+(script_time.toFixed(2))+"s)</h1>";
				setTimeout("document.location = \"index.php\"",1000);
			}
			else
			{
				sc_thumb_gen();
			}
		}
	}});
}

function sc_finishing() {
	var sStart = (new Date()).getTime();
	document.getElementById("result").innerHTML = "<h1 class=\"red\">Deleting removed manga, chapter, picture, and history...</h1>";
	$.ajax({ url:"handler/scan_finishing.php", success: function(msg) {
		if (!$.isNumeric(msg))
			msg = 0;
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
			
			$.ajax({url:"handler/scan_chapter.php?manga="+encodeURIComponent(lbl)+"&mode="+mode, success: function(msg) 
			{
				if ($.isNumeric(msg))
				{
					if (scan_index > 0)	$(lblObj[scan_index-1].parentNode).hide(animSpeed);
				
					var sEnd = (new Date()).getTime();
					var calTimeDif = (((sEnd-sStart) / 1000)-msg).toFixed(2);
					
					lblObj[scan_index].innerHTML = lbl + " <font class=\"white\">(" + msg + "s+" + calTimeDif + "s)</font>";
					chkObj[scan_index].parentNode.setAttribute("class","opt scan_completed");
					
					finished_time += parseFloat(msg);
					script_time += parseFloat(calTimeDif);
					scan_index++;
					getList();
				}
			}});
		} 
		else 
		{
			if (scan_index > 0)	$(lblObj[scan_index-1].parentNode).hide(animSpeed);
			
			var sEnd = (new Date()).getTime();
			var calTimeDif = ((sEnd-sStart) / 1000).toFixed(2);
			
			lblObj[scan_index].innerHTML = lbl + " <font class=\"red\">(Skiped "+calTimeDif+"s)</font>";
			script_time += parseFloat(calTimeDif);
			scan_index++;
			getList();
		}
	} 
	else 
	{
		// Completed Action
		$(lblObj[scan_index-1].parentNode).hide(animSpeed, function() 
		{
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
	$("#scan_btn").hide();
	document.getElementById('loading').removeAttribute("style");
	
	if ($("#mode-fast").is(':checked'))
		mode = 0;
	else if ($("#mode-medium").is(':checked'))
		mode = 1;
	else if ($("#mode-slow").is(':checked'))
		mode = 2;
	
	$("#scan-option").hide();
	
	for (var i=0;i<scan_num;i++) 
	{
		chkObj[i] = document.getElementById("chk_"+(i+1));
		chkObj[i].disabled = true;
		lblObj[i] = document.getElementById("lbl_"+(i+1));
	}
	
	$.ajax({url:"handler/scan_init.php", success: function(msg) 
	{
		// document.getElementById("result").innerHTML += msg;
		scan_index = 0;
		getList();
		loadBoxMover();
	}});
}

$(document).ready(function()
{
	if ($.browser.mobile)
	{
		animSpeed = 0;
	}
	
	$("#check-all").click(function()
	{
		for (var i=0;i<scan_num;i++) 
		{
			if ($("#chk_"+(i+1)).css('visibility') != 'hidden')
			{
				$("#chk_"+(i+1)).prop('checked', true);
			}
		}
		return false;
	});
	
	$("#uncheck-all").click(function()
	{
		for (var i=0;i<scan_num;i++) 
		{
			if ($("#chk_"+(i+1)).css('visibility') != 'hidden')
			{
				$("#chk_"+(i+1)).prop('checked', false);
			}
		}
		return false;
	});
});