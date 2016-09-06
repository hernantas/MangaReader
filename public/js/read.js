
$(document).ready(function() {
    $(document).keyup(function(e) {
        if (e.keyCode === 37) window.location.href = prevPage;
        if (e.keyCode === 39) window.location.href = nextPage;
    });

    $(".jump").on('change', function() {
        window.location.href = baseUrl + this.value;
    });
});
