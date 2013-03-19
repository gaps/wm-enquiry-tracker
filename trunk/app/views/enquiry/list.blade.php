<div class="row">
    <div class="span2">
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
                <input type="checkbox" ng-model="branch.selected"> {{ branch.value.name }}
            </label>
        </div>


        <label><strong>Types</strong></label>

        <div ng-repeat="type in types">
            <label class="checkbox">
                <input type="checkbox" ng-model="type.selected"> {{ type.name }}
            </label>
        </div>

        <label><strong>Status</strong></label>

        <div ng-repeat="status in statuses">
            <label class="checkbox">
                <input type="checkbox" ng-model="status.selected"> {{ status.value.name }}
                <span class="color-box-{{status.value.id}}"></span>
            </label>
        </div>
        <p style="float:left;">
            <button class="btn" type="button" ng-click="getEnquiries()">Filter</button>
        </p>

    </div>

    <div class="span9">
        <p>
            <a href="#myModal" role="button" class="btn" data-toggle="modal">Add Enquiry</a>
            <button class="btn" type="button">Export</button>
        </p>
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th>Date</th>
                <th>First Name</th>
                <th>Mobile</th>
                <th>Course</th>
                <th>Status</th>
                <th>Type</th>
                <th>Branch</th>
                <th>Remarks</th>
                <th>Edit</th>
            </tr>
            </thead>
            <tbody ng-show="enquiries.length>0">

            <tr ng-repeat="enquiry in enquiries" ng-class="getStatusCss(enquiry)">
                <td>{{ getFormattedDate(enquiry.enquiryDate) }}</td>
                <td>{{ enquiry.name }}</td>
                <td>{{ enquiry.mobile }}</td>
                <td>{{ enquiry.program }}</td>
                <td>{{ enquiry.enquiry_status[0].status }}</td>
                <td>{{ enquiry.type }}</td>
                <td>{{ enquiry.branch.name }}</td>
                <td style="max-width: 100px;">{{ getStatusText(enquiry) }}</td>
                <td>
                    <div class="dropdown">
                        <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#"
                           href="/page.html">
                            Edit
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li><a ng-click="setNew(enquiry)">New</a></li>
                            <li><a ng-click="showEnrollModal(enquiry)">Enrolled</a></li>
                            <li><a ng-click="showFollowupModal(enquiry)">Enroll Later</a></li>
                            <li><a ng-click="showNotInterestedModel(enquiry)">Not Interested</a></li>
                        </ul>
                    </div>
                </td>
            </tr>

            </tbody>
            <tbody ng-show="enquiries.length==0">
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
    </div>


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

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     style="display: none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Ã—</button>
        <h3 style="border:none;" id="myModalLabel">Add Enquiry</h3>

    </div>
    <div class="modal-body">
        <p>
        </p>

        <form>
            <label>Name</label>
            <input type="text" ng-model="enquiry.name" placeholder="Name">
            <label>Mobile</label>
            <input type="text" ng-model="enquiry.mobile" placeholder="Mobile">
            <label>Course</label>

            <select ng-model="enquiry.program">
                <option ng-repeat="course in courses" value="{{  course }}">{{ course }}</option>
            </select>
            <label>Email-Id</label>
            <input type="text" ng-model="enquiry.email" placeholder="Email-Id">
            <!--            <label>Status</label>-->
            <!--            <select ng-model="enquiry.status">-->
            <!--                <option value="enrolled">Enrolled</option>-->
            <!--                <option value="follow_up">Enrolled-Later</option>-->
            <!--                <option value="not_interested">Not Interested</option>-->
            <!--                <option value="created">New</option>-->
            <!--            </select>-->

            <label>Type</label>
            <select ng-model="enquiry.type">
                <option value="Walk-in">Walk-in</option>
                <option value="Telephonic">Telephonic</option>
                <option value="Other">Other</option>
            </select>
            <label>Branch</label>
            <select ng-model="enquiry.branchId">
                <option ng-repeat="branch in branches" value="{{ branch.value.id }}">{{ branch.value.name }}</option>
            </select>
        </form>
        <p></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary" ng-click="addEnquiry(enquiry)">Save</button>
    </div>
</div>

<script type="text/javascript">
    initComponents();
</script>

