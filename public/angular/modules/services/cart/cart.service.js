'use strict';

angular.module('cartService').

service('cartService', ['$http', 'cookieService', function ($http, cookieService) {
    var self = this;
    
    self.sizeSubscriptions = [];
    self.totalSubscriptions = [];

    self.notifySizeSubscriptions = function (data) {
        _.forEach(self.sizeSubscriptions, function (callback) { callback(data); });
    };

    self.notifyTotalSubscriptions = function (data) {
        _.forEach(self.totalSubscriptions, function (callback) { callback(data); });
    };
    
    self.getCartSize = function (callback, subscribe) {
        cookieService.get(function (cookieKey) {
            $http.post("/api/CartApi/getcartsize", cookieKey).then(function (response) {
                if (callback) callback(response.data);
                if (subscribe) self.sizeSubscriptions.push(callback);
            }, function (error) {

            });
        })
    };

    self.getCartTotal = function (callback, subscribe) {
        cookieService.get(function (cookieKey) {
            $http.post("/api/CartApi/getcarttotal", cookieKey).then(function (response) {
                if (callback) callback(response.data);
                if (subscribe) self.totalSubscriptions.push(callback);
            }, function (error) {

            });
        });
    };

    self.getCart = function (callback) {
        cookieService.get(function (cookieKey) {
            $http.post("/api/CartApi/getcart", cookieKey).then(function (response) {
                if (callback) callback(response.data);
            }, function (error) {

            });
        });
    };

    self.getCartBreakDown = function (callback) {
        cookieService.get(function (cookieKey) {
            $http.post("/api/CartApi/getcartbreakdown", cookieKey).then(function (response) {
                if (callback) callback(response.data);
            }, function (error) {

            });
        });
    };
    
    self.addToCart = function (cart, callback) {
        $http.patch("/api/CartApi/patch", cart).then(function (response) {
            if (callback) callback(response.data);
            if (response.data.success) {
               self.getCartSize(self.notifySizeSubscriptions);
               self.getCartTotal(self.notifyTotalSubscriptions);
            }
        }, function (error) {
            
        });
    };
    
    self.deleteFromCart = function (coffeeKey, callback) {
        cookieService.get(function (cookieKey) {
            var cartItem = {
                cookiekey: cookieKey,
                coffeekey: coffeeKey
            };

            $http.post("/api/CartApi/delete", cartItem).then(function (response) {
                if (callback) callback(response.data);
                if (response.data.success) {
                    self.getCartSize(self.notifySizeSubscriptions);
                    self.getCartTotal(self.notifyTotalSubscriptions);
                }
            }, function (error) {

            });
        });
    };
    
    self.incrementCartItem = function (coffeeKey, callback) {
        cookieService.get(function (cookieKey) {
            var cartItem = {
                cookiekey: cookieKey,
                coffeekey: coffeeKey
            };

            $http.put("/api/CartApi/increment", cartItem).then(function (response) {
                if (callback) callback(response.data);
                if (response.data.success) {
                    self.getCartTotal(self.notifyTotalSubscriptions);
                }
            }, function (error) {

            });
        });
    };
    
    self.decrementCartItem = function (coffeeKey, callback) {
        cookieService.get(function (cookieKey) {
            var cartItem = {
                cookiekey: cookieKey,
                coffeekey: coffeeKey
            };

            $http.put("/api/CartApi/decrement", cartItem).then(function (response) {
                if (callback) callback(response.data);
                if (response.data.success) {
                    self.getCartTotal(self.notifyTotalSubscriptions);
                }
            }, function (error) {

            });
        });
    };
}]);