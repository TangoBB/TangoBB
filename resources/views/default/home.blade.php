@extends('global.master')

@section('pageHeader')
{{ $settings['forum_name'] }}
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
	<ul class="nav nav-pills nav-stacked">
		@foreach( $category as $cat )

		<li class="nav-item">
			<a href="{{ route('Forum::Category::Category', ['slug' => $cat['category_slug'], 'id' => $cat['id']]) }}" class="nav-link{{ ($cat['category_place'] == 1)? ' active' : '' }}">
				<i class="fa fa-comments" style="color:#{{ $cat['category_color'] }};"></i> {{ $cat['category_name'] }}
			</a>
		</li>

		@endforeach
	</ul>
</div>

<div class="col-sm-9">
	<div class="row">
		@foreach( $thread as $th )
		<div class="col-sm-4">
			<div class="card">
				<div class="card-block">
					<h4 class="card-title"><a href="{{ route('Forum::Thread::Thread', ['slug' => $th->post_slug, 'id' => $th->id]) }}">{{ $th->post_name }}</a></h4>
					<p class="card-text">{{ substr($app->Bbcode->strip($th->post_content), 0, 50) }}...</p>
				</div>
				<ul class="list-group list-group-flush">
					<li class="list-group-item">
						<small class="text-muted">Last updated <span title="{{ date('D, F Y', strtotime($th->updated_at)) }}">{{ date('l, g:h a', strtotime($th->updated_at)) }}</span></small>
						@if( $th->is_stickied == 1 )
						<small data-type="post-{{ $th->id }}-stick-symbol"><i class="fa fa-thumb-tack" title="Thread is stuck."></i></small>
						@endif
						@if( $th->is_locked == 1 )
						<small data-type="post-{{ $th->id }}-lock-symbol"><i class="fa fa-lock" title="Thread is locked."></i></small>
						@endif
					</li>
					<li class="list-group-item"><small class="text-muted">Created by <a href="#">{{ $th->User()->first()['name'] }}</a></small></li>
				</ul>
			</div>
		</div>
		@endforeach
	</div>
</div>

@endsection
