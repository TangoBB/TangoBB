<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags always come first -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>@hasSection('title') @yield('title') | {{ $settings['forum_name'] }} @else {{ $settings['forum_name'] }} @endif</title>

  <!-- Bootstrap CSS -->
  {!! $tangobb_css !!}
  <link rel="stylesheet" href="{{ asset('assets/default/css/layout.css') }}" />
</head>
<body>

  @include('global.masterNavigation')

  @hasSection('pageHeader')
  <div class="jumbotron header-jumbotron"@hasSection('pageHeaderColor') style="background-color:#@yield('pageHeaderColor')" @endif>
    <div class="container-fluid" data-midnight="header-jumbotron">
      <h1 class="display-5">@yield('pageHeader')</h1>
      @hasSection('pageHeaderDescription')
      <p class="lead">
        @yield('pageHeaderDescription')
      </div>
      @endif
    </div>
  </div>
  @endif

  <div class="container-fluid">
    <div class="row">
      @yield('content')
    </div>
  </div>

  <footer class="footer">
    Powered by TangoBB
  </footer>

  <!-- jQuery first, then Bootstrap JS. -->
  {!! $jquery !!}
  {!! $tangobb_js !!}
  <script src="{{ asset('assets/default/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/default/js/default.js') }}"></script>
</body>
</html>
