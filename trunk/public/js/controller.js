'use strict';

angular.module('app')
    .controller('Enquiry_List', ['$scope', '$http', 'UserService', 'EnquiryService', function ($scope, $http, $userService, $enquiryService) {

        $scope.branches = [];
        $scope.types = [];
        $scope.statuses = [];
        $scope.toDate = dateFormat(new Date(), 'dd mmmm yyyy');
        $scope.fromDate = dateFormat(new Date(), 'dd mmmm yyyy');
        $scope.enquiries = [];
        $scope.enquiry = {};
        $scope.courses = [];
        $scope.pageNumber = 1;
        $scope.pageCount = 25;
        $scope.previousPage = 0;
        $scope.nextPage = $scope.pageNumber + 1;

        $userService.getBranches().then(function (data) {
            $scope.branches = data.map(function (val) {
                return {"value": val, "selected": true};
            });
        });

        $scope.getFormattedDate = function ($date) {
            return moment($date).format('Do MMMM  YYYY');
        }

        $userService.getTypes().then(function (data) {
            $scope.types = data.map(function (val) {
                return {"name": val, "selected": true};
            });
        });


        $scope.getCourses = function () {
            $scope.courses = $userService.getCourses();
        }

        $scope.getCourses();
        $userService.getStatuses().then(function (data) {
            $scope.statuses = data.map(function (val) {
                return {"value": val, "selected": true};
            });
        });
        $scope.getSelectedBranches = function () {
            var branchIds = new Array();
            angular.forEach($scope.branches, function (branch) {
                if (branch.selected)
                    branchIds.push(branch.value.id);
            });
            return branchIds;
        }
        $scope.getSelectedTypes = function () {
            var typeSelected = new Array();
            angular.forEach($scope.types, function (type) {
                if (type.selected)
                    typeSelected.push(type.name);
            });
            return typeSelected;
        }
        $scope.getSelectedStatuses = function () {
            var statusIds = new Array();
            angular.forEach($scope.statuses, function (status) {
                if (status.selected)
                    statusIds.push(status.value.id);
            });
            return statusIds;
        }

        $scope.addEnquiry = function (enquiry) {
            var status = $enquiryService.addEnquiries(enquiry);
            $('#myModal').modal('hide');
            if (status) {
                window.location.href = '/#/enquiry/list';
            }

        }

        $scope.getEnquiries = function () {
            $scope.toDate = $('#toDate').val();
            $scope.fromDate = $('#fromDate').val();
            $scope.enquiries = $enquiryService.getEnquiries($scope.fromDate, $scope.toDate, $scope.getSelectedBranches(), $scope.getSelectedStatuses(), $scope.getSelectedTypes(),$scope.pageNumber, $scope.pageCount);

        }

        $scope.getStatusText = function (enquiry) {
            return enquiry.enquiry_status.length > 0 ?  ((enquiry.enquiry_status[0].remarks==null)||(enquiry.enquiry_status[0].remarks=="")?"No Remarks Available": enquiry.enquiry_status[0].remarks):"No Remarks Available";
        }

        $scope.setEnrollLater = function () {
            if ($scope.currentEnquiry == null) {
                alert('try again');
                return;
            }

            $scope.followupDate = $('#followup-date').val();

            $http.post(
                '/enquiry/create-followup',
                {
                    enquiryId: $scope.currentEnquiry.id,
                    followupDate: $scope.followupDate,
                    remarks: $scope.remarks
                }
            ).success(function ($enquiryStatus) {
                    $scope.currentEnquiry.enquiry_status.unshift($enquiryStatus);
                    $('#followup-modal').modal('hide');
                    $scope.currentEnquiry = null;
                    $scope.remarks = '';
                }).error(function ($data) {
                    //todo: log this
                });
        }
        $scope.showEnrollModal = function ($enquiry) {
            $scope.currentEnquiry = $enquiry;
            $('#enroll-modal').modal('show');
        }
        $scope.showFollowupModal = function ($enquiry) {
            $scope.currentEnquiry = $enquiry;
            $('#followup-modal').modal('show');
        }

        $scope.showNotInterestedModel = function ($enquiry) {
            $scope.currentEnquiry = $enquiry;
            $('#not-interested-modal').modal('show');
        }

        $scope.setEnrolled = function () {
            if ($scope.currentEnquiry == null) {
                alert('try again');
                return;
            }

            $scope.joiningDate = $('#joining-date').val();

            $http.post(
                '/enquiry/mark-enrolled',
                {
                    enquiryId: $scope.currentEnquiry.id,
                    joiningDate: $scope.joiningDate,
                    remarks: $scope.remarks
                }
            ).success(function ($enquiryStatus) {
                    $scope.currentEnquiry.enquiry_status.unshift($enquiryStatus);
                    $('#enroll-modal').modal('hide');
                    $scope.currentEnquiry = null;
                    $scope.remarks = '';
                }).error(function ($data) {
                    //todo: log this
                });

        }
        $scope.setNew = function ($enquiry) {
            $http.post('enquiry/mark-enquiry-new', {enquiryId: $enquiry.id}).success(function ($newEnquiryStatus) {
                $enquiry.enquiry_status.unshift($newEnquiryStatus);
            });
        }

        $scope.setNotInterested = function () {
            if ($scope.currentEnquiry == null) {
                alert('try again');
                return;
            }

            $http.post(
                '/enquiry/mark-enquiry-not-interested',
                {
                    enquiryId: $scope.currentEnquiry.id,
                    remarks: $scope.remarks
                }
            ).success(function ($enquiryStatus) {
                    $scope.currentEnquiry.enquiry_status.unshift($enquiryStatus);
                    $('#not-interested-modal').modal('hide');
                    $scope.currentEnquiry = null;
                    $scope.remarks = '';
                }).error(function ($data) {
                    //todo: log this
                });
        }
        $scope.updateNext = function () {
            $scope.previousPage = $scope.pageNumber;
            $scope.pageNumber = $scope.nextPage;
            $scope.nextPage = $scope.nextPage + 1;
            $scope.getEnquiries();
        }

        $scope.updatePrevious = function () {
            $scope.pageNumber = $scope.previousPage;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.previousPage = $scope.previousPage - 1;
            $scope.getEnquiries();

        }

        setTimeout(function () {

            $scope.enquiries = $enquiryService.getEnquiries($scope.fromDate, $scope.toDate, $scope.getSelectedBranches(), $scope.getSelectedStatuses(), $scope.getSelectedTypes(),$scope.pageNumber, $scope.pageCount);
        }, 300);

        $scope.getStatusCss = function ($enquiry) {
            switch ($enquiry.enquiry_status[0].status) {
                case 'absent':
                    return 'warning';
                    break;
                case 'enrolled':
                    return 'success';
                    break;
                case 'not_interested':
                    return 'error';
                    break;
                case 'follow_up':
                    return 'info';
                    break;

                default:
                    break;
            }
        }

    }
    ]);

