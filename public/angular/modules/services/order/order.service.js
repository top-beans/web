'use strict';

angular.module('orderService').

service('orderService', ['$http', 'cookieService', function ($http, cookieService) {
    var self = this;
    
    self.addAnonymousOrder = function (order, callback) {
        $http.patch("/api/OrdersApi/addanonymousorder", order).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.getCustomerAddresses = function (callback) {
        cookieService.get(function (cookieKey) {
            $http.post("/api/OrdersApi/getcustomeraddresses", cookieKey).then(function (response) {
                if (callback) callback(response.data);
            }, function (error) {

            });
        });
    };
    
    self.takePayment = function (token, callback) {
        cookieService.get(function (cookieKey) {
            var paymentDetails = {
                cookiekey: cookieKey,
                token: token
            };

            $http.post("/api/OrdersApi/takepayment", paymentDetails).then(function (response) {
                if (callback) callback(response.data);
            }, function (error) {

            });
        });
    };
}]);