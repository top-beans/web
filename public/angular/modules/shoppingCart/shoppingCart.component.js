'use strict';

angular.module('shoppingCart')

.component('shoppingCart', {
    templateUrl: '/angular/modules/shoppingCart/shoppingCart.template.html',
    controller: ['cookieService', 'cartService', function (cookieService, cartService) {
        var self = this;

        self.$onInit = function () {
            self.cartItems = [];
            self.cartTotal = 0;
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
                        cartService.deleteFromCart(cookieService.get(), cartItem.coffeekey, function (data) {
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
            cartService.incrementCartItem(cookieService.get(), cartItem.coffeekey, function (data) {
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
            cartService.decrementCartItem(cookieService.get(), cartItem.coffeekey, function (data) {
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
