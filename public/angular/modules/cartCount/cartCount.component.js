'use strict';

angular.module('cartCount')

.component('cartCount', {
    templateUrl: '/angular/modules/cartCount/cartCount.template.html',
    controller: ['cartService', function (cartService) {
        var self = this;

        self.updateCartSize = function (data) {
            if (!data.success) {
                toastrErrorFromList(data.errors);
            } else {
                if (data.warnings.length) toastrWarningFromList(data.warnings);
                self.cartSize = data.item;
            }
        };
        
        self.$onInit = function () {
            self.cartSize = 0;
            cartService.getCartSize(self.updateCartSize, true);
        };
    }]
});
