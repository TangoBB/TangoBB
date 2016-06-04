$(document).ready(function() {

	var loader = '{{ asset('assets/img/ajax-loader.gif') }}';

	//BBCode Editor
	$.getScript('{{ asset('assets/js/wysibb/jquery.wysibb.min.js') }}', function() {
		var wbbOpt = {
			buttons: "{{ $editor_buttons }}"
		};
		$('textarea.bbcode_editor, #bbcode_editor').wysibb(wbbOpt);
	});

	$('form[data-process-method="json"]').on('submit', function(e) {
		e.preventDefault();

		//$().after();
		var alertSuccess  = $('div[data-type="alert-success"]');
		var alertFailure  = $('div[data-type="alert-fail"]');

		var loadSpan      = $('span[data-content="loader"]');
		loadSpan.html(' <img src="' + loader + '" />');

		var contentId     = $(this).attr('data-content-id');

		var action        = $(this).attr('data-process-action');
		var processUrl;
		var redirectUrl   = null;
		var showHtml      = false;
		var updateContent = false;

		//$('textarea.bbcode_editor').sync();
		if( $('textarea.bbcode_editor').length > 0 )
		{
			$('textarea.bbcode_editor').each(function() {
				$(this).sync();
			});
		}

		if( $('#bbcode_editor').length > 0 )
		{
			$('#bbcode_editor').sync();
		}

		var serialData = $(this).serialize();
		//console.log($(this).serialize());

		switch( action ) {
			case 'login':
			processUrl = '{{ route('Json::Account::LogIn') }}';
			//processUrl = '{{ url('json/account/login') }}';
			//processUrl = '/json/account/login';
			break;
			case 'signup':
			processUrl = '{{ route('Json::Account::SignUp') }}';
			break;

			case 'thread.create':
			processUrl = '{{ url('json/forum/thread/create/') }}/' + contentId;
			break;

			case 'thread.reply':
			processUrl = '{{ url('json/forum/thread/reply/') }}/' + contentId;
			showHtml   = true;
			break;

			case 'post.edit':
			processUrl    = '{{ url('json/forum/thread/editpost/') }}/' + contentId;
			updateContent = true;
			break;
		}

		var request = $.ajax({
			url: processUrl,
			method: 'post',
			data: serialData,
			crossDomain: false,
			statusCode: {
				404: function() {
					loadSpan.html('');
					alertFailure.html('{{ trans('messages.global.404') }}').show();
				},
				403: function() {
					loadSpan.html('');
					alertFailure.html('{{ trans('messages.global.403') }}').show();
				},
				405: function() {
					loadSpan.html('');
					alertFailure.html('{{ trans('messages.global.405') }}').show();
				}
			}
		});

		request.done(function(msg) {
			console.log(msg);
			var msg = jQuery.parseJSON(msg);
			if( msg.success == 1 )
			{
				loadSpan.html('');
				if( typeof msg.action != 'undefined' && msg.action != null )
				{
					if( typeof msg.action.displayText != 'undefined' && msg.action.displayText != null )
					{
						if( showHtml )
						{
							$('div[data-content-type="html-content"]').html(msg.action.displayText);
						}
						else if( updateContent )
						{
							$('div[data-display-id="' + contentId + '"]').html(msg.action.displayText);
							alert(msg.action.additionalAlert);
						}
						else
						{
							alertSuccess.html(msg.action.displayText).show();
						}
					}

					if( typeof msg.action.redirect != 'undefined' && msg.action.redirect != null )
					{
						window.location.replace(msg.action.redirect);
					}
				}
			}
			else
			{
				loadSpan.html('');
				var outMsg = '';
				for( i = 0; i < msg.message.length; i++ )
				{
					outMsg += '<li>' + msg.message[i] + '</li>';
				}

				alertFailure.html('<ul>' + outMsg + '</ul>').show();
			}
		});

request.fail(function(jqXHR, textStatus) {
	loadSpan.html('');
	alertFailure.html(textStatus).show();
});
});

	//Ajax Links
	$('a[data-action="delete-thread"]').on('click', function(e) {
		e.preventDefault();

		var contentId = $(this).attr('data-thread-id');

		var request = $.ajax({
			url: '{{ url('json/forum/thread/delete/') }}/' + contentId,
			method: 'get',
			crossDomain: false,
			statusCode: {
				404: function() {
					loadSpan.html('');
					alertFailure.html('{{ trans('messages.global.404') }}').show();
				},
				403: function() {
					loadSpan.html('');
					alertFailure.html('{{ trans('messages.global.403') }}').show();
				},
				405: function() {
					loadSpan.html('');
					alertFailure.html('{{ trans('messages.global.405') }}').show();
				}
			}
		});

		request.done(function(msg) {
			var msg = jQuery.parseJSON(msg);
			if( msg.success == 1 )
			{
				$('div[data-thread-id="' + contentId + '"]').fadeOut();
			}
		});

		request.fail(function(jqXHR, textStatus) {
			loadSpan.html('');
			alertFailure.html(textStatus).show();
		});
	});

});
