'use strict';

angular.module('cartCount')

.component('cartCount', {
    templateUrl: '/angular/modules/cartCount/cartCount.template.html',
    controller: ['cookieService', 'cartService', function (cookieService, cartService) {
        var self = this;
        self.cartSize = 0;

        self.updateCartSize = function (data) {
            if (!data.success) {
                toastrErrorFromList(data.errors);
            } else {
                if (data.warnings.length) toastrWarningFromList(data.warnings);
                self.cartSize = data.item;
            }
        };

        self.$onInit = function () {
            cartService.getCartSize(cookieService.get(), self.updateCartSize);
            cartService.subscribe(self.updateCartSize);
        };
    }]
});
