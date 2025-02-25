<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="Fast and easy onlye education center" name="description" />
    <meta content="Bakhtishod" name="author" />

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    {{-- <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet"> --}}
    {{-- <link href="{{asset('coloradmin/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css')}}" rel="stylesheet" /> --}}
    <link href="{{asset('coloradmin/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('coloradmin/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
    {{-- <link href="{{asset('coloradmin/css/animate.min.css')}}" rel="stylesheet" /> --}}
    <link href="{{asset('coloradmin/css/style.min.css')}}" rel="stylesheet" />
    {{-- <link href="{{asset('coloradmin/css/style-responsive.min.css')}}" rel="stylesheet" /> --}}
    {{-- <link href="{{asset('coloradmin/css/theme/default.css')}}" rel="stylesheet" id="theme" /> --}}
    <!-- ================== END BASE CSS STYLE ================== -->
    <style>
        .login-cover-bg {
            background-image: url("{{asset('coloradmin/img/login-bg/bg-3.jpg')}}");
            max-width: 100%;
            max-height: 100%;
            content: '';
            background-position: center;
            background-size: cover;
        }
    </style>
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{asset('coloradmin/plugins/pace/pace.min.js')}}"></script>
    <!-- ================== END BASE JS ================== -->
</head>

<body class="pace-top">
    <!-- begin #page-loader -->
    <div id="page-loader" class="fade in">
     
        <span class="spinner"></span>
    </div>

    <!-- end #page-loader -->
    <div class="login-cover">
        <div class="login-cover-bg"></div>
    </div>

    <div class="content">
        @yield('content')
    </div>
    <script>
        let eye1 = document.getElementById('eye1')
        let eye1none = document.getElementById('eye1None')
        let passOne = document.getElementById('inputElementIdOne')

        function eyeFunction() {
            passOne.type == 'password' ? (passOne.type = 'text', eye1none.style.display = 'none', eye1.style.display = 'inline-block') : (passOne.type = 'password', eye1.style.display = 'none', eye1none.style.display = 'inline-block')

        }
    </script>
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{asset('coloradmin/plugins/jquery/jquery-1.9.1.min.js')}}"></script>
    <script src="{{asset('coloradmin/plugins/jquery/jquery-migrate-1.1.0.min.js')}}"></script>
    <script src="{{asset('coloradmin/plugins/jquery-ui/ui/minified/jquery-ui.min.js')}}"></script>
    <script src="{{asset('coloradmin/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- ================== END BASE JS ================== -->

    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="{{asset('coloradmin/js/login-v2.demo.min.js')}}"></script>
    <script src="{{asset('coloradmin/js/apps.min.js')}}"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->
    <script>
        $(document).ready(function() {
            App.init();
            LoginV2.init();
        });
    </script>
</body>

</html>