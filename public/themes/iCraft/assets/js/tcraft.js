$('.tooltip_toggle').tooltip();
$('#wrapper').click(function() {
	$('#login_cont .container').slideUp('fast');
});
$('ul.nav li.dropdown').hover(function() {
	$(this).find('.dropdown-menu').stop(true, true).delay(200).slideDown('fast');
}, function() {
	$(this).find('.dropdown-menu').stop(true, true).delay(200).slideUp('fast');
});
$('#nav').affix({
	offset: {
		//top: 0
		top: $('#top_login').height() + $('#top-wrapper').height()
	}
});
$('#nav').css('z-index', '1000');
$('#toggle_login a').click(function() {
	if( $('#login_cont .container').is(':visible') ) {
		$('#login_cont .container').slideUp('fast');
	} else {
		$('#login_cont .container').slideDown('fast');
	}
});
$('.toggle_theme_editor').click(function() {
	$('.theme_editor').slideToggle('fast');
});
if($.cookie('tcraft_theme_css')) {
	$("link.tcraft_css").attr("href",$.cookie('tcraft_theme_css'));
}
$(".theme_editor li a").click(function() { 
	//alert($(this).attr('rel'));
	$('link.tcraft_css').attr("href", $(this).attr('rel'));
	$.cookie('tcraft_theme_css', $(this).attr('rel'), {expires: 365, path: '/'});
	return false;
});