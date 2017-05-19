'use strict';

angular.module('payment')

.component('payment', {
    templateUrl: '/angular/modules/payment/payment.template.html',
    controller: ['cartService', 'cookieService', 'orderService', function (cartService, cookieService, orderService) {
        var self = this;

        self.$onInit = function () {
            self.cartBreakDown = null;
            self.getCartBreakDown();
        };
        
        self.getCartBreakDown = function() {
            cartService.getCartBreakDown(cookieService.get(), function (data) {
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    self.cartBreakDown = data.item;
                }
            });
        };
    }]
});
