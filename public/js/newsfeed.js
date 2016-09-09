
var columnWidth = 330;
var columnHeight = new Array();
var delayFeed = null;

function refillColumn()
{
    columnHeight = new Array();
    var conWidth = $(".container").width();
    var maxCol = Math.ceil(conWidth/columnWidth);
    for (var i = 0; i < maxCol; i++)
    {
        columnHeight.push(0);
    }
}

function feedConstruct()
{
    refillColumn();

    $(".feed").css('position', 'relative');

    $(".feed-item").each(function(index) {
        var id = 0;
        for (var i = 1; i < columnHeight.length; i++)
        {
            if (columnHeight[id] > columnHeight[i])
            {
                id = i;
            }
        }
        var height = $(this).height() + 20;

        $(this).css("position", "absolute");
        $(this).css("left", id*columnWidth);
        $(this).css("top", columnHeight[id]);

        columnHeight[id] += height;
    });
}

function feedResize()
{
    clearTimeout(delayFeed);
    delayFeed = setTimeout(feedConstruct, 300);
}

$( document ).ready(function()
{
    feedConstruct();

    $(window).resize(function() {
        feedResize();
    });
});
