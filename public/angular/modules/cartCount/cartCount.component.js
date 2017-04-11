'use strict';

angular.module('cartCount')

.component('cartCount', {
    templateUrl: '/angular/modules/cartCount/cartCount.template.html',
    controller: ['mycookie', 'cart', function (mycookie, cart) {
        var self = this;
        self.cartSize = 0;

        self.updateCartSize = function (newCartSize) {
            self.cartSize = newCartSize;
        };

        self.$onInit = function () {
            cart.getCartSize(mycookie.get(), self.updateCartSize);
            cart.subscribe(self.updateCartSize);
        };
    }]
});
