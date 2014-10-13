$('.tooltip_toggle').tooltip({
	placement: 'right'
});

$('.dropdown-menu').find('form').click(function (e) {
	e.stopPropagation();
});

$('input[type="text"]').addClass('form-control');

$('input[type="password"]').addClass('form-control');

$('textarea').addClass('form-control');

$('input[type="submit"]').addClass('btn btn-primary');

$('select').addClass('form-control');