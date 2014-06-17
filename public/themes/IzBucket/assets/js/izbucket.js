$(document).ready(function() {
	$('.slide_page.home .msg').css({
 		top:'50%', left:'50%', margin:'-' + ($('.slide_page.home .msg').height() / 2)+'px 0 0 -' + ($('.slide_page.home .msg').width() / 2) + 'px'
 	});

 	/*
 	 * Hiding Nav Bar
 	 */
	$('.main_nav').hover(function() {
		$(this).animate({
			opacity: 1
		}, 250);
	}, function() {
		$(this).delay(500).animate({
			opacity: 0.2
		}, 250);
	});

});
//Tooltip
$(function() {
  //Tooltip
  $('.toggle_tooltip').tooltip();
});

/*
 * Smooth Scrolling
 */
$(function() {
  $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});