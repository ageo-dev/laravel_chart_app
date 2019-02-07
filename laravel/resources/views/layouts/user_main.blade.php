<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $headTitle }}</title>

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
                            <li><a class="drawer-menu-item" href="{{route('logout')}}">Logout</a></li>
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
                    <a href="{{route('logout')}}">
                        <p class="mt50">Logout</p>
                    </a>
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