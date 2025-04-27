
// pos-modern.js
$(document).ready(function() {

    $(document).on('click', '.pesanan-item', function() {
        $(".pesanan-item").removeClass('selected');
        $(this).addClass('selected');
    });

    $("button").hover(function() {
        $(this).css("box-shadow", "0px 4px 10px rgba(0,0,0,0.2)");
    }, function() {
        $(this).css("box-shadow", "none");
    });

    $("button.loading").click(function() {
        $(this).prop('disabled', true);
        $(this).append('<span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true"></span>');
    });

});
