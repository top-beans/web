'use strict';

namespace('modals').orderCtrl = function ($uibModalInstance, cart) {
    var self = this;

    self.$onInit = function () {
        self.shoppingCart = new models.cart({
            cookiekey: self.cookiekey,
            coffeekey: self.coffeekey,
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

    self.order = function () {
        if (!self.validate()) {
            return;
        } else {
            cart.addToCart(self.shoppingCart);
        }
    };

    self.closeModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
};
