'use strict';
//Define routes for the application
angular.module('app', []).
    config(['$routeProvider' , function ($routeProvider) {
        $routeProvider.
            when('/user/login', {templateUrl: '/user/login', controller: 'User_Login'}).
            when('/followup/list', {templateUrl: '/enquiry/followups', controller: 'Followup_List'}).
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
            format:"dd MM yyyy",
            autoclose:true,
            todayBtn:true,
            todayHighlight:true
        });
    });
}