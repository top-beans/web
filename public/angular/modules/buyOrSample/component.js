'use strict';

angular.module('buyOrSample')

.component('buyOrSample', {
    templateUrl: '/angular/modules/buyOrSample/template.html',
    bindings: {
        coffeeShopUrl: '@'
    },
    controller: ['$http', function ($http) {
        var self = this;

        self.coffees = [];
        
        self.getCoffees = function(coffeeShopUrl) {
            $http.get(coffeeShopUrl).then(function (response) {
                self.coffees = _(response.data.data).sortBy(['cuppingScore']).reverse().value();
            });
        };
        
        self.fullStars = function (coffee) {
            var score = coffee.cuppingScore < 0 ? 0 : (coffee.cuppingScore > 100 ? 100 : coffee.cuppingScore);
            return _.range(Math.floor(score / 10));
        };
        
        self.halfStar = function (coffee) {
            var score = coffee.cuppingScore < 0 ? 0 : (coffee.cuppingScore > 100 ? 100 : coffee.cuppingScore);
            return (score % 10) !== 0;
        };
        
        self.borderStars = function (coffee) {
            var score = coffee.cuppingScore < 0 ? 0 : (coffee.cuppingScore > 100 ? 100 : coffee.cuppingScore);
            return _.range(10 - Math.ceil(score / 10));
        };
        
        self.currencyPart = function (price) {
            var m = price.match(/[\$\£]/);
            
            if (m !== null) {
                return m[0];
            } else {
                return null;
            }
        };
        
        self.pricePart = function (price) {
            var m = price.match(/[\d\.]+/);
            
            if (m !== null) {
                return m[0];
            } else {
                return null;
            }
        };
        
        self.integerPart = function (price) {
            var m = price.match(/[\d]+/);
            
            if (m !== null) {
                return m[0];
            } else {
                return null;
            }
        };
        
        self.fractionalPart = function (price) {
            var intPart = self.integerPart(price);
            var pricePart = self.pricePart(price);
            
            if (intPart === null || pricePart === null) {
                return null;
            } else {
                return pricePart.replace(intPart + '.', '');
            }
        };
        
        self.join = function(list, separator) {
            return _.join(list, separator);
        };
        
        self.$onInit = function () {
            self.getCoffees(self.coffeeShopUrl);
        };
    }]
});
