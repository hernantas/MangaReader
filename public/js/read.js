
var imgWidth = new Array();
var imagePadding = 100;

function imageWidth()
{
    var width = $(window).width() - imagePadding;
    $(".img_flex").each(function(index)
    {
        if (width != imgWidth[index])
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
        $(this).show();
    });
    $(".data-progress").remove();
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
        imgWidth.push($(this).attr("width"));
    });
    imageWidth();
    $(window).resize(function(){
        imageWidth();
    });
});
