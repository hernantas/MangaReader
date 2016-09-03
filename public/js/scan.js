
var scan_timer = 0;
var scan_counter = 0;

function writeInfo(msg)
{
    $(".info_debug").html("<div>"+msg+"</div>");
}

function checkStatusScan()
{
    $.ajax({
        method: "POST",
        url: baseUrl+"admin/scan/status"
    }).done(function(msg) {
        writeInfo(msg);

        var data = $.parseJSON(msg);
        if (data.result != "done")
        {
            scan_timer += parseFloat(data.time);
            scan_counter++;
            $(".time_debug").html("<div>"+scan_counter+": "+scan_timer+"</div>");
            checkStatusScan();
        }
    });
}

$(document).ready(function() {
    if ($(".scan_start").length)
    {

    }
    else
    {
        checkStatusScan();
    }
});
