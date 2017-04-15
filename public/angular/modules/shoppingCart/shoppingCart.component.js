'use strict';

angular.module('shoppingCart')

.component('shoppingCart', {
    templateUrl: '/angular/modules/shoppingCart/shoppingCart.template.html',
    bindings: {
        coffeeShopUrl: '@'
    },
    controller: ['$http', '$uibModal', 'cookieService', 'cartService', function ($http, $uibModal, cookieService, cartService) {
        var self = this;

        self.cartItems = [];
        
        self.getCart = function() {
            cartService.getCart(cookieService.get(), function (cartItems) {
                self.cartItems = _(cartItems).sortBy(['createddate']).reverse().value();
            });
        };
        
        self.fullStars = function (cartItem) {
            var score = cartItem.cuppingscore < 0 ? 0 : (cartItem.cuppingscore > 100 ? 100 : cartItem.cuppingscore);
            return _.range(Math.floor(score / 10));
        };
        
        self.halfStar = function (cartItem) {
            var score = cartItem.cuppingscore < 0 ? 0 : (cartItem.cuppingscore > 100 ? 100 : cartItem.cuppingscore);
            return (score % 10) !== 0;
        };
        
        self.borderStars = function (cartItem) {
            var score = cartItem.cuppingscore < 0 ? 0 : (cartItem.cuppingscore > 100 ? 100 : cartItem.cuppingscore);
            return _.range(10 - Math.ceil(score / 10));
        };
        
        self.$onInit = function () {
            self.getCoffees();
        };

        self.updateCart = function (cartItem) {
        };

        self.deleteFromCart = function (cartItem) {
        };
    }]
});
