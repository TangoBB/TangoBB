@extends('global.master')

@section('title', $thread['post_name'])

@section('pageHeader')
{{ $thread['post_name'] }}
@endsection

@section('content')

@if( Session::has('success') )
<div class="col-sm-12">
	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		{!! session('success') !!}
	</div>
</div>
@endif

@if (count($errors) > 0)
<div class="col-sm-12">
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
</div>
@endif

<div class="col-sm-10">
	<div class="card card-block" style="border-color:#55b346;">
		<p class="card-text">
			<img src="{{ App\User::Gravatar($thread->User()->first()) }}" class="img-circle" style="width:50px;height:50px;" />
			<a href="#">{{ $thread->User()->first()->name }}</a>
			<span class="text-muted" title="Last Edited {{ date('d M', strtotime($thread['updated_at'])) }}">{{ date('d M', strtotime($thread['updated_at'])) }}</span>
		</p>
		<p class="card-text">
			{!! $app->Bbcode->renderText($thread['post_content']) !!}
		</p>
	</div>
</div>

<div class="col-sm-2">
	@if( Auth::check() )
	<a href="#reply_container" class="btn btn-info btn-block">Reply</a>
	@else
	<a href="#" class="btn btn-info btn-block disabled">Log In to Reply</a>
	@endif
</div>

@foreach( $replies as $reply )
<div class="col-sm-10">
	<div class="card card-block">
		<p class="card-text">
			<img src="{{ App\User::Gravatar($reply->User()->first()) }}" class="img-circle" style="width:50px;height:50px;" />
			<a href="#">{{ $reply->User()->first()->name }}</a>
			<span class="text-muted" title="Last Edited {{ date('d M', strtotime($reply['updated_at'])) }}">{{ date('d M', strtotime($reply['updated_at'])) }}</span>
		</p>
		<p class="card-text">
			{!! $app->Bbcode->renderText($reply['post_content']) !!}
		</p>
	</div>
</div>
@endforeach

@if( Auth::check() )
@if(Auth::User()->hasPermission(null, 'post.reply'))
<div class="col-sm-10">
	<div class="reply" data-content-type="html-content"></div>

	<div class="alert alert-success" style="display:none;" data-type="alert-success"></div>
	<div class="alert alert-danger" style="display:none;" data-type="alert-fail"></div>

	<form action="{{ route('Forum::Thread::Thread', ['slug' => $thread['post_slug'], 'id' => $thread['id']]) }}" method="POST" id="reply_container" data-process-method="json" data-process-action="thread.reply" data-content-id="{{ $thread['id'] }}">
		<div class="form-group">
			<textarea name="editor" id="bbcode_editor"></textarea>
		</div>
		<div class="form-group">
			<input type="hidden" name="title" value="RE: {{ $thread['post_name'] }}" />
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<input type="submit" value="Reply" class="btn btn-primary" />
		</div>
	</form>
</div>
@else
<div class="col-sm-10">
	<div class="alert alert-warning">
		You do not have permission to reply to posts.
	</div>
</div>
@endif
@endif

<div class="col-sm-10" style="text-align:center;">
	{!! $replies->links() !!}
</div>

@endsection

@section('pageHeaderColor', $thread->Category()->first()['category_color'])
@section('pageHeaderDescription')
<a href="{{ route('Forum::Category::Category', ['slug' => $thread->Category()->first()['category_slug'], 'id' => $thread->Category()->first()['id']]) }}">
	<span class="label label-pill label-primary">{{ $thread->Category()->first()['category_name'] }}</span>
</a>
@endsection
