
var imgWidth = new Array();

function imageWidth()
{
    var width = $(window).width() - fixedPadding;
    $(".img_flex").each(function(index)
    {
        if (width != $(this).width())
        {
            if (width < imgWidth[index])
            {
                $(this).width(width);
            }
            else
            {
                $(this).width(imgWidth[index]);
            }
        }
    });
}

$(document).ready(function() {
    $(document).keyup(function(e) {
        if (e.keyCode === 37) window.location.href = prevPage;
        if (e.keyCode === 39) window.location.href = nextPage;
    });

    $(".jump").on('change', function() {
        window.location.href = baseUrl + this.value;
    });

    $(".img_flex").each(function(index)
    {
        imgWidth.push($(this).width());
    });
    imageWidth();
    $(window).resize(function(){
        imageWidth();
    });
});
