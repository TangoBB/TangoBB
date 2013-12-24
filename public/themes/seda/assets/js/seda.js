$("#wrap #member").last().css("border-bottom","2px solid #CCCCCC");
	$("a.member_list_link:contains('(Banned)')").css('color', '#888888').css('text-decoration', 'line-through');
	$("div.activity:empty").prepend('<div class="no_activity">User has not posted or replied to any threads</div>');
	$("pre.signature:empty").prepend('<div class="no_activity">User has not set a signature</div>');
	$("input[name=edit]").addClass('button medium green right');
	$( "textarea	" ).wrap( "<span class='textarea_container'>" );
	//$(".alert").delay(5000).fadeOut('slow')

	$('.reply').click(function() {
		$('iframe body').focus();
	});
	
	// LOGIN POPUP BOX - START //
	$("#toggleLogin").click(function (e) {
		$("#loginBox").fadeIn().show();
		$('#email').focus();
	});
	
	$("#mask").click(function (e) {
		$("#loginBox").fadeOut();
	});
	
	$(document).keydown(function (e) {
		if (!$(this).is(":hidden") && e.keyCode === 27) {
			$("#loginBox").fadeOut();
		}
	});	
	// LOGIN POPUP BOX - END //