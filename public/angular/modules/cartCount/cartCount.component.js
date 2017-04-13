'use strict';

angular.module('cartCount')

.component('cartCount', {
    templateUrl: '/angular/modules/cartCount/cartCount.template.html',
    controller: ['mycookie', 'cartService', function (mycookie, cartService) {
        var self = this;
        self.cartSize = 0;

        self.updateCartSize = function (newCartSize) {
            self.cartSize = newCartSize;
        };

        self.$onInit = function () {
            cartService.getCartSize(mycookie.get(), self.updateCartSize);
            cartService.subscribe(self.updateCartSize);
        };
    }]
});
