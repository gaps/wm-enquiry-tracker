//Define routes for the application
angular.module('app',[]).
    config(['$routeProvider', function ($routeProvider) {
        $routeProvider.

            when('/users/login', {templateUrl: '/users/login',controller: User_Login});

    }]);