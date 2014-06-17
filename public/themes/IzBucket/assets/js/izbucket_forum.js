$('.main_nav').hover(function() {
		$(this).animate({
			opacity: 1
		}, 250);
	}, function() {
		$(this).delay(500).animate({
			opacity: 0.1
		}, 250);
	});

	$('ul.nav li.dropdown').hover(function() {
		$(this).find('.dropdown-menu').stop(true, true).delay(200).slideDown('fast');
	}, function() {
		$(this).find('.dropdown-menu').stop(true, true).delay(200).slideUp('fast');
	});
	$('.toggle_tooltip').tooltip();