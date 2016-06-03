@extends('global.master')

@section('title', 'Sign Up')

@section('content')

<div class="col-sm-4 col-md-offset-4">
	<div class="card card-block">
		<h4 class="card-title">Sign Up</h4>

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

		<form action="{{ route('Account::SignUp') }}" data-process-method="json" data-process-action="signup" method="post">
			<div class="form-group">
				<label for="username">Username</label>
				<input type="text" name="username" id="username" class="form-control" />
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input type="text" name="email" id="email" class="form-control" />
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" name="password" id="password" class="form-control" />
			</div>
			<div class="form-group">
				<label for="confirm_password">Confirm Password</label>
				<input type="password" name="confirm_password" id="confirm_password" class="form-control" />
			</div>
			<div class="form-group">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<input type="submit" value="Sign Up" class="btn btn-primary" /><span data-content="loader"></span>
				or <a href="{{ route('Account::LogIn') }}">Log In</a>
			</div>
		</form>
	</div>
</div>

@endsection
