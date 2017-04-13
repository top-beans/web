'use strict';

angular.module('cartCount')

.component('cartCount', {
    templateUrl: '/angular/modules/cartCount/cartCount.template.html',
    controller: ['cookieService', 'cartService', function (cookieService, cartService) {
        var self = this;
        self.cartSize = 0;

        self.updateCartSize = function (newCartSize) {
            self.cartSize = newCartSize;
        };

        self.$onInit = function () {
            cartService.getCartSize(cookieService.get(), self.updateCartSize);
            cartService.subscribe(self.updateCartSize);
        };
    }]
});
