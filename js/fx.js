// FunctionX API

(function( window, undefined ) {
	var 
		// The deferred used on DOM ready
		readyList,
		
		// Central reference of root FunctionX(document)
		rootFunctionX,
		
		// FunctionX Version
		core_version = "0.01",
		
		// Map functionx and fx in case of overwrite
		_FunctionX = window.FunctionX,
		_fx = window.fx,
		
		// Define local copy of FunctionX
		// Support fx(#id), fx(obj), fx(function)
		FunctionX = function(selector) {
			return new FunctionX.fn.init(selector);
		};
		
	FunctionX.fn = FunctionX.prototype = {
		functionx: core_version,
		
		constructor: FunctionX,
		
		init: function(selector) {
			var elem;
			
			// Handle fx(""), fx(null), fx(undefined), fx(false)
			if ( !selector ) {
				return this;
			}
			
			// Handle HTML strings
			if (typeof selector === "string") {
				
				// Handle fx(#id)
				if (selector.charAt(0) === "#" && selector.length >= 3 ) {
					elem = document.getElementById( selector.substr(1) );
					
					// Check parentNode to catch when Blackberry 4.6 returns
					// nodes that are no longer in the document #6963
					if ( elem && elem.parentNode ) {
						// Otherwise, we inject the element directly into the jQuery object
						this[0] = elem;
					}
					
					this.selector = selector;
					this.length = 1;
					return this;
				} 
				
				// Handle fx(DOMElement)
			} else if (selector.nodeType) {
				this.selector = this[0] = selector;
				return this;
					
				// Handle fx(function)
				// Shortcut for fx(document).ready
			} else if (typeof selector === "function") {
				// WIP
				return rootFunctionX.ready(selector);
			}
		},
		
		// Start with empty selector
		selector: "",
		
		ready: function(fn) {
			FunctionX.ready.promise().push(fn);
		}
	};
	
	// Give init function of FunctionX prototype
	FunctionX.fn.init.prototype = FunctionX.fn;
	
	FunctionX.extend = FunctionX.fn.extend = function() {
		var copy,
			target = arguments[0],
			length = arguments.length;
		
		if ( typeof target !== "object" && !(typeof target === "function") ) {
			target = {};	
		}
		
		if (length == 1) {
			target = this;
			copy = arguments[0];
		} else {
			copy = arguments[1];	
		}
		
		for (key in copy) {
			target[key] = copy[key];
		}
		return target;
	};
	
	FunctionX.extend({
		isReady: false,
		ready: function() {
			FunctionX.isReady = true;
			FunctionX.ready.promise().run();
			
			if ( document.addEventListener ) {
				document.removeEventListener( "DOMContentLoaded", FunctionX.ready, false );
				window.removeEventListener( "load", FunctionX.ready, false );
	
			} else {
				document.detachEvent( "onreadystatechange", FunctionX.ready );
				window.detachEvent( "onload", FunctionX.ready );
			}
		},
		makeArray: function(arr) {
			var newArray = Array();
			for(i in arr) {
				if (typeof arr[i] === "object")
					newArray.push(FunctionX(arr[i]));
			}
			return newArray;
		},
		access: function(obj, fn) {
			var arr = Array();
			for (i in obj) {
				if (typeof obj[i] === "object") {
					arr[i] = fn.call(obj[i], i);
				}
			}
			// if object is only 1, return only 1 data
			if (arr.length <= 1) return arr[0];
			// else return multiple data
			return arr;
		},
		push: function (arr, value) {
			arr[arr.length] = value;
			return arr;	
		},
		pop: function(arr, value) {
			for (i=0;i<arr.length;i++) {
				if (arr[i] == value) {
					for (j=i;j<arr.length;j++) {
						arr[j] = arr[j+1];	
					}
				}
			}
			return arr;
		}
	});
	
	FunctionX.ready.promise = function() {
		if (!readyList) {
			readyList = FunctionX.Deferred();
			
			if ( document.addEventListener ) {
				// Use the handy event callback
				document.addEventListener( "DOMContentLoaded", FunctionX.ready, false );
	
				// A fallback to window.onload, that will always work
				window.addEventListener( "load", FunctionX.ready, false );
	
			// If IE event model is used
			} else {
				// Ensure firing before onload, maybe late but safe also for iframes
				document.attachEvent( "onreadystatechange", FunctionX.ready );
	
				// A fallback to window.onload, that will always work
				window.attachEvent( "onload", FunctionX.ready );
			}
		}
		return readyList;
	};
	
	FunctionX.extend({
		Deferred: function() {
			var deferred = {
				fn: Array(),
				length: 0,
				push: function(fn) {
					this.fn[this.length] = fn;
					this.length++;
				},
				run: function() {
					for(var i=0;i<this.length;i++) {
						this.fn[i].call();	
					}
				}
			};
			return deferred;
		}
	});
	
	// Link Back
	window.fx = window.FunctionX = FunctionX;
	// Reference (document) as Root
	rootFunctionX = fx(document);
	
	FunctionX.extend({
		ajax: function () {
			var xml, args = arguments[0];
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xml=new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xml=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xml.onreadystatechange = function () {
				if (xml.readyState==4) {
					if (args.fn != null) args.fn(xml.responseText);	
				}
			}
			xml.open("GET",args.url,true);
			xml.send();	
		}
	});
	
	FunctionX.fn.extend({
		// Set Event
		on: function(event, fn) {
			FunctionX.access(this, function() {
				if (this.addEventListener) {
					this.addEventListener(event, fn, false);
				} else if (this.attachEvent) {
					this.attachEvent('on' + event, fn);
				}
			});
		},
		// Set/Get Attribute
		attr: function(attr, value) {
			if (value == null)
				return this[0].getAttribute(attr);
			else
				this[0].setAttribute(attr,value);
		},
		// Set/Get Css
		css: function (property, value) {
			return FunctionX.access(this, function() {
				var computedStyle;
				// IE <9 Compability
				if (window.getComputedStyle) {
					computedStyle = getComputedStyle(this, null);
				} else {
					computedStyle = this.currentStyle;
				}
				if (value == null) {
					return computedStyle.getPropertyValue(property);	
				} else {
					this.style[property] = value;
				}
			});
		},
		// Get Child
		child: function() {
			return FunctionX.makeArray(this[0].childNodes);
		}
	});
})(window);

