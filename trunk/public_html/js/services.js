var module = angular.module('app');
module.factory('UserService', ["$http", "$q", function ($http, $q) {
    return {
        //function to get branches as per various filters. All filters are optional
        getBranches: function () {
            var deferred = $q.defer();          //defer for data

            $http.get(
                    '/enquiry/branches'
                ).success(function (data) {
                    //if data is proper array, return data else empty array
                    if (Array.isArray(data)) {
                        deferred.resolve(data);
                    }
                    else
                        deferred.resolve([]);
                }
            ).error(function (e) {
                    //if there is an error processing data, reject it and log error
                    console.log('error', e);
                    deferred.reject(e);
                }
            );

            return deferred.promise;
        },

        getTypes: function () {
            var deferred = $q.defer();          //defer for data

            $http.get(
                    '/enquiry/types'
                ).success(function (data) {
                    //if data is proper array, return data else empty array
                    if (Array.isArray(data)) {
                        deferred.resolve(data);
                    }
                    else
                        deferred.resolve([]);
                }
            ).error(function (e) {
                    //if there is an error processing data, reject it and log error
                    console.log('error', e);
                    deferred.reject(e);
                }
            );

            return deferred.promise;
        },
        getCourses: function () {
            var deferred = $q.defer();          //defer for data

            $http.get(
                    '/enquiry/courses'
                ).success(function (data) {
                    //if data is proper array, return data else empty array
                    if (Array.isArray(data)) {
                        deferred.resolve(data);
                    }
                    else
                        deferred.resolve([]);
                }
            ).error(function (e) {
                    //if there is an error processing data, reject it and log error
                    console.log('error', e);
                    deferred.reject(e);
                }
            );

            return deferred.promise;
        },

        getStatuses: function () {
            var deferred = $q.defer();          //defer for data

            $http.get(
                    '/enquiry/statuses'
                ).success(function (data) {
                    //if data is proper array, return data else empty array
                    if (Array.isArray(data)) {
                        deferred.resolve(data);
                    }
                    else
                        deferred.resolve([]);
                }
            ).error(function (e) {
                    //if there is an error processing data, reject it and log error
                    console.log('error', e);
                    deferred.reject(e);
                }
            );

            return deferred.promise;
        }
    }


}]);

module.factory('EnquiryService', ["$http", "$q", function ($http, $q) {
    return {

        getEnquiries: function (fromDate, toDate, branchIds, status, types, name, pageNumber, pageCount) {
            var deferred = $q.defer();          //defer for data

            $http.post(
                '/enquiry/get-enquiries',
                {
                    fromDate: fromDate,
                    toDate: toDate,
                    branchIds: branchIds,
                    status: status,
                    types: types,
                    name: name,
                    pageNumber: pageNumber,
                    pageCount: pageCount
                }
            ).success(function (data) {
                    if (Array.isArray(data)) {
                        deferred.resolve(data);
                    }
                    else
                        deferred.resolve([]);
                }).error(function (data) {
                    console.log('error', data);
                    deferred.reject(data);
                });

            return deferred.promise;
        },

        addEnquiries: function (enquiry) {
            var deferred = $q.defer();          //defer for data

            $http.post(
                '/enquiry/add-enquiry',
                {
                    name: enquiry.name,
                    program: enquiry.program,
                    email: enquiry.email,
                    mobile: enquiry.mobile,
                    type: enquiry.type,
                    branch_id: enquiry.branchId,
                    date: enquiry.enquiryDate
                }
            ).success(function (data) {

                    deferred.resolve(data);

                }).error(function (data) {
                    console.log('error', data);
                    deferred.reject(data);
                });

            return deferred.promise;
        }

    }


}]);

module.factory('FollowupService', ["$http", "$q", function ($http, $q) {
    return {

        getFollowups: function (fromDate, toDate, branchIds, types, name, pageNumber, pageCount) {
            var deferred = $q.defer();          //defer for data

            $http.post(
                '/enquiry/get-followups',
                {
                    fromDate: fromDate,
                    toDate: toDate,
                    branchIds: branchIds,
                    types: types,
                    name: name,
                    pageNumber: pageNumber,
                    pageCount: pageCount
                }
            ).success(function (data) {
                    if (Array.isArray(data)) {
                        deferred.resolve(data);
                    }
                    else
                        deferred.resolve([]);
                }).error(function (data) {
                    console.log('error', data);
                    deferred.reject(data);
                });

            return deferred.promise;
        }

    }

}]);

module.factory('EditEnquiryService', ["$http", "$q", function ($http, $q) {
    return {

        getEnquiry: function (id) {
            var deferred = $q.defer();          //defer for data

            $http.post(
                '/enquiry/get-enquiry',
                {
                    enquiryId: id
                }
            ).success(function (data) {
                    deferred.resolve(data);
                }).error(function (data) {
                    console.log('error', data);
                    deferred.reject(data);
                });

            return deferred.promise;
        },
        updateEnquiry: function (enquiry) {

            $http.post(
                '/enquiry/update-enquiry',
                {
                    'enquiryId': enquiry.id,
                    'name': enquiry.name,
                    'mobile': enquiry.mobile,
                    'email': enquiry.email,
                    'date': enquiry.enquiryDate,
                    'program': enquiry.course,
                    'branch_id': enquiry.branch_id,
                    'type': enquiry.type
                }
            ).success(function (data) {
                    window.location.href = "/#/enquiry/list/";
                }).error(function (data) {
                    console.log(data);
                });


        }

    }

}]);
