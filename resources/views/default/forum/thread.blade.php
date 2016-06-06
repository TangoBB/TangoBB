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

@if( Session::has('error') )
<div class="col-sm-12">
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		{!! session('error') !!}
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
	<div class="card" style="border-color:#55b346;">
		<div class="card-block">
			<p class="card-text">
				<img src="{{ App\User::Gravatar($thread->User()->first()) }}" class="img-circle" style="width:50px;height:50px;" />
				<a href="#">{{ $thread->User()->first()->name }}</a>
				<span class="text-muted" title="Last Edited {{ date('d M', strtotime($thread['updated_at'])) }}">{{ date('d M', strtotime($thread['updated_at'])) }}</span>
			</p>
			<div class="card-text" data-display-id="{{ $thread['id'] }}">
				{!! $app->Bbcode->renderText($thread['post_content']) !!}
			</div>
		</div>
		@if( Auth::check() )
		@if( Auth::User()->can('update-post', $thread) || Auth::User()->hasPermission(null, 'moderator.edit.post') )
		<div class="card-block" style="display:none;" data-edit-id="{{ $thread['id'] }}">
			<p class="card-text"><hr size="1" /></p>
			<h4 class="card-title">Edit Post</h4>
			<p class="card-text">
				<form method="POST" data-process-method="json" data-process-action="post.edit" data-content-id="{{ $thread['id'] }}">
					<div class="form-group">
						<textarea name="editor" class="bbcode_editor">{!! $thread['post_content'] !!}</textarea>
					</div>
					<div class="form-group">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<input type="submit" value="Edit" class="btn btn-primary btn-sm" />
					</div>
				</form>
			</p>
		</div>
		@endif
		@endif
		@if( Auth::check() )
		<ul class="list-group list-group-flush">
			@if( Auth::User()->id == $thread['posted_by'] )
			<li class="list-group-item">
				<small class="text-muted">
					Options: <a href="{{ route('Forum::Thread::Edit', ['id' => $thread['id']]) }}" data-link="json" data-effect="edit" data-post-id="{{ $thread['id'] }}">Edit</a>
				</small>
			</li>
			@endif
			@if( Auth::User()->hasPermission(null, 'moderator.access') )
			<li class="list-group-item">
				<small class="text-muted">
					Moderator:
					@if( $app->auth->user()->hasPermission(null, 'moderator.delete.post') )
					<a href="{{ route('Forum::Thread::Delete', ['id' => $thread['id']]) }}">Delete</a> |
					@endif
					@if( $app->auth->user()->hasPermission(null, 'moderator.edit.post') )
					<a href="{{ route('Forum::Thread::Edit', ['id' => $thread['id']]) }}" data-effect="edit" data-post-id="{{ $thread['id'] }}">Edit</a> |
					@endif
					@if( $app->auth->user()->hasPermission(null, 'moderator.stick.post') )
					<a href="{{ route('Forum::Thread::Stick', ['id' => $thread['id']]) }}" data-link="json" data-action="stick-thread" data-thread-id="{{ $thread['id'] }}">{{ ($thread['is_stickied'] == 1)? 'Unstick' : 'Stick' }}</a> |
					@endif
					@if( $app->auth->user()->hasPermission(null, 'moderator.lock.post') )
					<a href="{{ route('Forum::Thread::Lock', ['id' => $thread['id']]) }}" data-link="json" data-action="lock-thread" data-thread-id="{{ $thread['id'] }}">{{ ($thread['is_locked'] == 1)? 'Unlock' : 'Lock' }}</a>
					@endif
				</small>
			</li>
			@endif
		</ul>
		@endif
	</div>
</div>

<div class="col-sm-2">
	@if( $thread['is_locked'] == 1 )
	<a href="#" class="btn btn-warning btn-block disabled">Thread Locked</a>
	@else
	@if( Auth::check() )
	<a href="#reply_container" class="btn btn-info btn-block">Reply</a>
	@else
	<a href="#" class="btn btn-info btn-block disabled">Log In to Reply</a>
	@endif
	@endif
