<div class="row">
    <div class="span12">
        <h3>Edit a Enquiry</h3>
    </div>
</div>
<form name="form" novalidate="novalidate">

    <div class="row">
        <div class="span4">
            <label for="name">Name</label>
            <input type="text"  name="studentName" ng-required="true" id="name"
                   placeholder="Enter full name"
                   class="span4">
        </div>
        <div class="span4"><label for="name">Mobile</label>
            <input name="mobile" type="text" ng-model="mobile" ng-required="true" ng-minLength="8" 
                   id="mobile" class="span4">
        </div>
        <div class="span4">
            <label for="branch">Branch</label>
            <select ng-model="branchId" id="branch" ng-required="true" class="span4">
                @foreach ($branches as $branch)
                <option value="<% $branch->id %>"><% $branch->name %></option>
                @endforeach
            </select>
        </div>
    </div>

    <p>&nbsp;</p>

    <div class="row">
        <div class="span4">
            <label for="demoDate">Demo Date</label>

            <div class="input-append date datetime-input" data-date-format="dd M yyyy hh:ii">
                <input size="16" type="text" class="span3" id="demoDate" ng-model="demoDate" value="" readonly>
                <span class="add-on"><i class="icon-calendar"></i></span>
            </div>

        </div>
        <div class="span4">
            <label for="course">Program</label>
            <select ng-model="course" id="course" class="span4">
                @foreach ($courses as $course)
                <option value="<% $course %>"><% $course %></option>
                @endforeach
            </select>
        </div>
        <div class="span2">
            <label for="faculty">Faculty</label>
            <input type="text" ng-model="faculty" ng-required="true" id="faculty" placeholder="Enter full name"
                   class="span2">
        </div>
        <div class="span2">
            <label for="counsellor">Counsellor</label>
            <input type="text" ng-model="counsellor" ng-required="true" id="counsellor" placeholder="Enter full name"
                   class="span2">
        </div>
    </div>
    <div class="row">
        <div class="span4">
            <button type="button" ng-disabled="form.$invalid" ng-click="saveDemo()" class="btn btn-success">Save
            </button>
        </div>
    </div>
</form>

<script type="text/javascript">
    initComponents();
</script>
