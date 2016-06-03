<nav class="navbar navbar-light navbar-full bg-faded" style="background-color:#ccc;">
  <div class="container-fluid">
    <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#mainNavbar">
      &#9776;
    </button>
    <div class="collapse navbar-toggleable-xs" id="mainNavbar">
      <a href="{{ route('Index::Index') }}" class="navbar-brand">{{ $settings['forum_name'] }}</a>
      <ul class="nav navbar-nav">
        <li class="nav-item{{ (Request::is('/'))? ' active' : '' }}"><a class="nav-link" href="{{ route('Index::Index') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Log In</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Sign Up</a></li>
      </ul>

      <form action="" method="POST" class="form-inline pull-xs-right">
        <input type="text" name="search" class="form-control" placeholder="Search" />
        <input type="submit" style="display:none;" />
      </form>
    </div>
  </div>
</nav>
