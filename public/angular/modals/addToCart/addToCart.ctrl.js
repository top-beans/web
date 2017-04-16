'use strict';

namespace('modals').addToCartCtrl = function ($uibModalInstance, cartService, coffee, cookiekey) {
    var self = this;

    self.$onInit = function () {
        self.coffee = coffee;
        self.shoppingCart = new models.cart({
            coffeekey: self.coffee.coffeekey,
            cookiekey: cookiekey,
            requesttypekey: models.requestTypes.sample,
            quantity: 1
        });
    };

    self.validate = function () {
        var errors = [];

        if (self.shoppingCart.requesttypekey === models.requestTypes.purchase && (!isValidInt(self.shoppingCart.quantity) || self.shoppingCart.quantity <= 0)) {
            errors.push("A valid quantity greater than zero is required");
        }

        if (errors.length > 0) {
            toastrErrorFromList(errors, "Validation Input Errors");
        }

        return errors.length === 0;
    };

    self.addToCart = function () {
        if (!self.validate()) {
            return;
        } else {
            cartService.addToCart(self.shoppingCart, function (savedCart) {
                $uibModalInstance.close(savedCart);
            });
        }
    };

    self.closeModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
};
