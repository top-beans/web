'use strict';

angular.module('cancellationsService').

service('cancellationsService', ['$http', function ($http) {
    var self = this;

    self.confirmGroupKey = function (groupKey, callback) {
        $http.post("/api/CancelApi/confirmgroupkey", groupKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };

    self.confirmCode = function (code, callback) {
        $http.post("/api/CancelApi/confirmcode", code).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };

    self.getOrderTotal = function (code, callback) {
        
        $http.post("/api/CancelApi/getordertotal", code).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.cancelOrderItems = function (code, coffeeKeys, callback) {
        var orderItems = {
            groupkey: code,
            coffeekeys: coffeeKeys
        };

        $http.post("/api/CancelApi/cancelorderitems", orderItems).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
}]);