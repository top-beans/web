'use strict';

angular.module('payment')

.component('payment', {
    templateUrl: '/angular/modules/payment/payment.template.html',
    bindings: {
        worldpayClientKey: '@'
    },
    controller: ['cartService', 'orderService', function (cartService, orderService) {
        var self = this;

        self.$onInit = function () {
            self.cartTotal = 0;
            self.card = new models.card();
            self.loadingCart = false;
            self.loadingWorldpay = false;
            self.worldpayOk = true;
            self.worldpayLoadingError = null;
            
            self.getCartTotal(function (cartTotal) {
                if (cartTotal > 0) {
                    self.initializeWorldpay();
                }
            });
        };

        self.loading = function () {
            return self.loadingCart || self.loadingWorldpay;
        };
        
        self.getCartTotal = function (callback) {
            self.loadingCart = true;
            
            cartService.getCartTotal(function (data) {
                self.loadingCart = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    self.cartTotal = data.item;
                    if (callback) { callback(self.cartTotal); }
                }
            }, false);
        };
        
        self.initializeWorldpay = function () {
            self.loadingWorldpay = true;
            
            try {
                Worldpay.useTemplateForm({
                    'clientKey':self.worldpayClientKey,
                    'saveButton':false,
                    'templateOptions': {
                        images:{enabled:false},
                        dimensions: {width: 380,height: 260}
                    },
                    'paymentSection':'paymentSection',
                    'display':'inline',
                    'callback': function(response) {
                        if (response && response.error && response.error.message || !response || !response.token) {
                            toastrError(response && response.error && response.error.message || 'Please contact us immediately', 'Payment Errors');
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
                self.loadingWorldpay = false;
            } catch(e) {
                self.worldpayLoadingError = e && e.message;
                self.loadingWorldpay = false;
                self.worldpayOk = false;
            }
        };

        self.confirm = function() {
            Worldpay.submitTemplateForm();
        };
    }]
});
