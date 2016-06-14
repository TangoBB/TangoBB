@extends('global.master')

@section('title', 'Change Email')

@section('content')

<div class="col-sm-4 col-md-offset-4">
	<div class="card card-block">
		<h4 class="card-title">Change Email</h4>

		@if( Session::has('success') )
		<div class="alert alert-success">
			{!! session('success') !!}
		</div>
		@endif

		@if( Session::has('error') )
		<div class="alert alert-danger">
			{!! session('error') !!}
		</div>
		@endif

		@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		<div class="alert alert-success" style="display:none;" data-type="alert-success"></div>
		<div class="alert alert-danger" style="display:none;" data-type="alert-fail"></div>

		<form action="{{ route('Account::Change::Email.Post') }}" data-process-method="json" data-process-action="change.email" method="post">
			<div class="form-group">
				<label for="email">Email</label>
				<input type="text" name="email" id="email" class="form-control" value="{{ Auth::user()['email'] }}" />
			</div>
			<div class="form-group">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<input type="submit" value="Change Email" class="btn btn-primary" /><span data-content="loader"></span>
			</div>
		</form>
	</div>
</div>

@endsection
