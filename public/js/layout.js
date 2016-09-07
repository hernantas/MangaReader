
var fixedWidth = 220;
var minWidth = 660;
var fixedPadding = 100;

function layoutWidth()
{
    var wWidth = $(window).width();
    var bodyPadding = Math.max(Math.floor(wWidth / fixedWidth)-3, 1) * 100;
    if ($(".container").length)
    {
        var width = wWidth - bodyPadding;
        var newWidth = Math.floor(width / fixedWidth) * fixedWidth;
        if (newWidth < minWidth) newWidth = minWidth;
        $(".container").width(newWidth);
    }
}

$(document).ready(function() {
    layoutWidth();

    $(window).resize(function(){
        layoutWidth();
    });
});
