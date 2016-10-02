
var columnWidth = 330;
var columnHeight = new Array();
var delayFeed = null;
var feedPage = 0;
var feedList = new Array();
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

    $(feedList).each(function(index) {
        var id = getShortestColumn();
        var height = $(this).outerHeight() + 15;

        $(this).css("position", "absolute");
        $(this).css("left", id*columnWidth);
        $(this).css("top", columnHeight[id]);

        columnHeight[id] += height;
    });

    var highest = getHighestColumn();
    var btnMore = $(".load-more");
    var btnLoading = $(".load-loading");
    btnMore.css("position", "absolute");
    btnMore.css("top", columnHeight[highest]);
    btnMore.show();
    btnLoading.css("position", "absolute");
    btnLoading.css("top", columnHeight[highest]);
    btnLoading.hide();
}

function feedResize()
{
    clearTimeout(delayFeed);
    delayFeed = setTimeout(feedConstruct, 250);
}

function createFeed()
{
    var container = $("<div class=\"panel feed-item\"><div class=\"warp\"></div></div>");
    var title = $("<a class=\"title\"></a>");
    var date = $("<div class=\"desc\"></div>");
    var content = $("<div class=\"warp content\"></div>");
    var img = $("<div></div>");
    var footer = $("<div class=\"footer\"></div>");
    container = container.children(0);
    container.append(title);
    container.append(date);
    container.append(content);
    container = container.parent();
    container.append(img);
    container.append(footer);
    return {
        'container': container,
        'title': title,
        'date': date,
        'content': content,
        'img': img,
        'footer': footer
    };
}

function createFromJson(data)
{
    var elemFeed = $(".feed");
    var feeds = data.feed;
    $.each(feeds, function(i, feed)
    {
        var newFeed = createFeed();

        newFeed.title.attr('href', baseUrl+"manga/"+feed.fname);
        newFeed.title.html(feed.name);

        newFeed.date.html(feed.date);

        $.each(feed.data, function(i, chapter) {
            newFeed.content.append($("<div><a href=\""+baseUrl+"manga/"+feed.fname+"/chapter/"+
                chapter.friendly_name+"\">"+chapter.name+"</a></div>"));
        });

        if (feed.more == true)
        {
            newFeed.content.append($("<div>[<a href=\""+baseUrl+"manga/"+feed.fname+"\">more...</a>]</div>"));
        }

        $.each(feed.imgs, function(i, img)
        {
            newFeed.img.append("<img src=\""+img.path+"\" height=\""+img.size+"\" width=\""+img.size+"\" />");
        });

        var optAllChapter = $("<a href=\""+baseUrl+"manga/"+feed.fname+"\">All Chapters</a>");
        newFeed.footer.append(optAllChapter);

        elemFeed.append(newFeed.container);
        feedList.push(newFeed.container);
    });

    feedConstruct();
}

function getFeed()
{
    if (!feedLoading)
    {
        $(".load-more").hide();
        $(".load-loading").show();
        feedLoading = true;
        $.ajax({
            method: "POST",
            url: baseUrl+"home/feed",
            data: {
                page: feedPage
            }
        }).done(function(msg) {
            msg = $.trim(msg);
            feedPage++;
            feedLoading = false;
            var data;
            try
            {
                data = $.parseJSON(msg);
            }
            catch (e)
            {
                console.log(e);
            }
            createFromJson(data);
        });
    }
}

$( document ).ready(function()
{
    getFeed();
    $(window).resize(function() {
        feedResize();
    });

    $(".load-more").click(function()
    {
        getFeed();
    });
});
