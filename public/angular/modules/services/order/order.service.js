'use strict';

angular.module('orderService').

service('orderService', ['$http', 'cookieService', function ($http, cookieService) {
    var self = this;

    self.totalSubscriptions = [];

    self.notifyTotalSubscriptions = function (data) {
        _.forEach(self.totalSubscriptions, function (callback) { callback(data); });
    };
    
    self.getOrderTotal = function (groupKey, callback, subscribe) {
        $http.post("/api/OrdersApi/getordertotal", groupKey).then(function (response) {
            if (callback) callback(response.data);
            if (subscribe) self.totalSubscriptions.push(callback);
        }, function (error) {

        });
    };
    
    self.searchOrderHeaders = function (searchParams, callback) {
        $http.post("/api/OrdersApi/searchorderheaders", searchParams).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.getOrder = function (groupKey, callback) {
        $http.post("/api/OrdersApi/getorder", groupKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.getOrderHeader = function (groupKey, callback) {
        $http.post("/api/OrdersApi/getorderheader", groupKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.cancelOrderItem = function (groupKey, coffeeKey, callback) {
        var orderItem = {
            groupkey: groupKey,
            coffeekey: coffeeKey
        };

        $http.post("/api/OrdersApi/cancelorderitem", orderItem).then(function (response) {
            if (callback) callback(response.data);
            if (response.data.success) {
                self.getOrderTotal(groupKey, self.notifyTotalSubscriptions);
            }
        }, function (error) {

        });
    };
    
    self.returnOrderItem = function (groupKey, coffeeKey, callback) {
        var orderItem = {
            groupkey: groupKey,
            coffeekey: coffeeKey
        };

        $http.post("/api/OrdersApi/returnorderitem", orderItem).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {

        });
    };
    
    self.cancelOrder = function (groupKey, callback) {
        $http.post("/api/OrdersApi/cancelorder", groupKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.updateCustomerAddresses = function (addresses, callback) {
        $http.post("/api/OrdersApi/updatecustomeraddresses", addresses).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.dispatchOrder = function (groupKey, callback) {
        $http.post("/api/OrdersApi/dispatchorder", groupKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.returnOrder = function (groupKey, callback) {
        $http.post("/api/OrdersApi/returnorder", groupKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
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