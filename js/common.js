// JavaScript Document

(function(a){(jQuery.browser=jQuery.browser||{}).mobile=/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))})(navigator.userAgent||navigator.vendor||window.opera);

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

$(document).ready(function(e) 
{
	
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