'use strict';

angular.module('payment')

.component('payment', {
    templateUrl: '/angular/modules/payment/payment.template.html',
    bindings: {
        worldPayClientKey: '@'
    },
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
            var form = document.getElementById('paymentForm');
            
            if ($(form).hasClass('ng-invalid-required') || $(form).hasClass('ng-invalid-pattern')) {
                toastrError('Please review form', 'Invalid Details');
                return false;
            }
            
            Worldpay.useOwnForm({
                'clientKey': self.worldPayClientKey,
                'form': form,
                'reusable': false,
                'callback': function(status, response) {
                    if (response.error) {
                        toastrError('Please contact us immediately', 'Payment Errors');
                    } else {
                        var token = response.token;
                    }
                }
            });            
        };
    }]
});
