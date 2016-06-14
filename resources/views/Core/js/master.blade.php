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

		//Check if form has multipart/form-data
		var enctype       = $(this).attr('enctype');

		//Get button what was clicked.
		var submitButton  = $(document.activeElement);
		if( submitButton.length && $(this).has(submitButton) && submitButton.is('button[type="submit"], input[type="submit"], input[type="image"]') )
		{
			submitButton = submitButton;
		}
		else
		{
			submitButton = $(this).find('input[type="submit"]');
		}
		//Disable the button
		submitButton.prop('disabled', true);

		//Get progress bars (if any).
		var uploadProgressBar   = $(this).find('div[data-progress-type="upload"]');//Percent value will be set on data-progress-value attribute. Set default to 0.
		var downloadProgressBar = $(this).find('div[data-progress-type="download"]');//Percent value will be set on data-progress-value attribute. Set default to 0.

		//$().after();
		var alertSuccess  = $('div[data-type="alert-success"]');
		var alertFailure  = $('div[data-type="alert-fail"]');

		var loadSpan      = $('span[data-content="loader"]');
		loadSpan.html(' <img src="' + loader + '" />');

		var action        = $(this).attr('data-process-action');
		var processUrl;
		var redirectUrl   = null;

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

		switch( action )
		{
			case 'login':
			processUrl = '{{ route('Json::Account::LogIn') }}';
			//Method will be ran when success message is received from the JSON.
			var successRequest  = function(msg) {
				alertSuccess.html(msg.action.displayText).show();
			};

			//Method will be ran when error message is received from the JSON.
			var errorRequest = function(msg) {
				var outMsg = '';
				for( i = 0; i < msg.message.length; i++ )
				{
					outMsg += '<li>' + msg.message[i] + '</li>';
				}

				alertFailure.html('<ul>' + outMsg + '</ul>').show();
			};
			break;

			case 'signup':
			processUrl = '{{ route('Json::Account::SignUp') }}';
			//Method will be ran when success message is received from the JSON.
			var successRequest  = function(msg) {
				alertSuccess.html(msg.action.displayText).show();
			};

			//Method will be ran when error message is received from the JSON.
			var errorRequest = function(msg) {
				var outMsg = '';
				for( i = 0; i < msg.message.length; i++ )
				{
					outMsg += '<li>' + msg.message[i] + '</li>';
				}

				alertFailure.html('<ul>' + outMsg + '</ul>').show();
			};
			break;

			case 'thread.create':
			var contentId     = $(this).attr('data-content-id');
			processUrl = '{{ url('json/forum/thread/create/') }}/' + contentId;
			//Method will be ran when success message is received from the JSON.
			var successRequest  = function(msg) {
				alertSuccess.html(msg.action.displayText).show();
			};

			//Method will be ran when error message is received from the JSON.
			var errorRequest = function(msg) {
				var outMsg = '';
				for( i = 0; i < msg.message.length; i++ )
				{
					outMsg += '<li>' + msg.message[i] + '</li>';
				}

				alertFailure.html('<ul>' + outMsg + '</ul>').show();
			};
			break;

			case 'thread.reply':
			var contentId     = $(this).attr('data-content-id');
			processUrl = '{{ url('json/forum/thread/reply/') }}/' + contentId;
			showHtml   = true;
			//Method will be ran when success message is received from the JSON.
			var successRequest = function(msg) {
				$('div[data-content-type="html-content"]').html(msg.action.displayText);
			};

			//Method will be ran when error message is received from the JSON.
			var errorRequest = function(msg) {
				var outMsg = '';
				for( i = 0; i < msg.message.length; i++ )
				{
					outMsg += '<li>' + msg.message[i] + '</li>';
				}

				alertFailure.html('<ul>' + outMsg + '</ul>').show();
			};
			break;

			case 'post.edit':
			var contentId     = $(this).attr('data-content-id');
			processUrl    = '{{ url('json/forum/thread/editpost/') }}/' + contentId;
			updateContent = true;
			var successRequest = function(msg) {
				$('div[data-display-id="' + contentId + '"]').html(msg.action.displayText);
				//$('[data-type="edit-success-icon"]').html('<i class="fa fa-check-circle"></i>').fadeIn().delay(500).fadeOut();
				$('[data-type="post-' + contentId + '-edit-success-symbol"]').fadeIn().delay(5000).fadeOut();//Fade in and out with a 5s delay.
				//alert(msg.action.additionalAlert);
			};
			var errorRequest = function(msg) {
				var outMsg = '';
				for( i = 0; i < msg.message.length; i++ )
				{
					outMsg += '<li>' + msg.message[i] + '</li>';
				}

				alertFailure.html('<ul>' + outMsg + '</ul>').show();
			};
			break;

			case "change.password":
			var processUrl = '{{ route('Json::Account::Change::Password') }}';
			var successRequest = function(msg) {
				alertSuccess.html(msg.action.displayText).show();
			};
			var errorRequest = function(msg) {
				var outMsg = '';
				for( i = 0; i < msg.message.length; i++ )
				{
					outMsg += '<li>' + msg.message[i] + '</li>';
				}

				alertFailure.html('<ul>' + outMsg + '</ul>').show();
			};
			break;

			case "change.email":
			var processUrl = '{{ route('Json::Account::Change::Email') }}';
			var successRequest = function(msg) {
				alertSuccess.html(msg.action.displayText).show();
			};
			var errorRequest = function(msg) {
				var outMsg = '';
				for( i = 0; i < msg.message.length; i++ )
				{
					outMsg += '<li>' + msg.message[i] + '</li>';
				}

				alertFailure.html('<ul>' + outMsg + '</ul>').show();
			};
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
			},
			xhr: function()
			{
				var xhr = new window.XMLHttpRequest();

				//Upload Progress.
				if( uploadProgressBar.length > 0 )
				{
					xhr.upload.addEventListener("progress", function(evt){
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total;
							console.log(percentComplete);
							uploadProgressBar.attr('data-progress-value', percentComplete);
						}
					}, false);
				}

				//Download Progress.
				if( downloadProgressBar.length > 0 )
				{
					xhr.addEventListener("progress", function(evt){
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total;
							console.log(percentComplete);
							downloadProgressBar.attr('data-progress-value', percentComplete);
						}
					}, false);
				}

				return xhr;
			},
			beforeSend: function(xhr) {
				if( enctype !== undefined )
				{
					xhr.setRequestHeader('Content-Type', enctype.val());
				}
			}
		});

		request.done(function(msg) {
			//Removing the "disabled" property on the button.
			submitButton.removeProp('disabled');

			console.log(msg);//Troubleshooting.
			var msg = jQuery.parseJSON(msg);
			if( msg.success == 1 )
			{
				loadSpan.html('');
				successRequest(msg);
			}
			else
			{
				loadSpan.html('');
				errorRequest(msg);
			}

			if( typeof msg.action.redirect != 'undefined' && msg.action.redirect != null )
			{
				window.location.replace(msg.action.redirect);
			}
		});

		request.fail(function(jqXHR, textStatus) {
			//Removing the "disabled" property on the button.
			submitButton.removeProp('disabled');

			loadSpan.html('');
			alertFailure.html(textStatus).show();
		});
	});

	@if( Auth::check() )
	$('a[data-link="json"]').on('click', function(e) {
		e.preventDefault();

		//Finding what action to do after the link is clicked.
		var linkOrigin = $(this);
		var dataAction = $(this).attr('data-action');

		switch(dataAction)
		{
			case "delete-thread":
			var contentId  = $(this).attr('data-thread-id');
			var requestUrl = '{{ url('json/forum/thread/delete/') }}/' + contentId;
			//Method will be ran when success message is received from the JSON.
			var successFeedback  = function(msg) {
				$('div[data-thread-id="' + contentId + '"]').fadeOut();
			};

			//Method will be ran when error message is received from the JSON.
			var errorFeedback = function(msg) {
			};
			break;

			case "stick-thread":
			var contentId  = $(this).attr('data-thread-id');
			var requestUrl = '{{ url('json/forum/thread/stick/') }}/' + contentId;

			//Method will be ran when success message is received from the JSON.
			var successFeedback  = function(msg) {
				if( msg.action.result == 1 )
				{
					$('[data-type="post-' + contentId + '-stick-symbol"]').fadeIn();
					linkOrigin.text('{{ trans('messages.thread.buttons.unstick') }}');
				}
				else
				{
					$('[data-type="post-' + contentId + '-stick-symbol"]').fadeOut();
					linkOrigin.text('{{ trans('messages.thread.buttons.stick') }}');
				}
			};

			//Method will be ran when error message is received from the JSON.
			var errorFeedback = function(msg) {
			};
			break;

			case "lock-thread":
			var contentId  = $(this).attr('data-thread-id');
			var requestUrl = '{{ url('json/forum/thread/lock/') }}/' + contentId;

			//Method will be ran when success message is received from the JSON.
			var successFeedback  = function(msg) {
				if( msg.action.result == 1 )
				{
					$('[data-type="post-' + contentId + '-lock-symbol"]').fadeIn();
					linkOrigin.text('{{ trans('messages.thread.buttons.unlock') }}');
				}
				else
				{
					$('[data-type="post-' + contentId + '-lock-symbol"]').fadeOut();
					linkOrigin.text('{{ trans('messages.thread.buttons.lock') }}');
				}
			};

			//Method will be ran when error message is received from the JSON.
			var errorFeedback = function(msg) {
			};
			break;
		}

		var request = $.ajax({
			url: requestUrl,
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
				successFeedback(msg);
			}
			else
			{
				errorFeedback(msg);
			}
		});

		request.fail(function(jqXHR, textStatus) {
			loadSpan.html('');
			console.log(textStatus);
			alertFailure.html(textStatus).show();
		});
	});
	@endif

});
