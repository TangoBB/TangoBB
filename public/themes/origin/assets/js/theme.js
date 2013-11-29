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