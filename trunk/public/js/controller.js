/**
 * Created with JetBrains PhpStorm.
 * User: keshav
 * Date: 14/3/13
 * Time: 7:02 PM
 * To change this template use File | Settings | File Templates.
 */
function Enquiry_List_Controller($scope, $http) {
alert();

}


function User_Login($scope, $http) {
    $scope.email = "";
    $scope.password = "";
    $scope.showError = false;

    $scope.loginUser = function (user) {
        $scope.showError = false;

        $http.post(
            '/users/login',
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