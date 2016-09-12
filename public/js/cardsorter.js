
var cardColumnWidth = 220;
var cardColumn = new Array();
var cardTimer = null;
var cardSortDelay = 250;

function resetColumn()
{
    cardColumn = new Array();
    var containerWidth = $(".container").width();
    var maxCol = Math.ceil(containerWidth/cardColumnWidth);
    for (var i = 0; i < maxCol; i++)
    {
        cardColumn.push(0);
    }
}

function getShortestColumn()
{
    var shortest = 0;
    for (var i = 1; i < cardColumn.length; i++)
    {
        if (cardColumn[i] < cardColumn[shortest])
        {
            shortest = i;
        }
    }
    return shortest;
}

function getHighestColumn()
{
    var highest = 0;
    for (var i = 1; i < cardColumn.length; i++)
    {
        if (cardColumn[i] > cardColumn[highest])
        {
            highest = i;
        }
    }
    return highest;
}

function sortCard()
{
    resetColumn();

    $('.card').each(function(index)
    {
        var id = getShortestColumn();
        var height = $(this).outerHeight() + 15;

        $(this).css("position", "absolute");
        $(this).css("left", id*cardColumnWidth);
        $(this).css("top", cardColumn[id]);
        $(this).show();

        cardColumn[id] += height;
    });

    var container = $('.card').parent();
    container.css('position', 'relative');
    container.height(cardColumn[getHighestColumn()]);
}

function delaySortCard()
{
    clearTimeout(cardTimer);
    cardTimer = setTimeout(sortCard, cardSortDelay);
}

$(document).ready(function()
{
    sortCard();

    $(window).resize(function()
    {
        delaySortCard();
    });
});
