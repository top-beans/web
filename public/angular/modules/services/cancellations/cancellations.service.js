'use strict';

angular.module('cancellationsService').

service('cancellationsService', ['$http', function ($http) {
    var self = this;

    self.getOrder = function (cancellationCode, email, callback) {
        var cancellationDetails = {
            code: cancellationCode,
            email: email
        };
        
        $http.post("/api/CancellationsApi/getorder", cancellationDetails).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.cancelOrderItem = function (cancellationCode, email, coffeeKey, callback) {
        var cancellationDetails = {
            code: cancellationCode,
            email: email,
            coffeekey: coffeeKey
        };
        
        $http.post("/api/CancellationsApi/cancelorderitem", cancellationDetails).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.cancelOrder = function (cancellationCode, email, callback) {
        var cancellationDetails = {
            code: cancellationCode,
            email: email
        };
        
        $http.post("/api/CancellationsApi/cancelorder", cancellationDetails).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
}]);