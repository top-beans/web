'use strict';

angular.module('checkout')

.component('checkout', {
    templateUrl: '/angular/modules/checkout/checkout.template.html',
    controller: ['$http', 'cartService', 'cookieService', 'orderService', function ($http, cartService, cookieService, orderService) {
        var self = this;

        self.$onInit = function () {
            self.countries = [];
            self.cartBreakDown = null;
            self.order = new models.checkout({ cookie: cookieService.get() });
            self.billingDifferent = false;
            
            self.getCountries();
            self.getCartBreakDown();
            self.getCustomerAddresses();
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
            
            if (!self.billingDifferent) {
                self.order.billingaddress = new models.address(self.order.deliveryaddress);
            }
            
            orderService.addAnonymousOrder(self.order, function (data) {
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else if (!data.item) {
                    cookieService.remove();
                    toastrSuccess("Your Order has been placed Successfully");
                } else {
                    location.href = "/Index/payment";
                }
            });
        };
    }]
});
