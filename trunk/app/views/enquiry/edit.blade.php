<div class="row">
    <div class="span12">
        <h3>Edit a Enquiry</h3>
    </div>
</div>
<form name="form" novalidate="novalidate">

    <div class="row">
        <div class="span4">
            <label for="name">Name</label>
            <input type="text" name="studentName" ng-model="name" ng-required="true" id="name"
                   placeholder="Enter full name"
                  class="span3">
            <div ng-show="form.studentName.$error.required && !form.studentName.$pristine "
                  class="validation invalid">Please enter name.</div>

        </div>
        <div class="span4"><label for="name">Mobile</label>
            <input name="mobile" type="text" ng-model="mobile" ng-minLength="8" ng-pattern="/^\+{0,1}\d+$/" ng-required="true" placeholder="Mobile">
                            <div ng-show="form.mobile.$error.required && !form.mobile.$pristine "
                                  class="validation invalid">Please enter your mobile number.</div>
                            <div
                                ng-show="form.mobile.$invalid && !form.mobile.$pristine && !form.mobile.$error.required"
                                class="validation invalid">

                                The mobile number must be at least 8 digits.
                            </div>
                            <div ng-show="form.mobile.$valid && !form.mobile.$pristine"
                                  class="validation valid">

                            </div>
        </div>
        <div class="span4">
            <label>Branch</label>

<!--            <select ng-model="branchId">-->
<!--                <option ng-repeat="branch in branches" value="{{branch.id}}"> {{branch.name}}</option>-->
<!--            </select>-->
            <select ng-model="branchId" id="branch" ng-required="true" >
                @foreach ($branches as $branch)
                <option value="<% $branch->id %>"><% $branch->name %></option>
                @endforeach
            </select>
        </div>
    </div>



    <div class="row">
        <div class="span4">
            <label for="enquiryDate">Date</label>

            <div class="input-append date datetime-input" data-date-format="dd M yyyy hh:ii">
                <input size="16" type="text"  id="enquiryDate" ng-model="enquiryDate" value="" readonly>
                <span class="add-on"><i class="icon-calendar"></i></span>
            </div>

        </div>
        <div class="span4">
            <label>Type</label>
            <select ng-model="type" ng-required="true">
                <option ng-repeat="type in types">{{ type}}</option>
            </select>
        </div>
        <div class="span4">
            <label>Email</label>
            <input ng-required="true" name="email" id="email" ng-model="email"
                   type="email" placeholder="Enter an Email id">
            <div ng-show="form.email.$error.required && !form.email.$pristine " class="text-error">Please enter an email</div>
            <div ng-show="form.email.$error.email && !form.email.$pristine "
                  class="text-error">Enter a valid email id. </div>
        </div>
    </div>

    <div class="row">
        <div class="span4">
            <label>Course</label>
            <select ng-model="course" ng-required="true" class="span3">
                <option ng-repeat="course in courses">{{ course }}</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="span4">
            <button type="button" ng-disabled="form.$invalid" ng-click="updateEnquiry()" class="btn btn-success">Update
            </button>
        </div>
    </div>
</form>

<script type="text/javascript">
    initComponents();
</script>
