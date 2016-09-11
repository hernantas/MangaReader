var ipage = 0;
var dbname = '';
var runtime = 0;

function import0()
{
    $.ajax({
        method: "POST",
        url: baseUrl+"admin/import/import0",
        data: {
            "dbname": dbname,
            page: ipage
        }
    }).done(function(msg) {
        ipage++;
        try
        {
            var data = $.parseJSON(msg);
            runtime += data.time;
            $(".data-time").html("Runtime: "+runtime.toFixed(2)+"s");

            if (data.result!="done")
            {
                import0();
            }
        }
        catch(e)
        {
            $(".data-progress").append("<div>"+msg+"</div>");
        }
    });
}

$(document).ready(function()
{
    var runtime = 0;
    $(".import0").click(function() {
        dbname = $(".import0Text").val();
        $(".data-action").html("");
        $(".data-progress").parent().show();
        import0();
    });
});
