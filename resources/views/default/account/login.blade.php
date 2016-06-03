@extends('global.master')

@section('title', 'Log In')

@section('content')

<div class="col-sm-4 col-md-offset-4">
	<div class="card card-block">
		<h4 class="card-title">Log In</h4>

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

		<form action="{{ route('Account::LogIn.Post') }}" data-process-method="json" data-process-action="login" method="post">
			<div class="form-group">
				<label for="username">Username</label>
				<input type="text" name="username" id="username" class="form-control" />
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" name="password" id="password" class="form-control" />
			</div>
			<div class="form-group">
				<input type="checkbox" name="remember_me" value="1" id="remember_me" /> <label for="remember_me">Remember Me</label>
			</div>
			<div class="form-group">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<input type="submit" value="Log In" class="btn btn-primary" /><span data-content="loader"></span>
				or <a href="{{ route('Account::SignUp') }}">Sign Up</a>
			</div>
		</form>
	</div>
</div>

@endsection
