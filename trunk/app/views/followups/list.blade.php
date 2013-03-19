<div class="row">
<span class="span2">
    <p>
        <label><strong>From Date</strong></label>
        <div class="input-append date date-input" data-date-format="dd M yyyy">
            <input class="span1" type="text" id="fromDate" ng-model="fromDate">
            <span class="add-on"><i class="icon-calender"></i></span>
        </div>

    <label><strong>To Date</strong></label>
        <div class="input-append date date-input" data-date-format="dd M yyyy">
            <input class="span1" type="text" id="toDate" ng-model="toDate">
            <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
    </p>
    <label><strong>Branches</strong></label>
        <div ng-repeat="branch in branches">
            <label class="checkbox">
                <input type="checkbox" ng-model="branch.selected"> {{ branch.value.name}}
            </label>
        </div>
        <label><strong>Types</strong></label>
        <div ng-repeat="type in types">
            <label class="checkbox">
                <input type="checkbox" ng-model="type.selected"> {{ type.name }}
            </label>
        </div>
        <p style="float:left;">
            <button class="btn" type="button" ng-click="getFilterFollowups()">Filter</button>
        </p>

</span>

<span class="span10">
<p>
    <button class="btn" type="button" ng-click="exportData()">Export to Excel</button>
</p>
<table class="table table-striped">
    <thead>
    <tr>
        <th>FollowUp Date</th>
        <th>First Name</th>
        <th>Mobile</th>
        <th>Course</th>
        <th>Type</th>
        <th>Branch</th>
        <th>Remarks</th>
        <th>Edit</th>
    </tr>
    </thead>

    <tbody ng-show="followUps.length>0">

    <tr ng-repeat="followup in followUps" ng-class="getStatusCss(followup)">
        <td>{{ getFormattedDate(followup.enquiry_status[0].followupDate) }}</td>
        <td>{{ followup.name }}</td>
        <td>{{ followup.mobile }}</td>
        <td>{{ followup.program }}</td>
        <td>{{ followup.type }}</td>
        <td>{{ followup.branch.name }}</td>
        <td>{{ getStatusText(followup) }}</td>
        <td>
            <div class="dropdown">
                <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#"
                   href="/page.html">
                    Edit
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <li><a ng-click="setNew(followup)">New</a></li>
                    <li><a ng-click="showEnrollModal(followup)">Enrolled</a></li>
                    <li><a ng-click="showFollowupModal(followup)">Enroll Later</a></li>
                    <li><a ng-click="showNotInterestedModel(followup)">Not Interested</a></li>
                </ul>
            </div>
        </td>

    </tr>
    </tbody>
    <tbody ng-show="followUps.length==0">
    <tr>
        <td colspan="9" style="text-align: center">
            <br/>
            <strong>
                No Data found for given branch and date. Try selecting different date or branch.
            </strong>
            <br/>
            <br/>
        </td>
    </tr>
    </tbody>
</table>
    <div>
        <button class="btn" ng-disabled="previousPage == 0" ng-click="updatePrevious()"><i
                class="icon-caret-left icon-large"></i> << </button>
        <button class="btn" ng-disabled="followUps.length ==0" ng-click="updateNext()"><i
                class="icon-caret-right icon-large"></i> >>  </button>
    </div>
</span>
</div>

<!--Enrollment Modal-->
<div id="enroll-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="enroll-modal-label"
     aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="enroll-modal-label">Enrolled</h4>
    </div>
    <div class="modal-body">
        <p>
            <label for="joining-date">Enrollment Date</label>
            <input id="joining-date" placeholder="enter enrollment date for the student"
                   type="text" class="span3 date date-input" ng-model="joiningDate">

            <label for="enrollment-remarks">Remarks</label>
            <textarea id="enrollment-remarks" class="span3" rows="5" ng-model="remarks"></textarea>
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button ng-click="setEnrolled()" class="btn btn-primary">Enroll Student</button>
    </div>
</div>

<!--    Followup Modal-->
<div id="followup-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="followup-modal-label"
     aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="followup-modal-label">Enroll Later</h4>
    </div>
    <div class="modal-body">
        <p>
            <label for="followup-date">Followup Date</label>
            <input id="followup-date" placeholder="enter enrollment date for the student"
                   type="text" class="span3 date date-input" ng-model="followupDate">

            <label for="followup-remarks">Remarks</label>
            <textarea id="followup-remarks" class="span3" rows="5" ng-model="remarks"></textarea>
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button ng-click="setEnrollLater()" class="btn btn-primary">Mark Followup</button>
    </div>
</div>

<!--    not Interested Modal-->
<div id="not-interested-modal" class="modal hide fade" tabindex="-1" role="dialog"
     aria-labelledby="not-interested-modal-label" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="not-interested-modal-label">Not Interested</h4>
    </div>
    <div class="modal-body">
        <p>
            <label for="not-interested-remarks">Remarks</label>
            <textarea id="not-interested-remarks" rows="5" class="span3" ng-model="remarks"></textarea>
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button ng-click="setNotInterested()" class="btn btn-primary">Mark Not Interested</button>
    </div>
</div>
<script>
    initComponents();
</script>