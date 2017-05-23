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
            self.confirming = false;
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
            
            self.confirming = true;
            
            Worldpay.useOwnForm({
                'clientKey': self.worldPayClientKey,
                'form': form,
                'reusable': false,
                'callback': function(status, response) {
                    if (status !== 200 || !response || response && response.error && response.error.message) {
                        toastrError(response && response.error && response.error.message || 'Please contact us immediately', 'Payment Errors');
                        self.confirming = false;
                    } else if (response.token) {
                        orderService.takePayment(cookieService.get(), response.token, function (data) {
                            self.confirming = false;
                            if (!data.success) {
                                toastrErrorFromList(data.errors, 'Payment Errors');
                            } else if (data.item.paymentStatus === "FAILED") {
                                toastrError(data.item.paymentStatusReason);
                            } else if (data.item.paymentStatus === "ERROR") {
                                toastrError('Please contact us immediately', 'Payment Errors');
                            } else {
                                location.href = "/Index/shoppingcart";
                            }
                        });
                    }
                }
            });            
        };
    }]
});
