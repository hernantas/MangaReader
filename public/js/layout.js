
var fixedWidth = 220;
var fixedPadding = 100;

function layoutWidth()
{
    if ($(".container").length)
    {
        var width = $(window).width()-fixedPadding;
        $(".container").width(Math.floor(width / fixedWidth) * fixedWidth);
    }
}

$(document).ready(function() {
    layoutWidth();

    $(window).resize(function(){
        layoutWidth();
    });
});
