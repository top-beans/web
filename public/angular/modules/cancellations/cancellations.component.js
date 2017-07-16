'use strict';

angular.module('cancellations')

.component('cancellations', {
    templateUrl: '/angular/modules/cancellations/cancellations.template.html',
    bindings: {
        cancellationCode: '@'
    },
    controller: ['cancellationsService', function (cancellationsService) {
        var self = this;

        self.$onInit = function () {
            self.cancellationCodeOk = false;
            self.email = null;
            self.orderItems = [];
            self.cancellationsService = cancellationsService;
        };
        
        self.confirmCancellationCode = function () {
            var form = $('form[name=cancellationsForm]');
            form.addClass('my-submitted');
            
            if (form.hasClass('ng-invalid-required')) {
                toastrError('Please review form', 'Invalid Details');
                return false;
            }
            
            showOverlay('Confirming Code ...');
            self.cancellationsService.confirmCancellationCode(self.cancellationCode, function (data) {
                hideOverlay();
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    self.orderItems = data.items;
                }
            });
        };
        
        self.getOrder = function () {
            
            var form = $('form[name=cancellationsForm]');
            
            form.addClass('my-submitted');
            
            if (form.hasClass('ng-invalid-required') || form.hasClass('ng-invalid-email')) {
                toastrError('Please review form', 'Invalid Details');
                return false;
            }
            
            showOverlay('Retrieving Order ...');
            self.cancellationsService.getOrder(self.cancellationCode, self.email, function (data) {
                hideOverlay();
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    self.orderItems = data.items;
                }
            });
        };

        self.cancelOrderItem = function (item) {
            item.cancelling = true;
            self.cancellationsService.cancelOrderItem(self.cancellationCode, self.email, item.coffeekey, function (data) {
                item.cancelling = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    var index = _.findIndex(self.orderItems, function (o) { return o.coffeekey === item.coffeekey; });
                    self.orderItems.splice(index, 1, data.item);
                }
            });
        };

        self.cancelOrder = function () {
            showOverlay('Cancelling Order ...');
            self.cancellationsService.cancelOrder(self.cancellationCode, self.email, function (data) {
                hideOverlay();
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    self.orderItems = data.items;
                }
            });
        };
    }]
});
