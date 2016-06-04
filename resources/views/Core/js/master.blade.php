$(document).ready(function() {

	var loader = '{{ asset('assets/img/ajax-loader.gif') }}';

	//BBCode Editor
	$.getScript('{{ asset('assets/js/wysibb/jquery.wysibb.min.js') }}', function() {
		var wbbOpt = {
			buttons: "{{ $editor_buttons }}"
		};
		$('#bbcode_editor').wysibb(wbbOpt);
		//$('#bbcode_editor').val($('#bbcode_editor').bbcode());
	});

	$('form[data-process-method="json"]').on('submit', function(e) {
		e.preventDefault();

		//$().after();
		var alertSuccess = $('div[data-type="alert-success"]');
		var alertFailure = $('div[data-type="alert-fail"]');

		var loadSpan     = $('span[data-content="loader"]');
		loadSpan.html(' <img src="' + loader + '" />');

		var contentId    = $(this).attr('data-content-id');

		var action      = $(this).attr('data-process-action');
		var processUrl;
		var redirectUrl = null;
		var showHtml    = false;

		//var serialData = $(this).find('input, select, textarea').serialize();
		if($('#bbcode_editor').length)
		{
			$('#bbcode_editor').val($('#bbcode_editor').bbcode());
		}

		var serialData = $(this).serialize();
		console.log(serialData);
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
						console.log(msg.action.displayText);
						if( showHtml === false )
						{
							alertSuccess.html(msg.action.displayText).show();
						}
						else
						{
							$('div[data-content-type="html-content"]').html(msg.action.displayText);
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

});