angular.module('app')
    .controller('Followup_List', ['$scope', '$http', 'UserService', 'FollowupService', function ($scope, $http, $userService, $followupService) {
        $scope.branches = [];
        $scope.types = [];
        $scope.toDate = dateFormat(new Date(), 'dd mmmm yyyy');
        $scope.fromDate = dateFormat(new Date(), 'dd mmmm yyyy');
        $scope.followUps = [];
        $scope.pageNumber = 1;
        $scope.pageCount = 25;
        $scope.previousPage = 0;
        $scope.nextPage = $scope.pageNumber + 1;
        $scope.currentEnquiry = null;

        $userService.getBranches().then(function (data) {
            $scope.branches = data.map(function (val) {
                return {"value": val, "selected": true};
            });
        });

        $userService.getTypes().then(function (data) {
            $scope.types = data.map(function (val) {
                return {"name": val, "selected": true};
            });
        });

        $scope.getSelectedBranches = function () {
            var branchIds = new Array();
            angular.forEach($scope.branches, function (branch) {
                if (branch.selected)
                    branchIds.push(branch.value.id);
            });
            return branchIds;
        }
        $scope.getSelectedTypes = function () {
            var typeSelected = new Array();
            angular.forEach($scope.types, function (type) {
                if (type.selected)
                    typeSelected.push(type.name);
            });
            return typeSelected;
        }

        $scope.getFormattedDate = function ($date) {
            return moment($date).format('Do MMM  YYYY');
        }
        $scope.showEnrollModal = function ($enquiry) {
            $scope.currentEnquiry = $enquiry;
            $('#enroll-modal').modal('show');
        }
        $scope.showFollowupModal = function ($enquiry) {
            $scope.currentEnquiry = $enquiry;
            $('#followup-modal').modal('show');
        }

        $scope.showNotInterestedModel = function ($enquiry) {
            $scope.currentEnquiry = $enquiry;
            $('#not-interested-modal').modal('show');
        }

        $scope.setEnrolled = function () {
            if ($scope.currentEnquiry == null) {
                alert('try again');
                return;
            }

            $scope.joiningDate = $('#joining-date').val();

            $http.post(
                '/enquiry/mark-enrolled',
                {
                    enquiryId: $scope.currentEnquiry.id,
                    joiningDate: $scope.joiningDate,
                    remarks: $scope.remarks
                }
            ).success(function ($enquiryStatus) {
                    $scope.currentEnquiry.enquiry_status.unshift($enquiryStatus);
                    $('#enroll-modal').modal('hide');
                    $scope.currentEnquiry = null;
                    $scope.remarks = '';
                }).error(function ($data) {
                    //todo: log this
                });

        }

        $scope.getStatusCss = function ($enquiry) {
            switch ($enquiry.enquiry_status[0].status) {
                case 'absent':
                    return 'warning';
                    break;
                case 'enrolled':
                    return 'success';
                    break;
                case 'not_interested':
                    return 'error';
                    break;
                case 'follow_up':
                    return 'info';
                    break;

                default:
                    break;
            }
        }

        $scope.getStatusText = function (enquiry) {
            return enquiry.enquiry_status.length > 0 ? enquiry.enquiry_status[0].remarks : "No Remarks Available";
        }

        $scope.setEnrollLater = function () {
            if ($scope.currentEnquiry == null) {
                alert('try again');
                return;
            }

            $scope.followupDate = $('#followup-date').val();

            $http.post(
                '/enquiry/create-followup',
                {
                    enquiryId: $scope.currentEnquiry.id,
                    followupDate: $scope.followupDate,
                    remarks: $scope.remarks
                }
            ).success(function ($enquiryStatus) {
                    $scope.currentEnquiry.enquiry_status.unshift($enquiryStatus);
                    $('#followup-modal').modal('hide');
                    $scope.currentEnquiry = null;
                    $scope.remarks = '';
                }).error(function ($data) {
                    //todo: log this
                });
        }

        $scope.setNew = function ($enquiry) {
            $http.post('enquiry/mark-enquiry-new', {enquiryId: $enquiry.id}).success(function ($newEnquiryStatus) {
                $enquiry.enquiry_status.unshift($newEnquiryStatus);
            });
        }

        $scope.setNotInterested = function () {
            if ($scope.currentEnquiry == null) {
                alert('try again');
                return;
            }

            $http.post(
                '/enquiry/mark-enquiry-not-interested',
                {
                    enquiryId: $scope.currentEnquiry.id,
                    remarks: $scope.remarks
                }
            ).success(function ($enquiryStatus) {
                    $scope.currentEnquiry.enquiry_status.unshift($enquiryStatus);
                    $('#not-interested-modal').modal('hide');
                    $scope.currentEnquiry = null;
                    $scope.remarks = '';
                }).error(function ($data) {
                    //todo: log this
                });
        }

        $scope.getFilterFollowups = function () {
            $scope.toDate = $('#toDate').val();
            $scope.fromDate = $('#fromDate').val();
            $scope.followUps = $followupService.getFollowups($scope.fromDate, $scope.toDate, $scope.getSelectedBranches(), $scope.getSelectedTypes(), $scope.pageNumber, $scope.pageCount);
        }

        $scope.updateNext = function () {
            $scope.previousPage = $scope.pageNumber;
            $scope.pageNumber = $scope.nextPage;
            $scope.nextPage = $scope.nextPage + 1;
            $scope.getFilterFollowups();
        }

        $scope.updatePrevious = function () {
            $scope.pageNumber = $scope.previousPage;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.previousPage = $scope.previousPage - 1;
            $scope.getFilterFollowups();

        }

        $scope.exportData = function () {
            $scope.toDate = $('#toDate').val();
            $scope.fromDate = $('#fromDate').val();
            $http.post(
                '/enquiry/get-export-followups',
                {
                    toDate: $scope.toDate,
                    fromDate: $scope.fromDate,
                    branchIds: $scope.getSelectedBranches(),
                    types: $scope.getSelectedTypes()
                }
            ).success(function ($data) {
                    if ($data.status != false) {
                        var downloadFrame = '<iframe height="0" width="0" style="display:none" src="' + $data.filePath + '"></iframe>';
                        $(downloadFrame).appendTo('body');
                    }
                    else {
                        alert('No Data to export');
                    }
                }).error(function ($data) {
                    //todo: work for error
                });
        }

        setTimeout(function () {
            $scope.followUps = $followupService.getFollowups($scope.fromDate, $scope.toDate, $scope.getSelectedBranches(), $scope.getSelectedTypes(),$scope.pageNumber, $scope.pageCount);
        }, 500);

    }
    ]);

angular.module('app')
    .controller('User_Login', ['$scope', '$http', function ($scope, $http) {
        $scope.email = "";
        $scope.password = "";
        $scope.showError = false;

        $scope.loginUser = function (user) {

            console.log('coming here');

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
    ]);

