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
            ).error(function ($e) {
                    //if there is an error processing data, reject it and log error
                    log('error', $e);
                    deferred.reject($e);
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
            ).error(function ($e) {
                    //if there is an error processing data, reject it and log error
                    log('error', $e);
                    deferred.reject($e);
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
            ).error(function ($e) {
                    //if there is an error processing data, reject it and log error
                    log('error', $e);
                    deferred.reject($e);
                }
            );

            return deferred.promise;
        }
    }


}]);

module.factory('EnquiryService', ["$http", "$q", function ($http, $q) {
    return {

        getEnquiries: function (fromDate, toDate, branchIds, status, types) {
            var deferred = $q.defer();          //defer for data

            $http.post(
                '/enquiry/get-enquiries',
                {
                    fromDate: fromDate,
                    toDate: toDate,
                    branchIds: branchIds,
                    status: status,
                    types: types
                }
            ).success(function ($data) {
                    if (Array.isArray(data)) {
                        deferred.resolve(data);
                    }
                    else
                        deferred.resolve([]);
                }).error(function ($data) {
                    log('error', $e);
                    deferred.reject($e);
                });

            return deferred.promise;
        }

    }


}]);
