@extends('global.master')

@section('pageHeader')
{{ $selected['category_name'] }}
@endsection

@section('content')

@if( Session::has('success') )
<div class="col-md-12">
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
		@foreach( $categories as $cat )

		<li class="nav-item">
			<a href="{{ route('Forum::Category', ['slug' => $cat['category_slug'], 'id' => $cat['id']]) }}" class="nav-link{{ ($cat['id'] == $selected['id'])? ' active' : '' }}">
				<i class="fa fa-comments" style="color:#{{ $cat['category_color'] }};"></i> {{ $cat['category_name'] }}
			</a>
		</li>

		@endforeach
	</ul>
</div>

<div class="col-sm-9">
	<div class="row">
		@foreach( $threads as $th )
		<div class="col-sm-4">
			<div class="card card-block">
				<h4 class="card-title"><a href="#">{{ $th->post_name }}</a></h4>
				<p class="card-text">{{ Bbcode::strip($th->post_content) }}</p>
				<p class="card-text"><small class="text-muted">Last updated <span title="{{ date('D, F Y', $th->updated_at) }}">{{ date('l, g:h a', $th->updated_at) }}</span> by <a href="#">{{ $th->User()->first()['name'] }}</a></small></p>
			</div>
		</div>
		@endforeach
	</div>
</div>

@endsection

@section('pageHeader', $selected['category_name'])
@section('pageHeaderColor', $selected['category_color'])
@section('pageHeaderDescription', $selected['category_description'])
