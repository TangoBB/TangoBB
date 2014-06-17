<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>%site_name%</title>

    <!-- Bootstrap -->
    <link href="%site_url%/public/themes/izbucket/assets/css/izbucket.css" rel="stylesheet">

    %editor_settings%

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body data-spy="scroll" data-target=".main_nav">

    <nav class="navbar navbar-default navbar-fixed-top main_nav" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-nav">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="collapse navbar-collapse" id="bs-nav">
          <ul class="nav navbar-nav">
            <li><a href="%site_url%/#home" id="scroll_nav">Introducing</a></li>
            <li><a href="%site_url%/#play" id="scroll_nav">Play</a></li>
            <li><a href="%site_url%/#rules" id="scroll_nav">Rules</a></li>
            <li class="active"><a href="%site_url%/forum.php">Forum</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container main_container">
      <div class="top" id="top">
        <h1>%site_name%</h1>
        <div id="nav">
          <nav class="navbar navbar-inverse" role="navigation">
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
                  <li class="active"><a href="%site_url%/forum.php">Forum Index</a></li>
                  <li><a href="%site_url%/members.php">Members</a></li>
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
              @else
              <li><a href="%site_url%/members.php/cmd/signin">Log In</a></li>
              @endif
                </ul>
              </div>
            </div>
          </nav>
        </div>
      </div>

      <div class="container placeholder">
        <h1>%page_title%</h1>
        %content%
      </div>
    </div>

    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            Made in Singapore by <a href="http://twitter.com/jtPox">@jtPox</a>
          </div>
          <div class="col-md-6" style="text-align:right">
            Powered by <a href="http://iko.im/">Iko</a>
          </div>
        </div>
      </div>
    </footer>

    <script src="%site_url%/public/themes/izbucket/assets/js/bootstrap.min.js"></script>
    <script src="%site_url%/public/themes/izbucket/assets/js/izbucket_forum.js"></script>
  </body>
</html>