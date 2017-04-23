'use strict';

angular.module('checkout')

.component('checkout', {
    templateUrl: '/angular/modules/checkout/checkout.template.html',
    controller: ['$http', 'cartService', 'cookieService', function ($http, cartService, cookieService) {
        var self = this;

        self.coffees = [];
        
        self.getCoffees = function() {
            $http.get(self.coffeeShopUrl).then(function (response) {
                self.coffees = _(response.data.items).sortBy(['cuppingscore']).reverse().value();
            });
        };
        
        self.fullStars = function (coffee) {
            var score = coffee.cuppingscore < 0 ? 0 : (coffee.cuppingscore > 100 ? 100 : coffee.cuppingscore);
            return _.range(Math.floor(score / 10));
        };
        
        self.halfStar = function (coffee) {
            var score = coffee.cuppingscore < 0 ? 0 : (coffee.cuppingscore > 100 ? 100 : coffee.cuppingscore);
            return (score % 10) !== 0;
        };
        
        self.borderStars = function (coffee) {
            var score = coffee.cuppingscore < 0 ? 0 : (coffee.cuppingscore > 100 ? 100 : coffee.cuppingscore);
            return _.range(10 - Math.ceil(score / 10));
        };
        
        self.$onInit = function () {
            self.getCoffees();
        };
        

        self.addToCart = function (coffee) {
            $uibModal.open({
                backdrop: 'static',
                templateUrl: '/angular/modals/addToCart/addToCart.template.html',
                controller: ['$uibModalInstance', 'cartService', 'coffee', 'cookiekey', modals.addToCartCtrl],
                controllerAs: "$mctrl",
                openedClass: 'page modal-open',
                resolve: {
                    coffee: coffee,
                    cookiekey: function () { return cookieService.get(); }
                }
            }).result.then(function (data) {
                if (data.warnings.length) { 
                    toastrWarningFromList(data.warnings);
                } else {
                    toastrSuccess("Added to Cart Successfully");
                }
            }, function () {
                
            });
        };
    }]
});
