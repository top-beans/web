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
        var isPurchase = self.shoppingCart.requesttypekey === models.requestTypes.purchase;

        if (!isValidInt(self.shoppingCart.quantity) || self.shoppingCart.quantity <= 0) {
            errors.push("A valid quantity greater than zero is required");
        } else if (self.shoppingCart.quantity > self.coffee.availableamount * (isPurchase ? 1 : self.coffee.baseunitsperpackage)) {
            errors.push("Your quantity exceeds the available amount of " + self.coffee.availableamount + " x " + self.coffee.packagingunit);
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
            cartService.addToCart(self.shoppingCart, function (data) {
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    $uibModalInstance.close(data);
                }
            });
        }
    };

    self.closeModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
};
