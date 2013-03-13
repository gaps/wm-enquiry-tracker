function User_Login($scope, $http) {
    $scope.email = "";
    $scope.password = "";
    $scope.showError = false;

    $scope.loginUser = function (user) {
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