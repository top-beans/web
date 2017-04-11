'use strict';

angular.module('buyOrSample')

.component('buyOrSample', {
    templateUrl: '/angular/modules/buyOrSample/buyOrSample.template.html',
    bindings: {
        coffeeShopUrl: '@'
    },
    controller: ['$http', function ($http) {
        var self = this;

        self.coffees = [];
        
        self.getCoffees = function() {
            $http.get(self.coffeeShopUrl).then(function (response) {
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
            self.getCoffees();
        };
        

        self.addOrEditUser = function (oldUser) {
            $uibModal.open({
                backdrop: 'static',
                templateUrl: '/angular/modals/user/user.template.html',
                controller: ['$uibModalInstance', '$http', 'md5', modals.userCtrl],
                controllerAs: "$mctrl",
                openedClass: 'page modal-open',
                resolve: {
                    user: oldUser,
                    maxLoginTries: self.maxLoginTries,
                    isReadOnly: false,
                    isCustomerUser: false
                }
            }).result.then(function (newUser) {
                if (!newUser) {
                    return;
                } else if (!oldUser) {
                    self.users.push(newUser);
                    toastrSuccess("Added User Successfully");
                } else {
                    var index = _.indexOf(self.users, oldUser);
                    self.users.splice(index, 1, newUser);
                    toastrSuccess("Updated User Successfully");
                }
            }, function () { });
        };
    }]
});
