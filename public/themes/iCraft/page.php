<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>%site_name%</title>
    <link href="%site_url%/public/themes/iCraft/assets/css/icraft.css" class="tcraft_css" rel="stylesheet">

    %editor_settings%

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    @if( !$TANGO->sess->isLogged )
    <div id="top_login">
      <div id="login_cont">
        <div class="container">
          <form action="%site_url%/members.php/cmd/signin" method="POST" class="form-horizontal" role="form">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="email">Email or Username</label>
              <div class="col-sm-10">
                <input type="text" name="email" class="form-control" id="email" placeholder="Username or Email">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="password">Password</label>
              <div class="col-sm-10">
                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
              </div>
            </div>
            <div align="center">
              <label for="remember">
                <input type="checkbox" name="remember" id="remember"> Remember me<br />
                <input type="submit" class="btn btn-default" name="signin" value="Sign in" /> <a href="%site_url%t/members.php/cmd/forgotpassword">Forgot Password</a>
              </label>
            </div>
          </form>
        </div>
      </div>
      <div id="toggle_login">
        <a href="javascript:return false;">Log In</a>
      </div>
    </div>
    @endif
    
    <div id="wrapper">
      <div id="top-wrapper">
        <div class="container tcontainer">
          <h1 class="site_name pull-left">
            <a href="%site_url%">%site_name%</a>
          </h1>
          <div class="search_box pull-right">
            <form action="http://tangobb.net/search.php" method="POST">
              <div class="left-inner-addon">
                <i class="glyphicon glyphicon-search"></i>
                <input name="search_query" type="text" class="form-control" placeholder="Search..." />
                <input type="submit" name="search_submit" style="visibility:hidden;display:none;" value="Search" />
              </div>
            </form>
          </div>
        </div>
        <!--- Navigation -->
        <div id="nav">
          <nav class="navbar navbar-default" role="navigation">
            <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#tc-navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>
            <div class="collapse navbar-collapse" id="tc-navbar">
              <ul class="nav navbar-nav">
                <li class="active"><a href="%site_url%/forum.php"><i class="fa fa-comments"></i> Forum</a></li>
                <li><a href="%site_url%/members.php"><i class="fa fa-group"></i> Members</a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                @if( $TANGO->sess->isLogged )
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> %username%</a>
                  <ul class="dropdown-menu">
                    @foreach ($TANGO->user->userLinks() as $name => $link)
                    <li>
                      <a href="{{ $link }}">
                        {{ $name }}
                      </a>
                    </li>
                    @endforeach
                  </ul>
                </li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-envelope"></i> Conversations
                    @if (count($TANGO->user->userMessages()) > 0)
                    <span class="label label-success">{{ count($TANGO->user->userMessages()) }}</span>
                    @endif
                  </a>
                  <ul class="dropdown-menu">
                    @if (count($TANGO->user->userMessages()) > 0)
                    @foreach ($TANGO->user->userMessages() as $msg)
                    <li><a href="{{ $msg['view_url'] }}">
                      <h4>
                        {{ $msg['message_title'] }}
                      </h4>
                      <small>By {{ $msg['message_sender'] }} at
                        {{ date('F j, Y', $msg['message_time']) }}</small>
                      </a>
                    </li>
                    @endforeach
                    @else
                    <li role="presentation" class="disabled"><a role="menuitem" tabindex="-1" href="#">No Conversations</a></li>
                    @endif
                    <li><a href="%site_url%/conversations.php/cmd/new">New Conversation</a></li>
                  </ul>
                </li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="overflow:auto;">
                    <i class="fa fa-bell"></i>
                    @if (count($TANGO->user->notifications()) > 0)
                    <span class="badge">
                      {{ count($TANGO->user->notifications()) }}
                    </span>
                    @endif
                  </a>
                  <ul class="dropdown-menu">
                    @if (count($TANGO->user->notifications()) < 1)
                    <li role="presentation" class="disabled"><a role="menuitem" tabindex="-1" href="#">No new notifications yet.</a></li>
                    @else
                    @foreach ($TANGO->user->notifications() as $note)
                    <li><a href="{{ $note['notice_link'] }}">
                      {{ $note['notice_content'] }}
                      <small class="text-muted">{{ date('F j, Y', $note['time_received']) }}</small>
                    </a>
                  </li>
                  @endforeach
                  {{ $TANGO->user->clearNotification() }}
                  @endif
                </ul>
              </li>
              @endif
              </ul>
            </div>
          </div>
        </nav>
      </div>
      <!--- Navigation End -->
    </div>

    <div id="main_container" class="container">
      <div class="row">

        <div class="col-md-12">

          <div class="page">
            <div class="row">
              <div class="col-md-10">
                <h2>%page_title%</h2>
              </div>
              <div class="col-md-2 css_style_chooser">
                <a href="javascript:return false;" class="toggle_theme_editor tooltip_toggle" data-toggle="tooltip" title="Theme Editor" data-placement="right"><i class="fa fa-list"></i></a>
              </div>
            </div>
          </div>

          <div class="theme_editor">
            <ul>
              <li><a href="#" rel="%site_url%/public/themes/iCraft/assets/css/icraft.css">Winter</a></li>
              <li><a href="#" rel="%site_url%/public/themes/iCraft/assets/css/underground.css">Underground</a></li>
              <li><a href="#" rel="%site_url%/public/themes/iCraft/assets/css/farm.css">Farm</a></li>
              <li><a href="#" rel="%site_url%/public/themes/iCraft/assets/css/quiet.css">Quiet</a></li>
            </ul>
          </div>

          %content%
        </div>

      </div>
      <footer class="footer">
        <p class="pull-right">
          Powered by <a href="http://iko.im/">Iko</a>
        </p>
      </footer>
    </div>
    
  </div>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="%site_url%/public/themes/iCraft/assets/js/bootstrap.min.js"></script>
    <script src="%site_url%/public/themes/iCraft/assets/js/jquery.cookie.js"></script>
    <script src="%site_url%/public/themes/iCraft/assets/js/tcraft.js"></script>
  </body>
</html>