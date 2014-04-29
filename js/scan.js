var scan_counter = 0,
	max_scan = 0,
	error_counter = false,
	timer_script = 0,
	error_msg = "";

function scan_end()
{
	if (error_counter)
	{
		$('input[id^="label_"]').parent().show(0);
	}
	
	$("#result").html("<h2 class=\"red\">Deleting manga, chapter, picture, and history from removed manga</span>");
	$("#result").show(0);
	
	$.ajax({
		url: "scan/end",
		success: function(e) {
			$("#result").html("<h2 class=\"green\">Scan take "+(timer_script/1000)+" seconds</h2>"+error_msg);
			$("#result").hide(0).show(750);
			
			setTimeout(function(){
				if (!error_counter) window.location.href = "home";
			}, 5000);
			
		}
	});
}

function scan_next()
{
	var prevelem = $("#label_"+(scan_counter-1));
	var elem = $("#label_"+scan_counter);
	var loadbar = $("#loadbar");
	
	loadbar.children("div").css("width",(((scan_counter+1)/max_scan)*100)+"%");
	var str = elem.parent().children("label").html();
	var surl = "scan/ajax";
	if (!elem.is(':checked')) surl = "scan/confirmed";
	var s_start = (new Date()).getTime();
	
	elem.parent().children("label").html(str+" <span class=\"red\">Scanning...</span>");
	$.ajax({
		url: surl,
		data: {manga: str}, 
		success: function(e) {
			time_result = e;
			//if ($.is !$.isNumeric(e)) { error_counter = true; time_result = 0; error_msg += e+"<br />"; }
			
			var s_end = (new Date()).getTime();
			var s_dif = s_end-s_start;
			if (elem.is(':checked')) elem.parent().children("label").html(str+" <span class=\"green\">(Completed "+e+"s+"+((s_dif/1000)-time_result).toFixed(2)+"s)</span>");
			else elem.parent().children("label").html(str+" <span class=\"red\">(Skipped)</span>");
			
			prevelem.parent().hide(750);
			scan_counter++;
			timer_script+= s_dif;
			
			if (scan_counter < max_scan) scan_next();
			else { elem.parent().hide(750); scan_end(); } 
		}
	});
	
}
function scan_start()
{
	scan_counter = 0;
	timer_script = 0;
	$('input[id^="label_"]').attr("disabled", true);
	
	$.ajax({
		url: "scan/init",
		success: function(e) {
			scan_next();
		}
	});
}

$(document).ready(function(){
	$("#btn_scan").click(function(){
		$("#loadbar").show();
		$(this).remove();
		scan_start();
	});
});