</div>

@foreach( $replies as $reply )
<div class="col-sm-10" data-thread-id="{{ $reply['id'] }}">
	<div class="card">
		<div class="card-block">
			<p class="card-text">
				<img src="{{ App\User::Gravatar($reply->User()->first()) }}" class="img-circle" style="width:50px;height:50px;" />
				<a href="#">{{ $reply->User()->first()->name }}</a>
				<span class="text-muted" title="Last Edited {{ date('d M', strtotime($reply['updated_at'])) }}">{{ date('d M', strtotime($reply['updated_at'])) }}</span>
			</p>
			<div class="card-text" data-display-id="{{ $reply['id'] }}">
				{!! $app->Bbcode->renderText($reply['post_content']) !!}
			</div>
		</div>
		@if( Auth::check() )
		@if( Auth::User()->can('update-post', $reply) )
		<div class="card-block" style="display:none;" data-edit-id="{{ $reply['id'] }}">
			<p class="card-text"><hr size="1" /></p>
			<h4 class="card-title">Edit Post</h4>
			<p class="card-text">
				<form method="POST" data-process-method="json" data-process-action="post.edit" data-content-id="{{ $reply['id'] }}">
					<div class="form-group">
						<textarea name="editor" class="bbcode_editor">{!! $reply['post_content'] !!}</textarea>
					</div>
					<div class="form-group">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<input type="submit" value="Edit" class="btn btn-primary btn-sm" />
					</div>
				</form>
			</p>
		</div>
		@endif
		@endif
		@if( Auth::check() )
		<ul class="list-group list-group-flush">
			@if( Auth::User()->id == $reply['posted_by'] )
			<li class="list-group-item">
				<small class="text-muted">
					Options: <a href="{{ route('Forum::Thread::Edit', ['id' => $reply['id']]) }}" data-effect="edit" data-post-id="{{ $reply['id'] }}">Edit</a>
				</small>
			</li>
			@endif
			@if( Auth::User()->hasPermission(null, 'moderator.access') )
			<li class="list-group-item">
				<small class="text-muted">
					Moderator:
					@if( $app->auth->user()->hasPermission(null, 'moderator.delete.post') )
					<a href="{{ route('Forum::Thread::Delete', ['id' => $reply['id']]) }}" data-thread-id="{{ $reply['id'] }}" data-action="delete-thread">Delete</a> |
					<a href="{{ route('Forum::Thread::Edit', ['id' => $reply['id']]) }}" data-effect="edit" data-post-id="{{ $reply['id'] }}">Edit</a>
					@endif
				</small>
			</li>
			@endif
		</ul>
		@endif
	</div>
</div>
@endforeach

@if( Auth::check() )
@if(Auth::User()->hasPermission(null, 'post.reply'))

@if( $thread['is_locked'] == 1 )
<div class="col-sm-10">
	<div class="alert alert-warning">Thread is locked.</div>
</div>
@else
<div class="col-sm-10">
	<div class="reply" data-content-type="html-content"></div>

	<div class="alert alert-success" style="display:none;" data-type="alert-success"></div>
	<div class="alert alert-danger" style="display:none;" data-type="alert-fail"></div>

	<form action="{{ route('Forum::Thread::Reply', ['id' => $thread['id']]) }}" method="POST" id="reply_container" data-process-method="json" data-process-action="thread.reply" data-content-id="{{ $thread['id'] }}">
		<div class="form-group">
			<textarea name="editor" id="bbcode_editor"></textarea>
		</div>
		<div class="form-group">
			<input type="hidden" name="title" value="RE: {{ $thread['post_name'] }}" />
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<input type="submit" value="Reply" class="btn btn-primary" /><span data-content="loader"></span>
		</div>
	</form>
</div>
@endif

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
