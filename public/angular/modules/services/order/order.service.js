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
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.searchOrderHeaders = function (searchParams, callback) {
        $http.post("/api/OrdersApi/searchorderheaders", searchParams).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.getOrder = function (groupKey, callback) {
        $http.post("/api/OrdersApi/getorder", groupKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.getOrderHeader = function (groupKey, callback) {
        $http.post("/api/OrdersApi/getorderheader", groupKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
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
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
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
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.requestItemRefund = function (groupKey, coffeeKey, callback) {
        var orderItem = {
            groupkey: groupKey,
            coffeekey: coffeeKey
        };

        $http.post("/api/OrdersApi/requestitemrefund", orderItem).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.dispatchItems = function (groupKey, coffeeKeys, callback) {
        var orderItems = {
            groupkey: groupKey,
            coffeekeys: coffeeKeys
        };

        $http.post("/api/OrdersApi/dispatchitems", orderItems).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.cancelOrder = function (groupKey, callback) {
        $http.post("/api/OrdersApi/cancelorder", groupKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.dispatchOrder = function (groupKey, callback) {
        $http.post("/api/OrdersApi/dispatchorder", groupKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.returnOrder = function (groupKey, callback) {
        $http.post("/api/OrdersApi/returnorder", groupKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.requestOrderRefund = function (groupKey, callback) {
        $http.post("/api/OrdersApi/requestorderrefund", groupKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.addAnonymousOrder = function (order, callback) {
        $http.patch("/api/OrdersApi/addanonymousorder", order).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            if (callback) callback({
                success: false,
                errors: ['Error ' + error.status + ': ' + error.statusText]
            });
        });
    };
    
    self.getCustomerAddresses = function (callback) {
        cookieService.get(function (cookieKey) {
            $http.post("/api/OrdersApi/getcustomeraddresses", cookieKey).then(function (response) {
                if (callback) callback(response.data);
            }, function (error) {
                if (callback) callback({
                    success: false,
                    errors: ['Error ' + error.status + ': ' + error.statusText]
                });
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
                if (callback) callback({
                    success: false,
                    errors: ['Error ' + error.status + ': ' + error.statusText]
                });
            });
        });
    };
}]);