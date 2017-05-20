'use strict';

angular.module('payment')

.component('payment', {
    templateUrl: '/angular/modules/payment/payment.template.html',
    controller: ['cartService', 'cookieService', 'orderService', function (cartService, cookieService, orderService) {
        var self = this;

        self.$onInit = function () {
            self.cartBreakDown = null;
            self.card = new models.card();
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
        
        self.confirm = function() {
            var form = $('form[name=paymentForm]');
            
            if (form.hasClass('ng-invalid-required') || form.hasClass('ng-invalid-pattern')) {
                toastrError('Please review form', 'Invalid Details');
                return false;
            }
        };
    }]
});
