<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test</title>

    <title>SB Admin 2 - Login</title>

    
    <link href="{{url('assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{url('assets/css/sb-admin-2.css')}}" rel="stylesheet">

    <link href="{{url('assets/css/custom.css')}}" rel="stylesheet">
    

    @yield('header_scripts')


</head>
<body class="bg-gradient-primary">

    @yield('main')

    <!-- Bootstrap core JavaScript-->
    <script src="{{url('assets/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{url('assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{url('assets/js/sb-admin-2.min.js')}}"></script>

    @yield('footer_scripts')

</body>
</html>