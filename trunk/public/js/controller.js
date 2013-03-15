'use strict';

angular.module('app')
    .controller('Enquiry_Lit', ['$scope', '$http', function ($scope, $http) {
        console.log('coming here');
    }
    ]);


angular.module('app')
    .controller('User_Login', ['$scope', '$http', function ($scope, $http) {
        $scope.email = "";
        $scope.password = "";
        $scope.showError = false;

        $scope.loginUser = function (user) {

            console.log('coming here');

            $scope.showError = false;

            $http.post(
                '/user/login',
                {
                    'email': user.email,
                    'password': user.password
                }
            ).success(function (data) {
                    if (data.status == true) {
                        window.location.href = data.url;
                    } else {
                        $scope.showError = true;
                    }
                });
        }
    }
    ]);