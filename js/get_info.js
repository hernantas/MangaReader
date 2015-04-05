// JavaScript Document

var timerErrorHandler=null;
var errorFlag = false;
function startTimer() {
	timerErrorHandler = setTimeout(function(){errorHandlerFunc()},25000);	
}
function errorHandlerFunc() {
	errorFlag = true;
}
function stopTimer() {
	clearInterval(timerErrorHandler);
}
function get_info(name) {
	startTimer();
	cAjax("handler/get_manga_id.php?name="+encodeURIComponent(name), function(msg) {
		stopTimer();
		if (!errorFlag || msg.length <= 15) {
			startTimer();
			cAjax("handler/get_manga_info.php?id="+msg, function(msg) {
				if (!errorFlag)
					document.getElementById('framed').innerHTML = msg;
				else
					document.getElementById('framed').innerHTML = "error";
			});
		} else {
			document.getElementById('framed').innerHTML = "error";
		}
	});
}