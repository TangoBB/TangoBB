<nav class="navbar navbar-light navbar-full bg-faded">
  <div class="container-fluid">
    <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#mainNavbar">
      &#9776;
    </button>
    <div class="collapse navbar-toggleable-xs" id="mainNavbar">
      <a href="{{ route('Index::Index') }}" class="navbar-brand">{{ $settings['forum_name'] }}</a>
      <ul class="nav navbar-nav">
        <li class="nav-item{{ (Request::is('/') || Request::is('category/*') || Request::is('thread/*'))? ' active' : '' }}"><a class="nav-link" href="{{ route('Index::Index') }}">Home</a></li>
        @if( Auth::check() )
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle{{ ( Request::is('account/*') )? ' active' : '' }}" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <img src="{{ App\User::Gravatar(Auth::user()) }}" class="img-circle" style="width:20px;height:20px;" /> {{ Auth::user()['name'] }}
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item{{ ( Request::is('account/change/email') )? ' active' : '' }}" href="{{ route('Account::Change::Email') }}">Change Email</a>
            <a class="dropdown-item{{ ( Request::is('account/change/password') )? ' active' : '' }}" href="{{ route('Account::Change::Password') }}">Change Password</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">View Profile</a>
          </div>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ route('Account::LogOut') }}">Log Out</a></li>
        @else
        <li class="nav-item{{ (Request::is('account/login'))? ' active' : '' }}"><a class="nav-link" href="{{ route('Account::LogIn') }}">Log In</a></li>
        <li class="nav-item{{ (Request::is('account/signup'))? ' active' : '' }}"><a class="nav-link" href="{{ route('Account::SignUp') }}">Sign Up</a></li>
        @endif
      </ul>

      <form action="" method="POST" class="form-inline pull-xs-right">
        <input type="text" name="search" class="form-control" placeholder="Search" />
        <input type="submit" style="display:none;" />
      </form>
    </div>
  </div>
</nav>
