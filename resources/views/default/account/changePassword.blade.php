@extends('global.master')

@section('title', 'Change Password')

@section('content')

<div class="col-sm-4 col-md-offset-4">
	<div class="card card-block">
		<h4 class="card-title">Change Password</h4>

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

		<form action="{{ route('Account::Change::Password.Post') }}" data-process-method="json" data-process-action="change.password" method="post">
			<div class="form-group">
				<label for="old_password">Old Password</label>
				<input type="password" name="old_password" id="old_password" class="form-control" />
			</div>
			<div class="form-group">
				<label for="new_password">New Password</label>
				<input type="password" name="new_password" id="new_password" class="form-control" />
			</div>
			<div class="form-group">
				<label for="confirm_password">Confirm New Password</label>
				<input type="password" name="confirm_password" id="confirm_password" class="form-control" />
			</div>
			<div class="form-group">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<input type="submit" value="Change Password" class="btn btn-primary" /><span data-content="loader"></span>
			</div>
		</form>
	</div>
</div>

@endsection
