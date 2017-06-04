'use strict';

angular.module('coffees')

.component('coffees', {
    templateUrl: '/angular/modules/coffees/coffees.template.html',
    controller: ['$uibModal', 'coffeeService', function ($uibModal, coffeeService) {
        var self = this;

        self.$onInit = function () {
            self.coffees = [];
            self.loading = false;
            self.getCoffees();
        };
        
        self.getCoffees = function() {
            self.loading = true;
            coffeeService.getCoffees(function (data) {
                self.loading = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    self.coffees = _(data.items).map(function (x) { return new models.coffee(x); }).sortBy(['cuppingscore']).reverse().value();
                }
            });
        };
        
        self.incrementCoffee = function (coffee) {
            coffee.incrementing = true;
            coffeeService.incrementCoffee(coffee.coffeekey, function (data) {
                coffee.incrementing = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    var index = _.findIndex(self.coffees, function (o) { return o.coffeekey === coffee.coffeekey; });
                    self.coffees.splice(index, 1, new models.coffee(data.item));
                }
            });
        };
        
        self.decrementCoffee = function (coffee) {
            coffee.decrementing = true;
            coffeeService.decrementCoffee(coffee.coffeekey, function (data) {
                coffee.decrementing = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    var index = _.findIndex(self.coffees, function (o) { return o.coffeekey === coffee.coffeekey; });
                    self.coffees.splice(index, 1, new models.coffee(data.item));
                }
            });
        };
        
        self.toggleActive = function (coffee) {
            coffee.toggling = true;
            coffeeService.toggleActive(coffee.coffeekey, function (data) {
                coffee.toggling = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    var index = _.findIndex(self.coffees, function (o) { return o.coffeekey === coffee.coffeekey; });
                    self.coffees.splice(index, 1, new models.coffee(data.item));
                }
            });
        };

        self.addOrEditCoffee = function (oldCoffee) {
            $uibModal.open({
                backdrop: 'static',
                templateUrl: '/angular/modals/coffee/coffee.template.html',
                controller: ['$uibModalInstance', 'coffeeService', 'coffee', modals.coffeeCtrl],
                controllerAs: "$mctrl",
                openedClass: 'page modal-open',
                resolve: {
                    coffee: oldCoffee
                }
            }).result.then(function (newCoffee) {
                if (!newCoffee) {
                    return;
                } else if (!oldCoffee) {
                    self.coffees.push(new models.coffee(newCoffee));
                    self.coffees = _.sortBy(self.coffees, ['cuppingscore']).reverse();
                    toastrSuccess("Added Coffee Successfully");
                } else {
                    var index = _.findIndex(self.coffees, function (o) { return o.coffeekey === oldCoffee.coffeekey; });
                    self.coffees.splice(index, 1, new models.coffee(newCoffee));
                    self.coffees = _.sortBy(self.coffees, ['cuppingscore']).reverse();
                    toastrSuccess("Updated Coffee Successfully");
                }
            }, function () { });
        };
    }]
});
