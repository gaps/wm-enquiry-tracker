<div class="row" ng-controller="User_Login">
    <div class="span4 offset4 well">
        <legend>Please Sign In</legend>
        <div class="alert alert-error" ng-show="showError">
            Incorrect Username or Password!
        </div>

        <form name="form" novalidate>
            <input type="email" class="span4" ng-required="true" ng-model="user.email" placeholder="Email">
            <input type="password" class="span4" ng-required="true" ng-model="user.password" placeholder="Password">
            <label class="checkbox">
                <input type="checkbox" name="remember" value="true"> Remember Me
            </label>
            <button type="button" ng-disabled="form.$invalid" ng-click="loginUser(user)" class="btn btn-info btn-block">Sign in</button>
        </form>
    </div>
</div>

