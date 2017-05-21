'use strict';

angular.module('checkout')

.component('checkout', {
    templateUrl: '/angular/modules/checkout/checkout.template.html',
    controller: ['$http', 'cartService', 'cookieService', 'orderService', function ($http, cartService, cookieService, orderService) {
        var self = this;

        self.$onInit = function () {
            self.cartItems = [];
            self.cartTotal = 0;
            self.countries = [];
            self.order = new models.checkout({ cookie: cookieService.get() });
            self.billingDifferent = false;
            
            self.getCountries();
            self.getCustomerAddresses();
            self.getCart();
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

        self.getCart = function() {
            cartService.getCart(cookieService.get(), function (data) {
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    self.cartItems = _(data.items).sortBy(['createddate']).reverse().value();
                }
            });
        };
            
        self.getCountries = function() {
            $http.get('/api/CountryApi/getcountries').then(function (response) {
                if (!response.data.success) {
                    toastrErrorFromList(response.data.errors);
                } else {
                    self.countries = response.data.items;
                }
            });
        };
        
        self.getCustomerAddresses = function () {
            orderService.getCustomerAddresses(cookieService.get(), function (data) {
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    self.order.deliveryaddress = new models.address(data.item.deliveryaddress);
                    self.order.billingaddress = new models.address(data.item.billingaddress);
                    self.billingDifferent = data.item.billingDifferent;
                }
            });
        };
        
        self.proceed = function() {

            var dlForm = $('form[name=deliveryForm]');
            var blForm = $('form[name=billingForm]');
            
            dlForm.addClass('my-submitted');
            blForm.addClass('my-submitted');
            
            var dlInv = dlForm.hasClass('ng-invalid-required') || dlForm.hasClass('ng-invalid-email');
            var blInv = blForm.hasClass('ng-invalid-required') || blForm.hasClass('ng-invalid-email');
            
            if (dlInv || (self.billingDifferent && blInv)) {
                toastrError('Please review form', 'Invalid Details');
                return false;
            }
            
            if (!self.billingDifferent) {
                self.order.billingaddress = new models.address(self.order.deliveryaddress);
            }
            
            orderService.addAnonymousOrder(self.order, function (data) {
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else if (!data.item.requirespayment) {
                    cookieService.remove();
                    location.href = "/Index/index";
                } else {
                    location.href = "/Index/payment";
                }
            });
        };
    }]
});
