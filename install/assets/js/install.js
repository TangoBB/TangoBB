/*
 * Installation Js for TangoBB
 */
function ajaxLoad(url) {
    var load_msg = '<div class="panel-body"><div align="center"><img src="assets/img/load.gif" alt="Loading..." /></div></div>';
    $('#main_content').html(load_msg).load(url);
    return false;
}

function ajaxForm(formUrl) {

    var load_msg = '<div class="panel-body"><div align="center"><img src="assets/img/load.gif" alt="Loading..." /></div></div>';

    var formData = $('form.ajaxForm').serialize();
    $('#main_content').html(load_msg);
    $.ajax({
        type: "POST",
        url: formUrl,
        data: formData,
        success: function (data) {
            $('#main_content').html(data);
        },
        error: function (xhr, status) {
            $('#main_content').html('<div class="alert alert-danger">Error, please try again later. <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>').fadeIn('0.8');
        }
    });
    return false;
}
