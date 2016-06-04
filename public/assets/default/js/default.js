$(document).ready(function() {
	$('.header-jumbotron').css("color", isDark($('.header-jumbotron').css("background-color")) ? 'white' : 'black');

	$('a[data-effect="edit"]').on('click', function(e) {
		e.preventDefault();
		var postId = $(this).attr('data-post-id');
		$('div[data-edit-id="' + postId + '"]').slideToggle();
	});
});

function isDark( color ) {
	var match = /rgb\((\d+).*?(\d+).*?(\d+)\)/.exec(color);
	return parseFloat(match[1])
	+ parseFloat(match[2])
	+ parseFloat(match[3])
	< 3 * 256 / 2;
}
