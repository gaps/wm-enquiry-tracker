//Define routes for the application
angular.module('app',[]).
    config(['$routeProvider', function ($routeProvider) {
        $routeProvider.
            when('/users/login', {templateUrl: '/users/login'}).
            when('/users/logout', {templateUrl: '/users/logout'})

        when('/enquiry/', {templateUrl: '/enquiry/list', controller: Enquiry_List_Controller});
    }]);