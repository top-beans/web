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
            self.cartTotal = 0;
            self.card = new models.card();
            cartService.getCartTotal(cookieService.get(), self.updateCartTotal, true);
        };

        self.updateCartTotal = function (data) {
            if (!data.success) {
                toastrErrorFromList(data.errors);
            } else {
                if (data.warnings.length) toastrWarningFromList(data.warnings);
                self.cartTotal = data.item;
            }
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
                    if (!response.token) {
                        toastrError('Please contact us immediately', 'Payment Errors');
                        return;
                    }
                    
                    orderService.takePayment(cookieService.get(), response.token, function (data) {
                        if (!data.success) {
                            toastrErrorFromList(data.errors);
                        } else {
                            location.url = '/Index/index';
                        }
                    });
                }
            });            
        };
    }]
});
