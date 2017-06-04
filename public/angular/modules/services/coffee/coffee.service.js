'use strict';

angular.module('coffeeService').

service('coffeeService', ['$http', function ($http) {
    var self = this;
    
    self.getCoffees = function (callback) {
        $http.get('/api/CoffeeApi/getall').then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.getActiveCoffees = function(callback) {
        $http.get('/api/CoffeeApi/getallactive').then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.addOrUpdateCoffee = function (coffee, callback) {
        $http.patch("/api/CoffeeApi/patch", coffee).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {
            
        });
    };
    
    self.toggleActive = function (coffeeKey, callback) {
        $http.post("/api/CoffeeApi/toggleactive", coffeeKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {

        });
    };
    
    
    self.incrementCoffee = function (coffeeKey, callback) {
        $http.put("/api/CoffeeApi/increment", coffeeKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {

        });
    };
    
    self.decrementCoffee = function (coffeeKey, callback) {
        $http.put("/api/CoffeeApi/decrement", coffeeKey).then(function (response) {
            if (callback) callback(response.data);
        }, function (error) {

        });
    };
}]);