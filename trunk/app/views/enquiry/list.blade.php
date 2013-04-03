<div class="row">
    <div class="span3">
        <p>
            <label><strong>From Date</strong></label>

        <div class="input-append date date-input" data-date-format="dd M yyyy">
            <input class="span2" type="text" id="fromDate" ng-model="fromDate">
            <span class="add-on"><i class="icon-calendar"></i></span>
        </div>

        <label><strong>To Date</strong></label>

        <div class="input-append date date-input" data-date-format="dd M yyyy">
            <input class="span2" type="text" id="toDate" ng-model="toDate">
            <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        </p>

        <label><strong>Name</strong></label>

        <input type="text" ng-model="name">

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
            <!--            <a href="#myModal" role="button" class="btn" data-toggle="modal">Add Enquiry</a>-->
            <button class="btn" type="button" ng-click="showAddEnquiryModal()">Add Enquiry</button>
            <button class="btn" type="button" ng-click="exportData()">Export</button>
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
                <td>{{ getStatus(enquiry)}}</td>
                <td>{{ enquiry.type }}</td>
                <td>{{ enquiry.branch.name }}</td>
                <td style="max-width: 100px;">{{ getStatusText(enquiry) }}</td>
                <td>
                    <div ng-show="checkStatus(enquiry)" class="dropdown">
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
                            <li><a href="#/enquiry/edit/{{enquiry.id}}">Edit</a></li>
                        </ul>
                    </div>
                    <span ng-hide="checkStatus(enquiry)"><a href="#/enquiry/edit/{{enquiry.id}}">Edit</a></span>
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
                    class="icon-arrow-left"></i>
            </button>
            <button class="btn" ng-disabled="enquiries.length ==0" ng-click="updateNext()"><i
                    class="icon-arrow-right"></i></button>
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
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
        <h3 style="border:none;" id="myModalLabel">Add Enquiry</h3>

    </div>
    <div class="modal-body">
        <p>
        </p>

        <form name="form" id="addEnquiryForm" novalidate>
            <div>
                <label>Name</label>
                <input type="text" name="enquiryName" ng-required="true" ng-model="enquiry.name" placeholder="Name">
 <span ng-show="form.enquiryName.$error.required && !form.enquiryName.$pristine "
       class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter your name</span>
            </div>


            <label for="enquiryDate">Date</label>
            <input size="16" class="date datetime-input" type="text" id="enquiryDate"
                   ng-model="enquiry.enquiryDate" value="" readonly>


            <div>
                <label>Mobile</label>
                <input type="text" id="inputMobile" name="mobile" ng-model="enquiry.mobile" ng-minLength="8"
                       ng-pattern="/^\+{0,1}\d+$/" ng-required="true" placeholder="Mobile">
                            <span ng-show="form.mobile.$error.required && !form.mobile.$pristine "
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter your mobile number</span>
                            <span
                                ng-show="form.mobile.$invalid && !form.mobile.$pristine && !form.mobile.$error.required"
                                class="validation invalid">
                                <i class="icon-remove padding-right-5"></i>
                                The mobile number must be at least 8 digits
                            </span>
                            <span ng-show="form.mobile.$valid && !form.mobile.$pristine"
                                  class="validation valid">
                                <i class="icon-ok padding-right-5"></i>
                            </span>
            </div>
            <label>Course</label>

            <select ng-model="enquiry.program" ng-required="true">
                <option ng-repeat="course in courses" value="{{  course }}">{{ course }}</option>
            </select>
            <label>Email-Id</label>
            <input type="email" ng-model="enquiry.email" ng-required="true" placeholder="Email-Id">
            <label>Type</label>


            <select ng-model="enquiry.type" ng-required="true">
                <option ng-repeat="type in types" value="{{type.name}}">{{type.name}}</option>
            </select>
            <label>Branch</label>
            <select ng-model="enquiry.branchId" ng-required="true">
                <option ng-repeat="branch in branches" value="{{ branch.value.id }}">{{ branch.value.name }}</option>
            </select>

        </form>
        <p></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary" ng-disabled="form.$invalid" ng-click="addEnquiry(enquiry)">Save</button>
    </div>
</div>

<script type="text/javascript">
    initComponents();
</script>

