<div class="row" ng-controller="Enquiry_List">

    <div class="span3">

        <label>Branches</label>

        <div ng-repeat="branch in branches">
            <label class="checkbox">
                <input type="checkbox" ng-model="branch.selected"> {{ branch.name }}
            </label>
        </div>


        <label>Types</label>
        <div ng-repeat="type in types">
            <label class="checkbox">
                <input type="checkbox" ng-model="type.selected"> {{ type.name }}
            </label>
        </div>

        <label>Status</label>
        <div ng-repeat="status in statuses">
            <label class="checkbox">
                <input type="checkbox" ng-model="status.selected"> {{ status.name }}
            </label>
        </div>


    </div>

    <div class="span9">

        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th>Enquiry Date</th>
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

            <tr ng-repeat="enquiry in enquiries">
                <td>{{ getFormattedDate(enquiry.enquiryDate) }}</td>
                <td>{{ enquiry.name }}</td>
                <td>{{ enquiry.mobile }}</td>


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

    </div>


</div>


