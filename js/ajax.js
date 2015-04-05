// JavaScript Document

var AJAX_LOADING_BIG = "<img src=\"images/ajax-loader.gif\" class=\"ajax-loader\" />";

function CreateXmlHttp() {
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
	  	xmlhttp=new XMLHttpRequest();
	} else {
		// code for IE6, IE5
	  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlhttp;
}

function cAjax(url, cFunc) {
	var xml = new CreateXmlHttp();
	xml.onreadystatechange = function () {
		if (xml.readyState==4) {
			if (cFunc != null) cFunc(xml.responseText);	
		}
	};
	xml.open("GET",url,true);
	xml.send();
}

function cAjaxObj(url, cObj) {
	var xml = CreateXmlHttp();
	xml.onreadystatechange = function () {
		if (xml.readyState==4) {
			if (cObj != null) {
				cObj.innerHTML = (xml.responseText);
				cObj.setAttribute('contentloaded',true);
			}
		}
	};
	xml.open("GET",url,true);
	xml.send();
}