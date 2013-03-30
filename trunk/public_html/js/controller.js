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
        $scope.pageNumber = 1;
        $scope.pageCount = 25;
        $scope.previousPage = 0;
        $scope.nextPage = $scope.pageNumber + 1;
        $scope.courses = [];

        $userService.getBranches().then(function (data) {
            $scope.branches = data.map(function (val) {
                return {"value": val, "selected": true};
            });
        });

        $scope.getFormattedDate = function ($date) {
            return moment($date).format('Do MMM  YYYY');
        };

        $userService.getTypes().then(function (data) {
            $scope.types = data.map(function (val) {
                return {"name": val, "selected": true};
            });
        });

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
        };

        $scope.getSelectedTypes = function () {
            var typeSelected = new Array();
            angular.forEach($scope.types, function (type) {
                if (type.selected)
                    typeSelected.push(type.name);
            });
            return typeSelected;
        };

        $scope.getSelectedStatuses = function () {
            var statusIds = new Array();
            angular.forEach($scope.statuses, function (status) {
                if (status.selected)
                    statusIds.push(status.value.id);
            });
            return statusIds;
        };

        $scope.addEnquiry = function (enquiry) {
            $enquiryService.addEnquiries(enquiry).then(function (value) {
                $('#myModal').modal('hide');
                $scope.enquiry = {};
                $scope.enquiries.unshift(value);
            });

        };


        $scope.getPageEnquiries = function () {
            $scope.toDate = $('#toDate').val();
            $scope.fromDate = $('#fromDate').val();

            $enquiryService.getEnquiries($scope.fromDate, $scope.toDate, $scope.getSelectedBranches(), $scope.getSelectedStatuses(), $scope.getSelectedTypes(), $scope.pageNumber, $scope.pageCount).then(function (value) {
                $scope.enquiries = value;
            });
        };

        $scope.getEnquiries = function () {
            $scope.pageNumber = 1;
            $scope.pageCount = 25;
            $scope.previousPage = 0;
            $scope.toDate = $('#toDate').val();
            $scope.fromDate = $('#fromDate').val();

            $enquiryService.getEnquiries($scope.fromDate, $scope.toDate, $scope.getSelectedBranches(), $scope.getSelectedStatuses(), $scope.getSelectedTypes(), $scope.pageNumber, $scope.pageCount).then(function (value) {
                $scope.enquiries = value;
            });
        }

        $scope.getStatusText = function (enquiry) {
            return enquiry.enquiry_status.length > 0 ? ((enquiry.enquiry_status[0].remarks == null) || (enquiry.enquiry_status[0].remarks == "") ? "NA" : enquiry.enquiry_status[0].remarks) : "NA";
        }

        $scope.updateNext = function () {
            $scope.previousPage = $scope.pageNumber;
            $scope.pageNumber = $scope.nextPage;
            $scope.nextPage = $scope.nextPage + 1;
            $scope.getPageEnquiries();
        }

        $scope.updatePrevious = function () {
            $scope.pageNumber = $scope.previousPage;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.previousPage = $scope.previousPage - 1;
            $scope.getPageEnquiries();

        }

        $scope.getStatus = function ($enquiry) {
            switch ($enquiry.enquiry_status[0].status) {
                case 'enrolled':
                    return 'Enrolled';
                    break;
                case 'not_interested':
                    return 'Not Interested';
                    break;
                case 'follow_up':
                    return 'Enrolled Later';
                    break;
                default:
                    return 'New';
                    break;
            }
        }

        $scope.checkStatus = function ($enquiry) {
            switch ($enquiry.enquiry_status[0].status) {
                case 'enrolled':
                    return false;
                    break;
                case 'not_interested':
                    return true;
                    break;
                case 'follow_up':
                    return true;
                    break;
                default:
                    return true;
                    break;
            }
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

        $scope.showAddEnquiryModal = function () {
            $scope.enquiry = {};
            $('#myModal').modal('show');
            $('#addEnquiryForm')[0].reset();
            $('#myModal span').hide();
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
        $scope.getCourses = function () {
            $scope.courses = $userService.getCourses();
        }

        $scope.getCourses();
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

        $scope.exportData = function () {
            $scope.toDate = $('#toDate').val();
            $scope.fromDate = $('#fromDate').val();
            $http.post(
                '/enquiry/get-export-enquiries',
                {
                    toDate: $scope.toDate,
                    fromDate: $scope.fromDate,
                    branchIds: $scope.getSelectedBranches(),
                    status: $scope.getSelectedStatuses(),
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

            $enquiryService.getEnquiries($scope.fromDate, $scope.toDate, $scope.getSelectedBranches(), $scope.getSelectedStatuses(), $scope.getSelectedTypes(), $scope.pageNumber, $scope.pageCount).then(function (value) {
                $scope.enquiries = value;
            });
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
            return enquiry.enquiry_status.length > 0 ? ((enquiry.enquiry_status[0].remarks == null) || (enquiry.enquiry_status[0].remarks == "") ? "No Remarks Available" : enquiry.enquiry_status[0].remarks) : "No Remarks Available";
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
            $scope.pageNumber = 1;
            $scope.pageCount = 25;
            $scope.previousPage = 0;
            $scope.toDate = $('#toDate').val();
            $scope.fromDate = $('#fromDate').val();
            $scope.followUps = $followupService.getFollowups($scope.fromDate, $scope.toDate, $scope.getSelectedBranches(), $scope.getSelectedTypes(), $scope.pageNumber, $scope.pageCount);
        }

        $scope.getPageFollowups = function () {
            $scope.toDate = $('#toDate').val();
            $scope.fromDate = $('#fromDate').val();
            $scope.followUps = $followupService.getFollowups($scope.fromDate, $scope.toDate, $scope.getSelectedBranches(), $scope.getSelectedTypes(), $scope.pageNumber, $scope.pageCount);
        }

        $scope.updateNext = function () {
            $scope.previousPage = $scope.pageNumber;
            $scope.pageNumber = $scope.nextPage;
            $scope.nextPage = $scope.nextPage + 1;
            $scope.getPageFollowups();
        }

        $scope.updatePrevious = function () {
            $scope.pageNumber = $scope.previousPage;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.previousPage = $scope.previousPage - 1;
            $scope.getPageFollowups();

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
            $scope.followUps = $followupService.getFollowups($scope.fromDate, $scope.toDate, $scope.getSelectedBranches(), $scope.getSelectedTypes(), $scope.pageNumber, $scope.pageCount);
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

//angular.module('app')
//    .controller('Enquiry_Edit_Controller', ['$scope', '$http', '$routeParams', 'UserService', function ($scope, $http, $routeParams, $userService) {
//        $scope.branches = [];
//        $scope.types = [];
//        $scope.statuses = [];
//        $scope.courses = [];
//        $scope.email = '';
//        $scope.name = '';
//        $scope.mobile = '';
//        $scope.course = '';
//        $scope.id = $routeParams.id;
//        $scope.courses = [];
//        $scope.enquiryDate = dateFormat(new Date(), 'dd mmmm yyyy');
//        $userService.getBranches().then(function (data) {
//            $scope.branches = data;
//        });
//
//        $scope.getFormattedDate = function ($date) {
//            return moment($date).format('Do MMM  YYYY');
//        };
//
//        $userService.getTypes().then(function (data) {
//            $scope.types = data;
//        });
//
//        $userService.getStatuses().then(function (data) {
//            $scope.statuses = data.map(function (val) {
//                return {"value": val, "selected": true};
//            });
//        });
//
//        $scope.getCourses = function () {
//            $scope.courses = $userService.getCourses();
//        }
//
//        $scope.getCourses();
//
//        $scope.editEnquiry = function () {
//            $scope.enquiryDate = $('#enquiryDate').val();         //bad hack because of angular update issue
//
//            $http.post(
//                '/enquiry/get-enquiry',
//                {
//                    enquiryId: $scope.id
//                }
//            ).success(function ($data) {
//                    $scope.name = $data.name;
//                    $scope.mobile = $data.mobile;
//                    $scope.course = $data.program;
//                    $scope.branchId = $data.branch_id;
//                    $scope.type = $data.type;
//                    $scope.email = $data.email;
//                    $scope.enquiryDate = $scope.getFormattedDate($data.enquiryDate);
//
//
//                }).error(function (data) {
//                    //todo: log this
//                });
//        }
//        $scope.editEnquiry();
//
//        $scope.updateEnquiry = function () {
//            $scope.enquiryDate = $('#enquiryDate').val();         //bad hack because of angular update issue
//
//
//            $http.post(
//                '/enquiry/update-enquiry',
//                {
//                    'enquiryId': $scope.id,
//                    'name': $scope.name,
//                    'mobile': $scope.mobile,
//                    'email': $scope.email,
//                    'date': $scope.enquiryDate,
//                    'program': $scope.course,
//                    'branch_id': $scope.branchId,
//                    'type': $scope.type
//                }
//            ).success(function (data) {
//                    window.location.href = "/#/enquiry/list/";
//                }).error(function (data) {
//                    console.log(data);
//                });
//        }
//
//
//    }
//    ]);
//
angular.module('app')
    .controller('Enquiry_Edit_Controller', ['$scope', '$http', '$routeParams', 'UserService', 'EditEnquiryService', function ($scope, $http, $routeParams, $userService, $editEnquiryService) {
        $scope.branches = [];
        $scope.types = [];
        $scope.statuses = [];
        $scope.courses = [];

        $scope.id = $routeParams.id;
        $scope.courses = [];
        $scope.enquiry = {};
        $scope.enquiryDate = dateFormat(new Date(), 'dd mmmm yyyy');
        $userService.getBranches().then(function (data) {
            $scope.branches = data;
        });

        $scope.getFormattedDate = function ($date) {
            return moment($date).format('Do MMM  YYYY');
        };

        $userService.getTypes().then(function (data) {
            $scope.types = data;
        });

        $userService.getStatuses().then(function (data) {
            $scope.statuses = data.map(function (val) {
                return {"value": val, "selected": true};
            });
        });

        $scope.getCourses = function () {
            $scope.courses = $userService.getCourses();
        }

        $scope.getCourses();

        $editEnquiryService.getEnquiry($scope.id).then(function (data) {
            $scope.enquiry = data;
        });

        $scope.updateEnquiry= function(enquiry){
            $editEnquiryService.updateEnquiry(enquiry);
        }


//        $scope.editEnquiry = function () {
//            $scope.enquiryDate = $('#enquiryDate').val();         //bad hack because of angular update issue
//
//            $http.post(
//                '/enquiry/get-enquiry',
//                {
//                    enquiryId: $scope.id
//                }
//            ).success(function ($data) {
//                    $scope.name = $data.name;
//                    $scope.mobile = $data.mobile;
//                    $scope.course = $data.program;
//                    $scope.branchId = $data.branch_id;
//                    $scope.type = $data.type;
//                    $scope.email = $data.email;
//                    $scope.enquiryDate = $scope.getFormattedDate($data.enquiryDate);
//
//
//                }).error(function (data) {
//                    //todo: log this
//                });
//        }
//        $scope.editEnquiry();




    }
    ]);

