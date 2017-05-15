'use strict';

angular.module('cartService').

service('cartService', ['$http', function ($http) {
    var self = this;
    
    self.sizeSubscriptions = [];
    self.totalSubscriptions = [];

    self.notifySizeSubscriptions = function (data) {
        _.forEach(self.sizeSubscriptions, function (callback) { callback(data); });
    };

    self.notifyTotalSubscriptions = function (data) {
        _.forEach(self.totalSubscriptions, function (callback) { callback(data); });
    };
    
    self.getCartSize = function (cookieKey, callback, subscribe) {
        $http.get("/api/CartApi/getcartsize/" + cookieKey).then(function (response) {
            if (callback) callback(response.data);
            if (subscribe) self.sizeSubscriptions.push(callback);
        }, function (error) {
            
        });
    };

    self.getCartTotal = function (cookieKey, callback, subscribe) {
        $http.get("/api/CartApi/getcarttotal/" + cookieKey).then(function (response) {
            if (callback) callback(response.data);
            if (subscribe) self.totalSubscriptions.push(callback);
        }, function (error) {
            
        });
    };

    self.getCart = function (cookieKey, callback) {
        $http.get("/api/CartApi/getcart/" + cookieKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };

    self.getCartBreakDown = function (cookieKey, callback) {
        $http.get("/api/CartApi/getcartbreakdown/" + cookieKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.addToCart = function (cart, callback) {
        $http.patch("/api/CartApi/patch", cart).then(function (response) {
            if (callback) callback(response.data);
            if (response.data.success) {
               self.getCartSize(cart.cookiekey, self.notifySizeSubscriptions);
               self.getCartTotal(cart.cookiekey, self.notifyTotalSubscriptions);
            }
        });
    };
    
    self.deleteFromCart = function (cookieKey, coffeeKey, callback) {
        $http.delete("/api/CartApi/delete/" + cookieKey + "/" + coffeeKey).then(function (response) {
            if (callback) callback(response.data);
            if (response.data.success) {
                self.getCartSize(cookieKey, self.notifySizeSubscriptions);
                self.getCartTotal(cookieKey, self.notifyTotalSubscriptions);
            }
        }, function (error) {
            
        });
    };
    
    self.incrementCartItem = function (cookieKey, coffeeKey, callback) {
        $http.delete("/api/CartApi/increment/" + cookieKey + "/" + coffeeKey).then(function (response) {
            if (callback) callback(response.data);
            if (response.data.success) {
                self.getCartTotal(cookieKey, self.notifyTotalSubscriptions);
            }
        }, function (error) {
            
        });
    };
    
    self.decrementCartItem = function (cookieKey, coffeeKey, callback) {
        $http.delete("/api/CartApi/decrement/" + cookieKey + "/" + coffeeKey).then(function (response) {
            if (callback) callback(response.data);
            if (response.data.success) {
                self.getCartTotal(cookieKey, self.notifyTotalSubscriptions);
            }
        }, function (error) {
            
        });
    };
}]);