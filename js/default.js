// JavaScript Document

function addEvent(element, event, callbackFunction) {
    if (element.addEventListener) {
        element.addEventListener(event, callbackFunction, false);
    } else if (element.attachEvent) {
        element.attachEvent('on' + event, callbackFunction);
    }
};

function getEStyle(obj, property) {
	return window.getComputedStyle(obj, null).getPropertyValue(property);
}

function debug(msg) {
	var div = document.createElement("div");
	div.innerHTML = msg;
	var dbg = document.getElementById('debugger');
	dbg.insertBefore(div, dbg.firstChild);
	
	/*update(function(t,p) {
		if (t <= 500) {
			p.obj.style.left = (p.obj.parentNode.offsetWidth) + "px";
			return true;
		}
		return false;
	},{'obj':div});*/
	
	show(div,250,{
		'y': false,
		'trans': false	
	});
	
	wait(function() {
		hide(div,250,{
			'y': false,
			'trans': false	
		});
	}, 4000);
	
	wait(function() {
		div.parentNode.removeChild(div);
	}, 4250);
}

function wait(f, time) 
{
	setTimeout(function() {
		f()
	}, time);
}

function update(f, params)
{
	if (params == null)
		params = {'fps':10}
	else if (params.fps == null)
		params.fps = 10;
	updates(f, params, 0); 
}
function updates(f, params, tmr) 
{
	if (f(tmr, params))
		setTimeout(function() {
			updates(f, params,tmr+(1000/params.fps));	
		}, (1000/params.fps));	
}
/* Script here */

// Hiding Elements with translation
function hide (obj,speed,opt) {
	if (opt == null)
		opt = {
			'x': true,
			'y': true,
			'trans': true	
		};
	update(function(t, p) {
		p.obj.style.overflow = "hidden";
		p.obj.style.display = "block";
		var height = p.defaultheight - (parseFloat(getEStyle(p.obj, 'padding-top')) + 
										parseFloat(getEStyle(p.obj, 'padding-bottom')) +
										parseFloat(getEStyle(p.obj, 'border-top-width')) +
										parseFloat(getEStyle(p.obj, 'border-bottom-width')));
		var width = p.defaultwidth - (parseFloat(getEStyle(p.obj, 'padding-left')) + 
										parseFloat(getEStyle(p.obj, 'padding-right')) +
										parseFloat(getEStyle(p.obj, 'border-left-width')) +
										parseFloat(getEStyle(p.obj, 'border-right-width')));;
		//console.log(width);
		if (p.x) 
			p.obj.style.width = (width-(width * (parseFloat(t)/p.speed))) + "px"; 
		else
			p.obj.style.width = width + "px"; 
		if (p.y) 
			p.obj.style.height = (height-(height * (parseFloat(t)/p.speed))) + "px";
		else
			p.obj.style.height = height + "px";
		if (p.trans) p.obj.style.opacity = (1.0-(parseFloat(t)/p.speed));
		
		if (t <= p.speed) return true;
		
		p.obj.style.display = "none";
		//p.obj.parentNode.removeChild(p.obj);
		return false;
	}
	,
	{
		'obj': obj, 
		'speed': speed!=null?speed:750, 
		'fps': 30,
		'x': opt.x!=null?opt.x:true,
		'y': opt.y!=null?opt.y:true,
		'trans': opt.trans!=null?opt.trans:true,
		'defaultheight':obj.offsetHeight, 
		'defaultwidth':obj.offsetWidth
	});
}
// Showing Elements with translation
function show (obj,speed,opt) {
	if (opt == null)
		opt = {
			'x': true,
			'y': true,
			'trans': true	
		};
	update(function(t, p) {
		p.obj.style.overflow = "hidden";
		p.obj.style.display = "block";
		var height = p.defaultheight - (parseFloat(getEStyle(p.obj, 'padding-top')) + 
										parseFloat(getEStyle(p.obj, 'padding-bottom')) +
										parseFloat(getEStyle(p.obj, 'border-top-width')) +
										parseFloat(getEStyle(p.obj, 'border-bottom-width')));
		var width = p.defaultwidth - (parseFloat(getEStyle(p.obj, 'padding-left')) + 
										parseFloat(getEStyle(p.obj, 'padding-right')) +
										parseFloat(getEStyle(p.obj, 'border-left-width')) +
										parseFloat(getEStyle(p.obj, 'border-right-width')));;
		//console.log(width);
		if (p.x) 
			p.obj.style.width = (width * (parseFloat(t)/p.speed)) + "px"; 
		else
			p.obj.style.width = width + "px"; 
		if (p.y) 
			p.obj.style.height = (height * (parseFloat(t)/p.speed)) + "px";
		else
			p.obj.style.height = height + "px";
		if (p.trans) p.obj.style.opacity = (1.0-(parseFloat(t)/p.speed));
		
		if (t <= p.speed) return true;
		
		return false;
	}
	,
	{
		'obj': obj, 
		'speed': speed!=null?speed:750, 
		'fps': 30,
		'x': opt.x!=null?opt.x:true,
		'y': opt.y!=null?opt.y:true,
		'trans': opt.trans!=null?opt.trans:true,
		'defaultheight':obj.offsetHeight, 
		'defaultwidth':obj.offsetWidth
	});
}


/*
function mangaListDetail() {
	var linkElements = document.getElementsByName('has-detail');
	for (i=0;i<linkElements.length;i++) {
		addEvent(linkElements[i], 'click', function() {
			var id = this.getAttribute('manga');
			var obj = document.getElementById('id-'+id);
			if (obj.getAttribute('menushow') == "false") {
				obj.style.display = 'table-row';
				obj.setAttribute('menushow',"true");
				
				if (obj.firstChild.firstChild.firstChild.getAttribute('contentloaded') != "true") {
					obj.firstChild.firstChild.firstChild.innerHTML = AJAX_LOADING_BIG;
					new cAjaxObj("application/manga.php?id="+id+"&ajx=1", obj.firstChild.firstChild.firstChild);
				}
			} else {
				obj.style.display = 'none';
				obj.setAttribute('menushow',"false");
			}
		});
	}	
}


var section = new Array(),article,darc, btnmore = null, nfindex=0;
function newsFeedRegister() {
	article = document.getElementById("article");
	
	if (article != null) {
		var tdarc = article.getElementsByTagName("div");
		var counter=0;
		darc = new Array();
		for (var k=0;k<tdarc.length;k++) {
			if (tdarc[k].getAttribute("id") == "feed") {darc[counter++] = tdarc[k];}
		}
	}
}
function newsFeedConstruct() {
	if (article != null) {
		//Remove Container First
		for (var k=0;k<darc.length;k++) {
			article.appendChild(darc[k]);
		}
		for (i=0;i<section.length;i++) {
			if (section[i].parentNode) section[i].parentNode.removeChild(section[i]);		
		}
		// Create Container
		section = new Array();
		for (i=0;i<widthRes/500;i++) {
			section[i] = document.createElement("div");
			section[i].className = "section";
			article.appendChild(section[i]);	
		}
		// Move to Container
		for (i=0;i<darc.length;i++) {
			if (darc[i].getAttribute("id") == "feed") section[i % section.length].appendChild(darc[i]);
		}
	}	
}
*/