<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(\Route::current() -> getName()=='register')
        <title>Add user</title>
    @elseif(\Route::current() -> getName()=='admin.register')
        <title>Add administrator</title>
    @else
        <title>{{ $headTitle }}</title>
    @endif

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">

    <!-- drawer.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/drawer/3.2.1/css/drawer.min.css">

    <!-- pickadate -->
    <link href="{{ asset('css/pickadate/default.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/pickadate/default.date.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/pickadate/default.time.css') }}" rel="stylesheet" type="text/css">
    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div class="visible-sm visible-xs">
        <div class="navbar navbar-default navbar-fixed-top sp_head">
            <!-- <div class="container"> -->
            <div class="drawer drawer--left">
                <div role="banner">
                    <button type="button" class="drawer-toggle drawer-hamburger">
                        <span class="sr-only">toggle navigation</span>
                        <span class="drawer-hamburger-icon"></span>
                    </button>
                    <nav class="drawer-nav" role="navigation">
                        <ul class="drawer-menu">
                            <li><span class="drawer-brand"></span></li>
                            <li><a class="drawer-menu-item" href="{{ route('admin.home') }}">Top</a></li>
                            <li><a class="drawer-menu-item" href="{{ route('admin.userList',['ascOrDesc'=>'desc']) }}">User list</a></li>
                            <li><a class="drawer-menu-item" href="{{ route('admin.userSearchView') }}">User search</a></li>
                           
                            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                                <li><a class="drawer-menu-item" href="{{ route('register') }}">Add user</a></li>                                
                            @endif

                            <li><a class="drawer-menu-item" href="{{ route('admin.baseScheduleList',['ascOrDesc'=>'desc']) }}">Basic schedules</a></li>

                            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                                <li><a class="drawer-menu-item" href="{{ route('admin.addBaseSchedule') }}">Basic schedules registration</a></li>                                
                            @endif

                            <li><a class="drawer-menu-item" href="{{ route('admin.customScheduleList',['ascOrDesc'=>'desc']) }}">Custom schedules</a></li>

                            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                <li><a class="drawer-menu-item" href="{{ route('admin.register') }}">Add administrator</a></li>                                
                            @endif
                            <li><a class="drawer-menu-item" href="{{ route('admin.adminList') }}">Administrator list</a></li>

                            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                <li><a class="drawer-menu-item" href="{{ route('admin.configSettingView') }}">Configration</a></li>                                
                            @endif
                            <li>
                                <a href="{{ route('admin.logout') }}"
                                    class="drawer-menu-item"
                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>   
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- </div> -->
        </div>
        <div id="menu_back" style="display:none"></div>
    </div>

    <div id="sp_space" class="visible-sm visible-xs"></div>
    <div class="container-fluid padding0">
        <div class="row margin0">
            <div class="visible-md visible-lg">
                <div id="side_menu" class="col-md-3 margin0">
                    <a href="{{ route('admin.home') }}">
                        <p class="mt50 @if(\Route::current() -> getName()=='admin.home') side_menu_active @endif">Top</p>
                    </a>
                    <a href="{{ route('admin.userList',['ascOrDesc'=>'desc']) }}">
                        <p @if(\Route::current() -> getName()=='admin.userList')class="side_menu_active"@endif>User list</p>
                    </a>
                    <a href="{{ route('admin.userSearchView') }}">
                        <p 
                            @if(\Route::current() -> getName()=='admin.userSearchView')class="side_menu_active"@endif
                            @if(\Route::current() -> getName()=='admin.userSearch')class="side_menu_active"@endif
                            >User search</p>
                    </a>

                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                        <a href="{{ route('register') }}">
                            <p @if(\Route::current() -> getName()=='register')class="side_menu_active"@endif>Add user</p>
                        </a>
                    @endif

                    <a href="{{ route('admin.baseScheduleList',['ascOrDesc'=>'desc']) }}">
                        <p @if(\Route::current() -> getName()=='admin.baseScheduleList')class="side_menu_active"@endif>Basic schedules</p>
                    </a>

                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                        <a href="{{ route('admin.addBaseSchedule') }}">
                            <p @if(\Route::current() -> getName()=='admin.addBaseSchedule')class="side_menu_active"@endif>Basic schedules registration</p>
                        </a>                        
                    @endif

                    <a href="{{ route('admin.customScheduleList',['ascOrDesc'=>'desc']) }}">
                        <p @if(\Route::current() -> getName()=='admin.customScheduleList')class="side_menu_active"@endif>Custom schedules</p>
                    </a>

                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                        <a href="{{ route('admin.register') }}">
                            <p @if(\Route::current() -> getName()=='admin.register')class="side_menu_active"@endif>Add administrator</p>
                        </a>
                        <a href="{{ route('admin.adminList') }}">
                            <p @if(\Route::current() -> getName()=='admin.adminList')class="side_menu_active"@endif>Administrator list</p>
                        </a>                        
                    @endif

                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                        <a href="{{ route('admin.configSettingView') }}">
                            <p @if(\Route::current() -> getName()=='admin.configSettingView')class="side_menu_active"@endif>Configration</p>
                        </a>                        
                    @endif

                    <a href="{{ route('admin.logout') }}"
                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        <p>
                            Logout
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
            <div class="col-xs-12 col-md-7 col-md-offset-4">
                <div id="pc_space" class="visible-md visible-lg"></div>
                    @yield('content')
            </div>
        </div>
    </div>
    <div id="footer" class="text-center visible-sm visible-xs">
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>

    <!-- iScroll -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.1.3/iscroll.min.js"></script>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/footer.js') }}"></script>

    <!-- drawer.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/drawer/3.2.1/js/drawer.min.js"></script>
    <script src="{{ asset('js/drawer_script.js') }}"></script>

    <!-- pickadate -->
    <script src="{{ asset('js/pickadate/picker.js') }}"></script>
    <script src="{{ asset('js/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('js/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('js/pickadate/lang-ja.js') }}"></script>
    <script src="{{ asset('js/pickadate/script.js') }}"></script>

</body>
</html>