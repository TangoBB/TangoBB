window.onload = function() {


	var get_url = 'applications/extensions/shoutbox/access.php';
	var post_url = 'applications/extensions/shoutbox/post.php';

	

	$('#shoutbox_posts').load(get_url);	
	

	
	
	function reload_shouts() {
		$('#shoutbox_posts').load(get_url, function(){  
			return true;
		});
	}
	
	setInterval(reload_shouts, 3000);

	$('#shoutbox').submit(function() {
	
		$.post(post_url, $('#shoutbox').serialize(), function(data) {
			$('#shoutbox_posts').load(get_url);
			$('#shout').val('');
		});
		$('#shout').val('');
		return false;
	});

}

	function delete_shout(shout_id) {
		var delete_url = 'applications/extensions/shoutbox/delete.php';
		$.post(delete_url, { id: shout_id }, function(data) {
			$('#shoutbox_posts').load('applications/extensions/shoutbox/get.php');
		});
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/* Sean Davies - http://seandavies.pw */
	
