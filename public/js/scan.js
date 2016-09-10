
var scan_timer = 0;
var scan_counter = 0;

function addTime(time)
{
    scan_timer += parseFloat(time);
    scan_counter++;
    $(".time_debug").html("<div>Duration: "+scan_timer.toFixed(2)+"s</div>");
    $(".time_debug").append("<div>Average: "+(scan_timer/scan_counter).toFixed(2)+"s</div>");
}

function checkStatusScan()
{
    $.ajax({
        method: "POST",
        url: baseUrl+"admin/scan/status"
    }).done(function(msg) {
        try
        {
            var data = $.parseJSON(msg);
            if (data.result != "done")
            {
                addTime(data.time);
                for (var i = 0; i < data.warning.length; i++)
                {
                    $(".warning_debug").append("<div><b>Warning: </b>"+data.warning[i]+"</div>");
                }
                checkStatusScan();
            }
            else
            {
                $(".loader").html("<h3>Scan Completed</h3>");
            }
        }
        catch(err)
        {
            $(".loader").remove();
            $(".info_debug").html("<div>"+msg+"</div>");
        }
    });
}

$(document).ready(function() {
    if (!$(".scan_start").length)
    {
        checkStatusScan();
    }
});
