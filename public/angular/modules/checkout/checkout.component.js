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
            self.order = new models.checkout();
            self.billingDifferent = false;
            self.ldCountries = false;
            self.ldAddresses = false;
            self.ldCart = false;
            
            self.getCountries();
            self.getCustomerAddresses();
            self.getCart();
            
            cartService.getCartTotal(self.updateCartTotal, true);
            cookieService.get(function (cookieKey) { self.order.cookie = cookieKey; });
        };

        self.loading = function () {
            return self.ldCountries || self.ldAddresses || self.ldCart;
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
            self.ldCart = true;
            cartService.getCart(function (data) {
                self.ldCart = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    self.cartItems = _(data.items).sortBy(['createddate']).reverse().value();
                }
            });
        };
            
        self.getCountries = function() {
            self.ldCountries = true;
            $http.get('/api/CountryApi/getcountries').then(function (response) {
                self.ldCountries = false;
                if (!response.data.success) {
                    toastrErrorFromList(response.data.errors);
                } else {
                    self.countries = response.data.items;
                }
            });
        };
        
        self.getCustomerAddresses = function () {
            self.ldAddresses = true;
            orderService.getCustomerAddresses(function (data) {
                self.ldAddresses = false;
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
            
            showOverlay('Processing order ...');
            
            orderService.addAnonymousOrder(self.order, function (data) {
                hideOverlay();
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else if (!data.item.requirespayment) {
                    cookieService.remove();
                    location.href = "/Index/shoppingcart";
                } else {
                    location.href = "/Index/payment";
                }
            });
        };
    }]
});
