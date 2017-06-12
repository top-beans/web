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
            
            self.getCartTotal(function (cartTotal) {
                if (cartTotal > 0) {
                    self.initializeWorldPay();
                }
            });
        };

        self.getCartTotal = function (callback) {
            self.loading = true;
            
            cartService.getCartTotal(function (data) {
                self.loading = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    self.cartTotal = data.item;
                    if (callback) { callback(self.cartTotal); }
                }
            }, false);
        };
        
        self.initializeWorldPay = function () {
            Worldpay.useTemplateForm({
                'clientKey':self.worldPayClientKey,
                'saveButton':false,
                'templateOptions': {
                    images:{enabled:false},
                    dimensions: {width: 380,height: 260}
                },
                'paymentSection':'paymentSection',
                'display':'inline',
                'beforeSubmit': function() {
                    showOverlay('Processing payment ...');
                    return true;
                },
                'validationError': function(obj) {
                    hideOverlay();
                },                
                'callback': function(response) {
                    if (response && response.error && response.error.message || !response || !response.token) {
                        toastrError(response && response.error && response.error.message || 'Please contact us immediately', 'Payment Errors');
                        hideOverlay();
                    } else {
                        showOverlay('Processing payment ...');
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

        self.confirm = function() {
            showOverlay('Processing payment ...');
            Worldpay.submitTemplateForm();
        };
    }]
});
