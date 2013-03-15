//'use strict';
//
////for route user/login
//angular.module('app')
//    .controller('User_Login', ['$scope', '$http', '$window', function ($scope, $http, window) {
//        $scope.email = "";
//        $scope.password = "";
//        $scope.showError = false;
//
//        $scope.loginUser = function (user) {
//            $scope.showError = false;
//
//            $http.post(
//                '/users/login',
//                {
//                    'email': user.email,
//                    'password': user.password
//                }
//            ).success(function (data) {
//                    if (data.status == true) {
//                        window.location.href = data.url;
//                    } else {
//                        $scope.showError = true;
//                    }
//                }
//            ).error(function (data) {
//                    $scope.showError = true;
//                    log('error', data);
//                });
//        }
//    }]);


//function User_Login($scope, $http) {
//    $scope.email = "";
//    $scope.password = "";
//    $scope.showError = false;
//
//    $scope.loginUser = function (user) {
//        $scope.showError = false;
//
//        $http.post(
//            '/users/login',
//            {
//                'email': user.email,
//                'password': user.password
//            }
//        ).success(function (data) {
//                if (data.status == true) {
//                    window.location.href = data.url;
//                } else {
//                    $scope.showError = true;
//                }
//            });
//    }
//}