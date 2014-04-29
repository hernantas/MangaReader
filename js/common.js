
var bpath="",
	newsFeed = Array(),
	newsFeedPage = 1,
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
		newsFeedSection[i].css("width",(newsFeedWidth)+"px");
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

function body_resize()
{
	newsFeedBodyWidth = $(window).width()>newsFeedWidth?($(window).width()-($(window).width()%newsFeedWidth)):newsFeedWidth;
	if (newsFeedBodyWidth > newsFeedWidth*3) newsFeedBodyWidth = newsFeedWidth*3;
	if (newsFeedCurWidth != newsFeedBodyWidth) {
		newsFeedCurWidth = newsFeedBodyWidth; 
		$("#body").width(newsFeedBodyWidth);
		
		newsFeedConstruct();
	}	
}

$(document).ready(function() 
{
	body_resize();
	$(window).resize(function() { body_resize(); });
	
	var loadingfeed = false;
	$("#newsfeedmore").click(function(evt)
	{
		if (!loadingfeed) {
			loadingfeed = true;
			$(this).html("Loading");
			newsFeedPage++;
			$.ajax({
				url: "newsfeed/page/"+newsFeedPage+"/ajax",
				success: function(e) {
					$("#article").append();
					$("#article").append($("<div></div>").html(e));
					newsFeedConstruct();
					$(this).html("More...");
					loadingfeed = false;
				}
			});
		}
		evt.preventDefault();
	});
	
	$("#selectnavigation").change(function()
	{
		window.location = bpath+"/manga/"+$(this).val();
	});
});
