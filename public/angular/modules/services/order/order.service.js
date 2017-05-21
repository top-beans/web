'use strict';

angular.module('orderService').

service('orderService', ['$http', function ($http) {
    var self = this;
    
    self.addAnonymousOrder = function (order, callback) {
        $http.patch("/api/OrdersApi/addanonymousorder", order).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.getCustomerAddresses = function (cookieKey, callback) {
        $http.post("/api/OrdersApi/getcustomeraddresses", cookieKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.takePayment = function (cookieKey, token, callback) {
        var paymentDetails = {
            cookiekey: cookieKey,
            token: token
        };
        
        $http.post("/api/OrdersApi/takepayment", paymentDetails).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
}]);