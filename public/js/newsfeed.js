
var columnWidth = 330;
var columnHeight = new Array();
var delayFeed = null;
var feed = 0;
var feedLoading = false;

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

function getShortestColumn()
{
    var id = 0;
    for (var i = 1; i < columnHeight.length; i++)
    {
        if (columnHeight[id] > columnHeight[i])
        {
            id = i;
        }
    }
    return id;
}

function getHighestColumn()
{
    var id = 0;
    for (var i = 1; i < columnHeight.length; i++)
    {
        if (columnHeight[id] < columnHeight[i])
        {
            id = i;
        }
    }
    return id;
}

function feedConstruct()
{
    refillColumn();

    $(".feed").css('position', 'relative');

    $(".feed-item").each(function(index) {
        var id = getShortestColumn();
        var height = $(this).outerHeight() + 15;

        $(this).css("position", "absolute");
        $(this).css("left", id*columnWidth);
        $(this).css("top", columnHeight[id]);
        $(this).show();

        columnHeight[id] += height;
    });

    var highest = getHighestColumn();
    $(".load-more").css("position", "absolute");
    $(".load-loading").css("position", "absolute");
    $(".load-more").css("top", columnHeight[highest]);
    $(".load-loading").css("top", columnHeight[highest]);
    $(".load-more").show();
    $(".load-loading").hide();
}

function feedResize()
{
    clearTimeout(delayFeed);
    delayFeed = setTimeout(feedConstruct, 250);
}

function getFeed()
{
    if (!feedLoading)
    {
        $(".load-more").hide();
        $(".load-loading").show();
        feedLoading = true;
        $.ajax({
            "method": "POST",
            url: baseUrl+"home/feed",
            data: {
                'page': feed,
                'nofeed': $(".body .container .feed").length
            }
        }).done(function(msg) {
            msg = $.trim(msg);
            feed++;
            feedLoading = false;

            if (msg=="1")
            {
                console.log(feed);
                getFeed();
            }
            else if (msg=="0")
            {
                $(".load-more").hide();
                $(".load-loading").hide();
            }
            else
            {
                if ($(".body .container .feed").length)
                {
                    $(".body .container .feed").append(msg);
                }
                else
                {
                    $(".body .container").append(msg);
                    $(".load-more").click(function()
                    {
                        getFeed();
                    });
                }
                feedResize();
            }
        });
    }
}

$( document ).ready(function()
{
    getFeed();
    $(window).resize(function() {
        feedResize();
    });
});
