'use strict';

angular.module('shoppingCart')

.component('shoppingCart', {
    templateUrl: '/angular/modules/shoppingCart/shoppingCart.template.html',
    bindings: {
        orderComplete: '@'
    },
    controller: ['cartService', function (cartService) {
        var self = this;

        self.$onInit = function () {
            self.cartItems = [];
            self.cartTotal = 0;
            self.loadingCart = false;
            self.getCart();
            cartService.getCartTotal(self.updateCartTotal, true);
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
            self.loadingCart = true;
            cartService.getCart(function (data) {
            self.loadingCart = false;
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
            bootbox.confirm({
                title: "Confirm",
                message: "Are you sure?",
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn btn-default btn-xs'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm',
                        className: 'btn btn-primary btn-xs'
                    }
                },
                callback: function (result) {
                    if (result) {
                        cartItem.deleting = true;
                        cartService.deleteFromCart(cartItem.coffeekey, function (data) {
                            cartItem.deleting = false;
                            if (!data.success) {
                                toastrErrorFromList(data.errors);
                            } else {
                                if (data.warnings.length) toastrWarningFromList(data.warnings);
                                var index = _.findIndex(self.cartItems, function (o) { return o.shoppingcartkey === cartItem.shoppingcartkey; });
                                self.cartItems.splice(index, 1);
                                toastrSuccess("Deleted Item Successfully");
                            }
                        });
                    }
                }
            });            
        };

        self.incrementItem = function (cartItem) {
            cartItem.incrementing = true;
            cartService.incrementCartItem(cartItem.coffeekey, function (data) {
                cartItem.incrementing = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    var index = _.findIndex(self.cartItems, function (o) { return o.shoppingcartkey === cartItem.shoppingcartkey; });
                    self.cartItems.splice(index, 1, data.item);
                }
            });
        };

        self.decrementItem = function (cartItem) {
            cartItem.decrementing = true;
            cartService.decrementCartItem(cartItem.coffeekey, function (data) {
                cartItem.decrementing = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    var index = _.findIndex(self.cartItems, function (o) { return o.shoppingcartkey === cartItem.shoppingcartkey; });
                    self.cartItems.splice(index, 1, data.item);
                }
            });
        };
    }]
});
