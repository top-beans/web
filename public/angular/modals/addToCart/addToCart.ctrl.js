'use strict';

namespace('modals').addToCartCtrl = function ($uibModalInstance, cartService, cookieService, coffee) {
    var self = this;

    self.$onInit = function () {
        self.coffee = coffee;
        cookieService.get(function (cookieKey) {
            self.shoppingCart = new models.cart({
                coffeekey: self.coffee.coffeekey,
                cookiekey: cookieKey,
                requesttypekey: models.requestTypes.sample,
                quantity: 1
            });
        });
    };

    self.validate = function () {
        var errors = [];

        if (!isValidInt(self.shoppingCart.quantity) || self.shoppingCart.quantity <= 0) {
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
        }
        
        showOverlay('Adding to cart ...');
        
        cartService.addToCart(self.shoppingCart, function (data) {
            hideOverlay();
            if (!data.success) {
                toastrErrorFromList(data.errors);
            } else {
                $uibModalInstance.close(data);
            }
        });
    };

    self.closeModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
};
