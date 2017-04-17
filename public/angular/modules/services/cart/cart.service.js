'use strict';

angular.module('cartService').

service('cartService', ['$http', function ($http) {
    var self = this;
    
    self.subscriptions = [];

    self.notify = function (newCartSize) {
        _.forEach(self.subscriptions, function (callback) { callback(newCartSize); });
    };
    
    self.subscribe = function (callback) {
        if (callback) {
            self.subscriptions.push(callback);
        }
    };

    self.getCartSize = function (cookieKey, callback) {
        $http.get("/api/CartApi/getcartsize/" + cookieKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };

    self.getCart = function (cookieKey, callback) {
        $http.get("/api/CartApi/getcart/" + cookieKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.addToCart = function (cart, callback) {
        $http.patch("/api/CartApi/patch", cart).then(function (response) {
            if (response.data.success) self.getCartSize(cart.cookiekey, self.notify);
            if (callback) callback(response.data);
        });
    };
    
    self.deleteFromCart = function (cookieKey, coffeeKey, callback) {
        $http.delete("/api/CartApi/delete/" + cookieKey + "/" + coffeeKey).then(function (response) {
            if (response.data.success) self.getCartSize(cookieKey, self.notify);
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
}]);