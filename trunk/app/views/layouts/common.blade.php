<!doctype html>
<html lang="en" ng-app="app">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title></title>


    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>


    <!--Scripts-->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.0.4/angular.min.js"></script>

    <script src="{{{ URL::asset('js/controller/user.js') }}}"></script>

    <script src="{{{ URL::asset('js/app.js') }}}"></script>
</head>
<body>


<div class="container" ng-view>
    @yield('content')
</div>


</body>
</html>