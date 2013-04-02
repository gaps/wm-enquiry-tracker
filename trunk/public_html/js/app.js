'use strict';
//service for loader module
angular.module('LoaderServices', [])
    .config(function ($httpProvider) {
        $httpProvider.responseInterceptors.push('appHttpInterceptor');
        var spinnerFunction = function (data, headersGetter) {
            // todo start the spinner here
            $('#ajax-loader').show();

            return data;
        };
        $httpProvider.defaults.transformRequest.push(spinnerFunction);
    })
// register the interceptor as a service, intercepts ALL angular ajax http calls
    .factory('appHttpInterceptor', function ($q, $window) {
        return function (promise) {
            return promise.then(function (response) {
                // todo hide the spinner
                $('#ajax-loader').hide();
                return response;

            }, function (response) {
                // todo hide the spinner
                $('#ajax-loader').hide();
                return $q.reject(response);
            });
        };
    });



//Define routes for the application
angular.module('app', ['LoaderServices']).
    config(['$routeProvider' , function ($routeProvider) {
        $routeProvider.
            when('/', {templateUrl: '/enquiry/list',
                controller: 'Enquiry_List'
            }).
            when('/user/login', {templateUrl: '/user/login', controller: 'User_Login'}).
            when('/', {templateUrl: '/enquiry/list', controller: 'Enquiry_List'}).
            when('/followup/list', {templateUrl: '/enquiry/followups', controller: 'Followup_List'}).
            when('/enquiry/edit/:id', {templateUrl: '/enquiry/edit', controller: 'Enquiry_Edit_Controller'}).
            when('/enquiry/list', {
                templateUrl: '/enquiry/list',
                controller: 'Enquiry_List'
            });

        $routeProvider.otherwise({redirectTo: '/'});
    }]);


function initComponents() {
    $(function () {
        //initialize datepicker component

        $(".datetime-input,.date-input").datepicker({
            format: "dd MM yyyy",
            autoclose: true,
            todayBtn: true,
            todayHighlight: true
        });
    });
};

