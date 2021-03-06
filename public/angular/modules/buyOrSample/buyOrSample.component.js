'use strict';

angular.module('buyOrSample')

.component('buyOrSample', {
    templateUrl: '/angular/modules/buyOrSample/buyOrSample.template.html',
    controller: ['$http', '$uibModal', 'coffeeService', function ($http, $uibModal, coffeeService) {
        var self = this;

        self.$onInit = function () {
            self.coffees = [];
            self.loading = false;
            self.getCoffees();
        };
        
        self.getCoffees = function() {
            self.loading = true;
            coffeeService.getActiveCoffees(function (data) {
                self.loading = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    self.coffees = _(data.items).map(function (x) { return new models.coffee(x); }).sortBy(['cuppingscore']).reverse().value();
                }
            });
        };

        self.addToCart = function (coffee) {
            $uibModal.open({
                backdrop: 'static',
                templateUrl: '/angular/modals/addToCart/addToCart.template.html',
                controller: ['$uibModalInstance', 'cartService', 'cookieService', 'coffee', modals.addToCartCtrl],
                controllerAs: "$mctrl",
                openedClass: 'page modal-open',
                resolve: {
                    coffee: coffee
                }
            }).result.then(function (data) {
                if (data.warnings.length) { 
                    toastrWarningFromList(data.warnings);
                } else {
                    toastrSuccess("Added to Cart Successfully");
                }
            }, function () {
                
            });
        };

        self.makeEnquiry = function (coffee) {
            $uibModal.open({
                backdrop: 'static',
                templateUrl: '/angular/modals/coffeeEnquiry/coffeeEnquiry.template.html',
                controller: ['$uibModalInstance', '$http', 'coffee', modals.coffeeEnquiryCtrl],
                controllerAs: "$mctrl",
                openedClass: 'page modal-open',
                resolve: {
                    coffee: coffee
                }
            }).result.then(function () {
                toastrSuccess("Message sent successfully");
            }, function () {
                
            });
        };
    }]
});
