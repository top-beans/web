'use strict';

angular.module('payment')

.component('payment', {
    templateUrl: '/angular/modules/payment/payment.template.html',
    bindings: {
        worldPayClientKey: '@'
    },
    controller: ['cartService', 'orderService', function (cartService, orderService) {
        var self = this;

        self.$onInit = function () {
            self.cartTotal = 0;
            self.card = new models.card();
            self.loading = false;
            self.getCartTotal();
        };

        self.getCartTotal = function () {
            self.loading = true;
            
            cartService.getCartTotal(function (data) {
                self.loading = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    self.cartTotal = data.item;
                }
            }, false);
        };
        
        self.confirm = function() {
            var form = document.getElementById('paymentForm');
            
            if ($(form).hasClass('ng-invalid-required') || $(form).hasClass('ng-invalid-pattern')) {
                toastrError('Please review form', 'Invalid Details');
                return false;
            }
            
            showOverlay('Processing payment ...');
            
            Worldpay.useOwnForm({
                'clientKey': self.worldPayClientKey,
                'form': form,
                'reusable': false,
                'callback': function(status, response) {
                    if (status !== 200 || !response || response && response.error && response.error.message) {
                        toastrError(response && response.error && response.error.message || 'Please contact us immediately', 'Payment Errors');
                        hideOverlay();
                    } else if (response.token) {
                        orderService.takePayment(response.token, function (data) {
                            hideOverlay();
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
