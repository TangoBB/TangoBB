@extends('global.master')

@section('title', 'Edit Post')

@section('pageHeader')
Edit Post
@endsection

@section('content')

<form action="{{ route('Forum::Thread::Edit', ['id' => $post['id']]) }}" method="POST">
	<div class="col-sm-10">
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

		<div class="alert alert-success" style="display:none;" data-type="alert-success"></div>
		<div class="alert alert-danger" style="display:none;" data-type="alert-fail"></div>
		<div class="form-group">
			<textarea id="bbcode_editor" name="editor" class="form-control" style="min-height:350px;">{!! $post['post_content'] !!}</textarea>
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<input type="submit" value="Edit Post" class="btn btn-primary btn-block" />
			<span data-content="loader" style="text-align:center;"></span>
		</div>
	</div>
</form>


@endsection
