'use strict';

//Define routes for the application
angular.module('app', []).
    config(['$routeProvider', function ($routeProvider) {
        $routeProvider.
            when('/user/login', {templateUrl: '/user/login', controller: 'User_Login'}).
            when('/enquiry/', {templateUrl: '/enquiry/list', controller: 'Enquiry_List'});


        $routeProvider.otherwise({redirectTo: '/'});
    }]);