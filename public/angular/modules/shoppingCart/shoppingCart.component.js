'use strict';

angular.module('shoppingCart')

.component('shoppingCart', {
    templateUrl: '/angular/modules/shoppingCart/shoppingCart.template.html',
    controller: ['cookieService', 'cartService', function (cookieService, cartService) {
        var self = this;

        self.cartItems = [];
        self.cartTotal = 0;

        self.$onInit = function () {
            self.getCart();
            cartService.getCartTotal(cookieService.get(), self.updateCartTotal, true);
        };

        self.updateCartTotal = function (data) {
            if (!data.success) {
                toastrErrorFromList(data.errors);
            } else {
                if (data.warnings.length) toastrWarningFromList(data.warnings);
                self.cartTotal = data.item;
            }
        };

        self.getCart = function() {
            cartService.getCart(cookieService.get(), function (data) {
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    self.cartItems = _(data.items).sortBy(['createddate']).reverse().value();
                }
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
        
        self.updateCart = function (cartItem) {
        };

        self.deleteFromCart = function (cartItem) {
            cartService.deleteFromCart(cookieService.get(), cartItem.coffeekey, function (data) {
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    toastrSuccess("Deleted Item Successfully");
                    self.cartItems.splice(_.findIndex(self.cartItems, cartItem.shoppingcartkey), 1);
                }
            });
        };
    }]
});
