'use strict';

angular.module('orderService').

service('orderService', ['$http', function ($http) {
    var self = this;
    
    self.addAnonymousOrder = function (order, callback) {
        $http.patch("/api/OrdersApi/addanonymousorder", order).then(function (response) {
            if (callback) callback(response.data);
        });
    };
    
    self.getCustomerAddresses = function (cookieKey, callback) {
        $http.get("/api/OrdersApi/getcustomeraddresses/" + cookieKey).then(function (response) {
            if (callback) callback(response.data);
        });
    };
    
    self.takePayment = function (details, callback) {
        $http.post("/api/OrdersApi/takepayment", details).then(function (response) {
            if (callback) callback(response.data);
        });
    };
}]);