fx(document).ready(function() {
});
/*
fx = function(e) {
		if (typeof e == "string") {
			if (e.charAt(0)=="#") {
				e=document.getElementById(e.substr(1));
			}
		}		
		if (e && e.nodeType && e != null) {
			// Add Object function
			
			/// Check if Elements is Ready, only possible on document or window elements
			e.load = function(fn) {
				fx(document).on("DOMContentLoaded", function() {
					fn();
				});
			};
			
			/// Set Event handler on Elements
			e.on = function(event, fn) {
				 if (this.addEventListener) {
					this.addEventListener(event, fn, false);
				} else if (element.attachEvent) {
					this.attachEvent('on' + event, fn);
				}
			};
			
			/// Set or Get Attribute of Elements
			e.attr = function(attr, value) { 
				if (value == null)
					return this.getAttribute(attr);
				else
					this.setAttribute(attr,value);
			};
			
			/// Set or Get CSS Elements
			e.css = function (property, value) {
				var computedStyle;
				// IE <9 Compability
				if (window.getComputedStyle) {
					computedStyle = getComputedStyle(this, null);
				} else {
					computedStyle = this.currentStyle;
				}
				if (value == null) {
					return computedStyle.getPropertyValue(property);	
				} else {
					this.style[property] = value;
				}
			};
			
			/// Get actual height
			e.height = function() {
				return this.offsetHeight - (parseFloat(this.css('padding-top')) + 
											parseFloat(this.css('padding-bottom')) +
											parseFloat(this.css('border-top-width')) +
											parseFloat(this.css('border-bottom-width')));
			}
			/// Get actual width
			e.width = function() {
				return this.offsetWidth - (parseFloat(this.css('padding-left')) + 
									parseFloat(this.css('padding-right')) +
									parseFloat(this.css('border-left-width')) +
									parseFloat(this.css('border-right-width')));;	
			}
			
			/// Hide an Elements with animation
			e.hide = function(opt) {
				if (opt == null)
					opt = {
						'x': true,
						'y': true,
						'trans': true	
					};
				fx().update({				
					'obj': this, 
					'speed': opt.speed!=null?opt.speed:750, 
					'fps': 30,
					'x': opt.x!=null?opt.x:true,
					'y': opt.y!=null?opt.y:true,
					'trans': opt.trans!=null?opt.trans:true,
					'defaultheight':this.offsetHeight, 
					'defaultwidth':this.offsetWidth,
					'fn':function(o) {
						o.obj.style.overflow = "hidden";
						o.obj.style.display = "block";
						var height = o.defaultheight - (parseFloat(getEStyle(o.obj, 'padding-top')) + 
														parseFloat(getEStyle(o.obj, 'padding-bottom')) +
														parseFloat(getEStyle(o.obj, 'border-top-width')) +
														parseFloat(getEStyle(o.obj, 'border-bottom-width')));
						var width = o.defaultwidth - (parseFloat(getEStyle(o.obj, 'padding-left')) + 
														parseFloat(getEStyle(o.obj, 'padding-right')) +
														parseFloat(getEStyle(o.obj, 'border-left-width')) +
														parseFloat(getEStyle(o.obj, 'border-right-width')));;
						//console.log(width);
						if (o.x) 
							o.obj.style.width = (width-(width * (parseFloat(o.totalTime)/o.speed))) + "px"; 
						else
							o.obj.style.width = width + "px"; 
						if (o.y) 
							o.obj.style.height = (height-(height * (parseFloat(o.totalTime)/o.speed))) + "px";
						else
							o.obj.style.height = height + "px";
						if (o.trans) o.obj.style.opacity = (1.0-(parseFloat(o.totalTime)/o.speed));
						
						if (o.totalTime <= o.speed) return true;
						
						o.obj.style.display = "none";
						o.obj.style.width = width;
						o.obj.style.height = height;
						o.obj.style.opacity = "";
					}
				});
			}
			
			/// Show an Elements with animation
			e.show = function(opt) {
				if (opt == null)
					opt = {
						'x': true,
						'y': true,
						'trans': true	
					};
				fx().update({
					'obj': this, 
					'speed': opt.speed!=null?opt.speed:750, 
					'fps': 30,
					'x': opt.x!=null?opt.x:true,
					'y': opt.y!=null?opt.y:true,
					'trans': opt.trans!=null?opt.trans:true,
					'defaultheight':this.offsetHeight, 
					'defaultwidth':this.offsetWidth,
					'fn':function(o) {
						o.obj.style.zoverflow = "hidden";
						o.obj.style.display = "block";
						var height = o.defaultheight - (parseFloat(getEStyle(o.obj, 'padding-top')) + 
														parseFloat(getEStyle(o.obj, 'padding-bottom')) +
														parseFloat(getEStyle(o.obj, 'border-top-width')) +
														parseFloat(getEStyle(o.obj, 'border-bottom-width')));
						var width = o.defaultwidth - (parseFloat(getEStyle(o.obj, 'padding-left')) + 
														parseFloat(getEStyle(o.obj, 'padding-right')) +
														parseFloat(getEStyle(o.obj, 'border-left-width')) +
														parseFloat(getEStyle(o.obj, 'border-right-width')));;
						//console.log(width);
						if (o.x) 
							o.obj.style.width = (width * (parseFloat(o.totalTime)/o.speed)) + "px"; 
						else
							o.obj.style.width = width + "px"; 
						if (o.y) 
							o.obj.style.height = (height * (parseFloat(o.totalTime)/o.speed)) + "px";
						else
							o.obj.style.height = height + "px";
						if (o.trans) o.obj.style.opacity = (parseFloat(o.totalTime)/o.speed);
						
						if (o.totalTime <= o.speed) return true;
						
						o.obj.style.display = "";
						o.obj.style.width = width;
						o.obj.style.height = height;
						o.obj.style.opacity = "";
					}
				});
			}
			
			// End of object function
		} else {
			
			if (e==null) e=function(){};
			// Add none object function	
			
			/// Update looping function
			e.update = function(opt)
			{
				if (opt.fps == null) opt.fps = 10;
				if (opt.totalTime == null) opt.totalTime = 0;
				
				opt.deltaTime = 1000/opt.fps;
				opt.totalTime += opt.deltaTime;
				
				if (opt.fn(opt)) {
					var self = this;
					setTimeout(function() {
						self.update(opt);
					}, (1000/opt.fps));		
				}
			}
			
			/// Wait until time and run
			e.wait = function (opt) {
				setTimeout(function() {
					opt.fn()
				}, opt.time);
			}
			
			// Print Debug to debugger
			e.debug = function(msg) {
				var div = document.createElement("div");
				div.innerHTML = msg;
				var dbg = document.getElementById('debugger');
				dbg.insertBefore(div, dbg.firstChild);
				
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
			};
			
			// Ajax function
			e.ajax = function (opt) {
				var xml;
				if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xml=new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xml=new ActiveXObject("Microsoft.XMLHTTP");
				}
				var fn = opt.fn;
				xml.onreadystatechange = function () {
					if (xml.readyState==4) {
						debug('Request Received');
						if (fn != null) fn(xml.responseText);	
					}
				}
				xml.open("GET",opt.url,true);
				xml.send();
			}
		}
		return e;
	};
*/