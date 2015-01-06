$('.tooltip_toggle').tooltip({
	placement: 'right'
});

$('.dropdown-menu').find('form').click(function (e) {
	e.stopPropagation();
});