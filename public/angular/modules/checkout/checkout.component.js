'use strict';

angular.module('checkout')

.component('checkout', {
    templateUrl: '/angular/modules/checkout/checkout.template.html',
    controller: ['$http', 'cartService', 'cookieService', function ($http, cartService, cookieService) {
        var self = this;

        self.getCountries = function() {
            $http.get('/api/CountryApi/getcountries').then(function (response) {
                if (!response.data.success) {
                    toastrErrorFromList(response.data.errors);
                } else {
                    self.countries = response.data.items;
                }
            });
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
        
        self.$onInit = function () {
            self.countries = [];
            self.cartItems = [];
            self.order = new models.checkout({ cookie: cookieService.get() });
            self.billingDifferent = false;
            
            self.getCountries();
            self.getCart();
        };
    }]
});
