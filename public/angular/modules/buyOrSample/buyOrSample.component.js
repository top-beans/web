'use strict';

angular.module('buyOrSample')

.component('buyOrSample', {
    templateUrl: '/angular/modules/buyOrSample/buyOrSample.template.html',
    controller: ['$http', '$uibModal', function ($http, $uibModal) {
        var self = this;

        self.getCoffees = function() {
            self.loading = true;
            $http.get('/api/CoffeeApi/getallactive').then(function (response) {
                self.loading = false;
                self.coffees = _(response.data.items).sortBy(['cuppingscore']).reverse().value();
            });
        };
        
        self.fullStars = function (coffee) {
            var score = coffee.cuppingscore < 0 ? 0 : (coffee.cuppingscore > 100 ? 100 : coffee.cuppingscore);
            return _.range(Math.floor(score / 10));
        };
        
        self.halfStar = function (coffee) {
            var score = coffee.cuppingscore < 0 ? 0 : (coffee.cuppingscore > 100 ? 100 : coffee.cuppingscore);
            return (score % 10) !== 0;
        };
        
        self.borderStars = function (coffee) {
            var score = coffee.cuppingscore < 0 ? 0 : (coffee.cuppingscore > 100 ? 100 : coffee.cuppingscore);
            return _.range(10 - Math.ceil(score / 10));
        };
        
        self.$onInit = function () {
            self.coffees = [];
            self.loading = false;
            self.getCoffees();
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
    }]
});
