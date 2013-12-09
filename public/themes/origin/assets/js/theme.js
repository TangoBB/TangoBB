/*function origin_sign_in_slide() {
    if( $('#slide_signin').is(':visible') ) {
        $('#slide_signin').slideToggle('fast');
        $('#slide_signin').click(function(e) {
            e.stopPropagation();
        });
        $('#page_body').click(function() {
            $('#slide_signin').slideUp('fast');
        });
    } else {
        $('#slide_signin').slideToggle('fast');
    }
}*/
$('.btn-group > .trigger').popover({
    html: true,
    title: function () {
        return $(this).parent().find('.alert-title').html();
    },
    content: function () {
        return $(this).parent().find('.alert-content').html();
    },
    placement: 'bottom'
});
function origin_sign_in_slide() {
    if( $('#slide_signin').is(':visible') ) {
        $('#slide_signin').slideUp('fast');
    } else {
        $('#slide_signin').slideToggle('fast');
    }
    $('#page_body').click(function() {
        $('#slide_signin').slideUp('fast');
    });
}