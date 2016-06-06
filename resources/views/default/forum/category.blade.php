@extends('global.master')

@section('title', $selected['category_name'])

@section('pageHeader')
{{ $selected['category_name'] }}
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

<div class="col-sm-3">
	@if( Auth::check() && Auth::user()->hasPermission(null, 'post.create') && Auth::user()->can('post-in-category', $selected) )
	<div class="form-group">
		<a href="{{ route('Forum::Category::Post', ['slug' => $selected['category_slug'], 'id' => $selected['id']]) }}" class="btn btn-info btn-block">New Thread</a>
	</div>
	@endif
	<ul class="nav nav-pills nav-stacked">
		@foreach( $categories as $cat )

		<li class="nav-item">
			<a href="{{ route('Forum::Category::Category', ['slug' => $cat['category_slug'], 'id' => $cat['id']]) }}" class="nav-link{{ ($cat['id'] == $selected['id'])? ' active' : '' }}">
				<i class="fa fa-comments" style="color:#{{ $cat['category_color'] }};"></i> {{ $cat['category_name'] }}
			</a>
		</li>

		@endforeach
	</ul>
</div>

<div class="col-sm-9">
	<div class="row">
		@foreach( $threads as $th )
		<div class="col-sm-4" data-thread-id="{{ $th->id }}">
			<div class="card">
				<div class="card-block">
					<h4 class="card-title">
						<a href="{{ route('Forum::Thread::Thread', ['slug' => $th->post_slug, 'id' => $th->id]) }}">{{ $th->post_name }}</a>
					</h4>
					<p class="card-text">{{ substr($app->Bbcode->strip($th->post_content), 0, 50) }}...</p>
				</div>
				<ul class="list-group list-group-flush">
					<li class="list-group-item">
						<small class="text-muted">Last updated <span title="{{ date('D, F Y', strtotime($th->updated_at)) }}">{{ date('l, g:h a', strtotime($th->updated_at)) }}</span></small>
						@if( $th->is_stickied == 1 )
						<small data-type="post-{{ $th->id }}-stick-symbol"><i class="fa fa-thumb-tack" title="Thread is stuck."></i></small>
						@else
						<small data-type="post-{{ $th->id }}-stick-symbol" style="display:none;"><i class="fa fa-thumb-tack" title="Thread is stuck."></i></small>
						@endif
						@if( $th->is_locked == 1 )
						<small data-type="post-{{ $th->id }}-lock-symbol"><i class="fa fa-lock" title="Thread is locked."></i></small>
						@else
						<small data-type="post-{{ $th->id }}-lock-symbol" style="display:none;"><i class="fa fa-lock" title="Thread is locked."></i></small>
						@endif
					</li>
					<li class="list-group-item"><small class="text-muted">Created by <a href="#">{{ $th->User()->first()['name'] }}</a></small></li>
					@if( Auth::check() && Auth::User()->hasPermission(null, 'moderator.access') )
					<li class="list-group-item">
						<small class="text-muted">
							Moderator:
							@if( $app->auth->user()->hasPermission(null, 'moderator.delete.post') )
							<a href="{{ route('Forum::Thread::Delete', ['id' => $th->id]) }}" data-link="json" data-thread-id="{{ $th->id }}" data-action="delete-thread">Delete</a> |
							@endif
							@if( $app->auth->user()->hasPermission(null, 'moderator.stick.post') )
							<a href="{{ route('Forum::Thread::Stick', ['id' => $th->id]) }}" data-link="json" data-action="stick-thread" data-thread-id="{{ $th->id }}">{{ ($th->is_stickied == 1)? 'Unstick' : 'Stick' }}</a> |
							@endif
							@if( $app->auth->user()->hasPermission(null, 'moderator.lock.post') )
							<a href="{{ route('Forum::Thread::Lock', ['id' => $th->id]) }}" data-link="json" data-action="lock-thread" data-thread-id="{{ $th->id }}">{{ ($th->is_locked == 1)? 'Unlock' : 'Lock' }}</a>
							@endif
						</small>
					</li>
					@endif
				</ul>
			</div>
		</div>
		@endforeach
	</div>
</div>

<div class="col-sm-12" style="text-align:center;">
	{!! $threads->links() !!}
</div>

@endsection

@section('pageHeaderColor', $selected['category_color'])
@section('pageHeaderDescription', $selected['category_description'])
