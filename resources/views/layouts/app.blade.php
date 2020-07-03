<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">

      <nav class="navbar navbar-default navbar-light bg-light">

        <!-- Main hamburger menu - only available when logged in -->
        <div class="dropdown">
          <a class="btn" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">

            @auth
              <div class="lemon-dropdown-name">{{Auth::user()->name}}</div>
            @endauth

            @if(session('public_routes'))
              <div class="dropdown-divider"></div>
            @endif

            @foreach(session('public_routes') as $public_route)
              <a class="dropdown-item btn text-capitalize" href="{{$public_route}}">{{str_replace("_"," ",$public_route)}}</a>
            @endforeach

            @auth
              <div class="dropdown-divider"></div>
              @foreach(session('user_routes') as $user_route)
                <a class="dropdown-item btn text-capitalize" href="{{$user_route}}">{{str_replace("_"," ",$user_route)}}</a>
              @endforeach
              <div class="dropdown-divider"></div>
              <a class="dropdown-item btn" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            @endauth

          </div>
        </div>

        <!-- Don't show the nav title on the welcome page -->
        @if(!in_array(explode(".",Route::current()->getName())[0],['','/']))
          @auth
            <a class="navbar-brand lemon-title-nav text-primary m-auto pr-5" href="\">
              <img src="./logo-icon.png" class="img-fluid mb-1" width="28" height="28">
              @if(session('organization'))
                {{session('organization')->name}}
              @else
                {{config('app.name')}}
              @endif
            </a>
          @else
          <a class="navbar-brand lemon-title-nav text-primary mr-auto" href="\">
            <img src="./logo-icon.png" class="img-fluid mb-1" width="28" height="28">
          </a>
          @endauth
        @endif

        <!-- Allow login and register if the user is not logged in -->
        <div class="lemon-nav-right mt-1">
          @guest
            <a class="lemon-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            <a class="lemon-link" href="{{ route('register') }}">{{ __('Register') }}</a>
          @endguest
        </div>

      </nav>

      <!-- Welcome page -->
      @if(in_array(explode(".",Route::current()->getName())[0],['','/']))
        <div class="lemon-welcome">
            <div class="lemon-title">
              <img src="./logo.png" class="img-fluid m-3" width="400">
              @if(session('organization'))
                <br/><h3>{{session('organization')->name}}</h3>
              @endif
            </div>
            <div class="ml-3">
              <a class="lemon-link" href="https://jamesgriffithsdevelopment.com/project/laravellemon">Website</a>
              <a class="lemon-link" href="https://github.com/jamesrgriffiths/laravellemon">GitHub</a>
            </div>
        </div>

      <!-- All other pages - add vue templates if they exist otherwise it will yeild content -->
      @elseif (explode(".",Route::current()->getName())[0] == 'home')<home></home>
      @elseif (explode(".",Route::current()->getName())[0] == 'logs')<logs></logs>
      @elseif (explode(".",Route::current()->getName())[0] == 'organizations')<organizations></organizations>
      @elseif (explode(".",Route::current()->getName())[0] == 'users')<users></users>
      @elseif (explode(".",Route::current()->getName())[0] == 'user_types')<user-types></user-types>
      @elseif (explode(".",Route::current()->getName())[0] == 'variables')<variables></variables>
      @else @yield('content') @endif

    </div>
</body>
</html>
