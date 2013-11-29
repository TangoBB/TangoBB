<!DOCTYPE html>
<html>
  <head>
    <title>%page_title% | %site_name%</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="%site_url%/public/themes/origin/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="%site_url%/public/themes/origin/assets/css/origin.css" rel="stylesheet">
    %editor_settings%

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
      
      @if ($TANGO->perm->check('access_moderation'))
      <div id="staff_bar">
          <div class="container">
              <ul>
                  <li><a href="%site_url%/mod">Reports <span class="label label-warning">%mod_report_integer%</span></a></li>
                  @if ($TANGO->perm->check('access_administration'))
                  <li><a href="%site_url%/admin">Admin CP</a></li>
                  @endif
              </ul>
          </div>
      </div>
      @endif
      
      @if (!$TANGO->sess->isLogged)
      <div id="slide_signin">
          <div class="container">
              <form action="%site_url%/members.php/cmd/signin" method="POST" class="form-horizontal">
                  <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-10">
                          <input type="text" name="email" class="form-control" id="inputEmail3" placeholder="Email">
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                      <div class="col-sm-10">
                          <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Password">
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">
                          <input type="submit" class="btn btn-primary" name="signin" value="Sign in" />
                          <label>
                              <input type="checkbox" name="remember"> Remember me
                          </label>
                      </div>
                  </div>
              </form>
          </div>
      </div>
      @endif
    
      <div id="page_body">
      
      <nav class="navbar navbar-inverse nav_stay" role="navigation">
          <div class="container">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#origin-navbar-collapse">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  </button>
                </div>
                <div class="collapse navbar-collapse" id="origin-navbar-collapse">
              @if (!$TANGO->sess->isLogged)
              <ul class="nav navbar-nav">
                  <li><a href="javascript:origin_sign_in_slide();">Sign In</a></li>
                  <li><a href="%site_url%/members.php/cmd/register">Register</a></li>
              </ul>
              @else
              <p class="navbar-text">Signed in as </p>
              <div class="btn-group">
                  <a href="%site_url%/members.php/cmd/user/id/{{ $TANGO->sess->data['id']; }}" class="btn btn-default dropdown-toggle user_dropdown" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i> %username%</a>
                  <ul class="dropdown-menu" role="menu">
                      @foreach ($TANGO->user->userLinks() as $name => $link)
                            <li>
                                <a href="{{ $link }}">
                                    {{ $name }}
                                </a>
                            </li>
                      @endforeach
                  </ul>
              </div>
              
              @endif
              <form action="%site_url%/search.php" method="POST" class="navbar-form navbar-left pull-right" role="search">
                  <div class="form-group">
                      <input name="search_query" type="text" class="form-control" placeholder="Search" data-toggle="tooltip" data-placement="bottom" title="Press Enter">
                  </div>
                  <input type="submit" name="search_submit" style="visibility:hidden;display:none;" value="Search" />
              </form>
            </div>
          </div>
      </nav>
      
      <div class="container">
          
          <h1>%page_title%</h1>
          
          <div class="row">
              
              <div class="col-md-3">
                  <ul class="nav nav-pills nav-stacked">
                      <li class="active"><a href="%site_url%/forum.php">Forums</a></li>
                      <li><a href="%site_url%/members.php">Members</a></li>
                  </ul>
              </div>
              
              <div class="col-md-9">
                  
                  %content%
                  
              </div>
              
          </div>
          
          <hr size="1" />
          <footer>
              <p>
                  Powered by <a href="http://tangobb.net/">TangoBB</a>
              </p>
          </footer>
          
      </div>
          
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="%site_url%/public/themes/origin/assets/js/bootstrap.min.js"></script>
    <script src="%site_url%/public/themes/origin/assets/js/theme.js"></script>
  </body>
</html>