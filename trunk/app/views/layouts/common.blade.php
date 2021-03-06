<!doctype html>
<html lang="en" ng-app="app">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Wisdom Mart - Enquiry Tracker</title>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
    <link href="<% URL::asset('css/datepicker.css'); %>" media="all" type="text/css" rel="stylesheet">
    <link href="<% URL::asset('css/application.css'); %>" media="all" type="text/css" rel="stylesheet">

    <!--Scripts-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.0.4/angular.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js"></script>

    <script src="<% URL::asset('js/app.js'); %>"></script>
    <script src="<% URL::asset('js/services.js'); %>"></script>
    <script src="<% URL::asset('js/controller.js'); %>"></script>
    <script src="<% URL::asset('js/date-format.js'); %>"></script>
    <script src="<% URL::asset('js/bootstrap-datepicker.js'); %>"></script>
    <script src="<% URL::asset('js/moment.min.js'); %>"></script>
</head>
<body>


<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#">Wisdom Mart - Enquiry Tracker</a>

            <div class="nav-collapse collapse">
                @if(Auth::check())
                <ul class="nav">
                    <li class="active"><a href="/">Home</a></li>
                    <li><a href="#/enquiry/list">Enquiries List</a></li>
                    <li><a href="#/followup/list">Follow Up</a></li>
                    <li><a href="<% URL::to("/user/logout") %>">Logout</a></li>
                </ul>
                @endif
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>


<div class="container" ng-view></div>
<div id="ajax-loader">
    Loading ...
</div>

</body>
</html>