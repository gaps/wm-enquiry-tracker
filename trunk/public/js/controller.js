'use strict';

angular.module('app')
    .controller('Enquiry_List', ['$scope', '$http', 'UserService', 'EnquiryService', function ($scope, $http, $userService, $enquiryService) {

        $scope.branches = [];
        $scope.types = [];
        $scope.statuses = [];
        $scope.toDate = dateFormat(new Date(), 'dd mmmm yyyy');
        $scope.fromDate = dateFormat(new Date(), 'dd mmmm yyyy');
        $scope.enquiries = [];


        $userService.getBranches().then(function (data) {
            $scope.branches = data.map(function (val) {
                return {"name": val, "selected": true};
            });
        });

        $userService.getTypes().then(function (data) {
            $scope.types = data.map(function (val) {
                return {"name": val, "selected": true};
            });
        });

        $userService.getStatuses().then(function (data) {
            $scope.statuses = data.map(function (val) {
                return {"name": val, "selected": true};
            });
        });


//        $timeout(function() {
            $scope.enquiries = $enquiryService.getEnquiries($scope.fromDate, $scope.toDate, $scope.branches, $scope.statuses, $scope.types);
//        }, 2000);


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