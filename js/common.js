// JavaScript Document

var newsFeed = Array(),
	newsFeedSection = Array(),
	newsFeedBodyWidth = 500,
	newsFeedCurWidth = 0,
	newFeedMargin = 20,
	newsFeedWidth = 440+newFeedMargin,
	newsFeedPage = 2,
	btnMoreEvent = false,
	controlpanelmoving = false,
	controlpanelopen = false,
	linkColor = "#427fed";

function newsFeedConstruct() {
	var article = $("#article");
	
	// Handle Article when not found (when not in home)
	if (!article) return false;
	
	// Remove old section
	$("*#feed").each(function(index, element) {
		article.append(element);
	});
	for( var i=0 ; i<newsFeedSection.length ; i++ ) {
		newsFeedSection[i].remove();
	}
	
	// Create new section
	for(var i=0;i<newsFeedBodyWidth/newsFeedWidth;i++) {
		newsFeedSection[i] = $("<div></div>").addClass("section");
		newsFeedSection[i].css("width",(newsFeedWidth-newFeedMargin)+"px");
		article.append(newsFeedSection[i]);
	}
	
	// Registering feed
	$.merge(newsFeed, $("*#feed").removeAttr("id"));
	
	// Put feed in each section
	$(newsFeed).each(function(index, element) {
        newsFeedSection[index%(newsFeedBodyWidth/newsFeedWidth)].append($(element));
    });
	
	// Handle news feed hover
	// Placed here to recorver event handler when feed is moved
	$(newsFeed).hover(function(e) {
		//$(this).find(".chapter-container").css("box-shadow","0 0 0 rgba(0,0,0,0)").css("background","#FFF").animate({boxShadow : "0 0 20px rgba(0,0,0,0.2)",backgroundColor: "#f5f5f5"},200);
		$(this).find("a.nf_title, a.title").css("color","#404040").css("margin-right","7px").css("padding-right",55).animate({color:linkColor, marginRight:"55px", paddingRight:"7px"},200); 
	}, function(e) {
		//$(this).find(".chapter-container").css("box-shadow","0 0 20px rgba(0,0,0,0.2)").css("background","#f5f5f5").animate({boxShadow : "0 0 0 rgba(0,0,0,0)",backgroundColor: "#fff"},200);
		$(this).find("a.nf_title, a.title").css("color",linkColor).css("margin-right","55px").css("padding-right","7px").animate({color:"#404040", marginRight:"7px", paddingRight:"55px"},200);
	});
	
	return true;
}
// Automatic more in newsfeed
function btnMore() {
	// Handle if button is triggered when ajax is still not complete loading
	if (btnMoreEvent) return false;
	btnMoreEvent = true;
	
	var btnmore = $("#nfmore");
	
	// Handle when btnmore not found (when not in home)
	if (!btnmore) return false;
	
	btnmore.html("Loading...");
	$.ajax({
		url:"application/news_feed.php",
		data:{
			index: newsFeedPage
		},
		success: function(e) {
			btnMoreEvent = false;
			$("#article").append($("<div></div>").html(e));
			newsFeedConstruct();
			newsFeedPage++;
			btnmore.html("More");
		}
	});
}

