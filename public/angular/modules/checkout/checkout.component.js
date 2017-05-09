'use strict';

angular.module('checkout')

.component('checkout', {
    templateUrl: '/angular/modules/checkout/checkout.template.html',
    controller: ['$http', 'cartService', 'cookieService', function ($http, cartService, cookieService) {
        var self = this;

        self.countries = [];
        self.checkout = null;
        
        self.getCountries = function() {
            $http.get('/api/CountryApi/getcountries').then(function (response) {
                if (!response.data.success) {
                    toastrErrorFromList(response.data.errors);
                } else {
                    self.countries = response.data.items;
                }
            });
        };
        
        self.initCheckout = function () {
            self.checkout = new models.checkout();            
            self.checkout.cookie = cookieService.get();
        };
        
        self.$onInit = function () {
            self.getCountries();
            self.initCheckout();
        };
    }]
});
