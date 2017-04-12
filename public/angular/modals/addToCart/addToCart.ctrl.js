'use strict';

namespace('modals').addToCartCtrl = function ($uibModalInstance, cart, coffeekey, cookiekey) {
    var self = this;

    self.$onInit = function () {
        self.shoppingCart = new models.cart({
            coffeekey: coffeekey,
            cookiekey: cookiekey,
            requesttypekey: models.requestTypes.sample,
            quantity: 1
        });
    };

    self.validate = function () {
        var errors = [];

        if (!self.shoppingCart.requesttypekey === models.requestTypes.purchase && (!isValidInt(self.shoppingCart.quantity) || self.shoppingCart.quantity <= 0)) {
            errors.push("quantity is required");
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
            cart.addToCart(self.shoppingCart, function (savedCart) {
                $uibModalInstance.close(savedCart);
            });
        }
    };

    self.closeModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
};
