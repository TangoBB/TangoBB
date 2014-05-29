<?php
  if( !defined("BASEPATH") ) { die(); }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>%page_title% | %site_name%</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="%site_url%/public/themes/Blue/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="%site_url%/public/themes/Blue/assets/css/bootstrap-theme.css" rel="stylesheet">
    <link href="%site_url%/public/themes/Blue/assets/css/blue.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
    %editor_settings%
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <div class="container">
      <a href="#"><div class="logo pull-left"></div></a>
      <div class="search_box pull-right">
        <form action="%site_url%/search.php" method="POST">
          <div class="left-inner-addon">
            <i class="glyphicon glyphicon-search"></i>
            <input name="search_query" type="text" class="form-control tooltip_toggle" placeholder="Search..." data-toggle="tooltip" data-placement="left" title="Press Enter">
            <input type="submit" name="search_submit" style="visibility:hidden;display:none;" value="Search" />
          </div>
        </form>
      </div>
    </div>

    <nav class="navbar navbar-default" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li class="active"><a href="%site_url%">Forums</a></li>
            <li><a href="%site_url%/members.php">Members</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            @if ($TANGO->sess->isLogged)
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i> %username%</a>
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
                <li role="presentation" class="dropdown-header">No Messages</li>
                @endif
                <li><a href="%site_url%/conversations.php/cmd/new">New Message</a></li>
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
            @else
            <li><a href="#" data-toggle="modal" data-target="#login_modal">Sign In</a></li>
            @endif
          </ul>
        </div>
      </div>
    </nav>

    <div id="wrap" class="container">
      <div class="row">

        <div class="col-md-12">
          <h1>%page_title%</h1>
          %content%
        </div>

      </div>
      <footer>
          <small class="pull-left">
            Powered by <a href="http://iko.im/" target="_blank">Iko</a>
          </small>
          @if ($TANGO->sess->isLogged)
          <small class="pull-right">
            <span data-toggle="tooltip" title="Choose Themes" class="tooltip_toggle">
              <a href="#" data-toggle="modal" data-target="#theme_modal">Change Theme</a>
            </span>
          </small>
          @endif
      </footer>
    </div>

    <div class="container statistics">
      <small class="text-muted">
        Load Time: %elapsed_time% seconds |
        Memory: %memory_usage%
      </small>
    </div>

    @if (!$TANGO->sess->isLogged)
    <div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="login_modal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Sign In</h4>
          </div>
          <div class="modal-body">
            <form action="%site_url%/members.php/cmd/signin" method="POST">
              <input type="text" name="email" class="form-control" id="inputEmail3" placeholder="Username or Email">
              <br />
              <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Password">
              <label>
                <input type="checkbox" name="remember"> Remember me
              </label>
              <br />
              <input type="submit" class="btn btn-primary" name="signin" value="Sign in" />
              @if ($TANGO->data['facebook_authenticate'] == "1")
              <a href="%facebook_login_url%" class="btn btn-info btn-sm"><i class="fa fa-facebook"></i> Sign In with Facebook</a>
              @endif
              <br />
              <a href="%site_url%/members.php/cmd/forgotpassword">Forgot Password</a>
            </form>
          </div>
        </div>
      </div>
    </div>
    @endif
    
    @if ($TANGO->sess->isLogged) 
    <div class="modal fade" id="theme_modal" tabindex="-1" role="dialog" aria-labelledby="theme_modal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Choose Themes</h4>
          </div>
          <div class="modal-body">
            <div class="list-group">
              @foreach (listThemes() as $theme)
              @if ($theme['theme_name'] == $TANGO->sess->data['chosen_theme'])
              <a class="list-group-item active" href="{{ $theme['change_link'] }}">
                <h4 class="list-group-item-heading">{{ $theme['theme_name'] }}</h4>
              </a>
              @else
              <a class="list-group-item" href="{{ $theme['change_link'] }}">
                <h4>{{ $theme['theme_name'] }}</h4>
              </a>
              @endif
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif

    <!-- Include all compiled plugins (below), or include individual files as needed -->
     <script src="%site_url%/public/themes/Blue/assets/js/bootstrap.min.js"></script>
     <script src="%site_url%/public/themes/Blue/assets/js/blue.js"></script>
  </body>
</html>