<div ng-controller="User_Login">
    <div>
        <div ng-show="showError">
            Incorrect Username or Password!
        </div>

        <form name="form" novalidate>
            <input type="email" ng-required="true" ng-model="user.email" placeholder="Email">
            <input type="password" ng-required="true" ng-model="user.password" placeholder="Password">
            <label class="checkbox">
                <input type="checkbox" name="remember" value="true"> Remember Me
            </label>
            <button type="button" ng-disabled="form.$invalid" ng-click="loginUser(user)">Sign in</button>
        </form>
    </div>
</div>