$(document).ready(function(e) {
	
	// Prevent href with link # to work
	$("a[href=\"#\"]").click(function(e) {
        e.preventDefault();
    });
	
	// Resize body multiple 440
	newsFeedBodyWidth = $(window).width()>newsFeedWidth?($(window).width()-($(window).width()%newsFeedWidth)):newsFeedWidth;
	if (newsFeedBodyWidth > newsFeedWidth*3) newsFeedBodyWidth = newsFeedWidth*3;
	newsFeedCurWidth = newsFeedBodyWidth;
	$("#body").width(newsFeedBodyWidth);
	
	// Construct News Feed
	newsFeedConstruct();
	
    // handle when window is resized
	$(window).resize(function(e) {
		newsFeedBodyWidth = $(window).width()>newsFeedWidth?($(window).width()-($(window).width()%newsFeedWidth)):newsFeedWidth;
		if (newsFeedBodyWidth > newsFeedWidth*3) newsFeedBodyWidth = newsFeedWidth*3;
		if (newsFeedCurWidth != newsFeedBodyWidth) {
			newsFeedCurWidth = newsFeedBodyWidth; 
			$("#body").width(newsFeedBodyWidth);
			
			newsFeedConstruct();
		}
    });
	
	$("#nfmore").click(function(e) {
	// Handle btn more in news feed
        btnMore();
    });
	
	// Handle error check
	$("*#err-img-chk").click(function(e) {
        if ($(this).is(":checked"))
		{
			$.ajax({
				url:"handler/pict_error_report.php?id="+$(this).attr("data-id")+"&chk=1"
			});		
		}
		else
		{
			$.ajax({
				url:"handler/pict_error_report.php?id="+$(this).attr("data-id")+"&chk=0"
			});
		}
    });
	
	// Handle when scrolling
	$(window).scroll(function(e) {
		if ($("#nfmore").length != 0)
			if ($(window).scrollTop()+$(window).height() >= $("#nfmore").offset().top) {
				btnMore();	
			}
    });
	
	// Handle control panel close click
	$(".controlpanel a[href=\"#close\"]").click(function(e) {
        e.preventDefault();
		$(".controlpanel").mouseleave();
    });
	// Handle Control panel mouseenter
	var controlpanelTimer = null;
	$(".controlpanel a").click(function(e) {
    	if (!$(".controlpanel").hasClass("hover")) {
			return false;	
		}
    });
	$(".controlpanel").hover(function(e) {
		controlpanelTimer = setTimeout(function() {
		if (!controlpanelmoving && !controlpanelopen) {
			controlpanelmoving = true;
			$(".controlpanel").addClass("hover");
			$(".controlpanel").css('margin-left',-$(".controlpanel").outerWidth()).animate({
				'margin-left':'0px'
			},200, function() {
				controlpanelopen = true;
				controlpanelmoving = false;	
			});
		}}, 200);
	}, function(e) {
		if (controlpanelTimer != null) { clearTimeout(controlpanelTimer); controlpanelTimer = null; }
		if (!controlpanelmoving && controlpanelopen) {
			controlpanelmoving = true;
			controlpanelopen = false;
			
			$(".controlpanel").css('margin-left',0).animate({
				'margin-left':-$(".controlpanel").outerWidth()
			},200, function() {
				$(".controlpanel").css("display","block");
				$(".controlpanel").removeClass("hover");
				$(".controlpanel").css('margin-left',0);
				controlpanelmoving = false;	
			});
		}
	});
	
	/*$(".controlpanel").mouseenter(function(e) {
		if (!controlpanelmoving && !controlpanelopen) {
			controlpanelmoving = true;
			controlpanelopen = true;
			
			$(".controlpanel").addClass("hover");
			$(".controlpanel").css('margin-left',-$(".controlpanel").outerWidth()).animate({
				'margin-left':'0px'
			},200, function() {
				controlpanelmoving = false;	
			});
		}
    });
	*/
	/*
	// Handle Control panel mouseleave
	$(".controlpanel").mouseleave(function(e) {
		if (!controlpanelmoving && controlpanelopen) {
			controlpanelmoving = true;
			controlpanelopen = false;
			
			$(".controlpanel").css('margin-left',0).animate({
				'margin-left':-$(".controlpanel").outerWidth()
			},200, function() {
				$(".controlpanel").removeClass("hover");
				$(".controlpanel").css('margin-left',0);
				controlpanelmoving = false;	
			});
		}
	});
	*/
	// Handle when image is error
	$("*[data-report=\"error\"]").error(function(e) {
        console.log("Error on: "+$(this).attr("data-id"));
		$.ajax({
			url:"handler/pict_error_report.php?id="+$(this).attr("data-id")
		});
    });
});