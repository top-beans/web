'use strict';

angular.module('cancellations')

.component('cancellations', {
    templateUrl: '/angular/modules/cancellations/cancellations.template.html',
    controller: ['cancellationsService', 'bbox', function (cancellationsService, bbox) {
        var self = this;

        self.$onInit = function () {
            self.groupKey = null;
            self.reset();
            self.cancellationsService = cancellationsService;
            self.bbox = bbox;
        };
        
        self.reset = function () {
            self.groupKeyOk = false;
            self.code = null;
            self.codeOk = false;
            self.maskedEmail = null;
            self.orderItems = [];
            self.orderTotal = 0;
        };
        
        self.confirmGroupKey = function () {
            toastr.clear();
            
            var form = $('form[name=groupKeyForm]');
            form.addClass('my-submitted');
            
            if (form.hasClass('ng-invalid-required')) {
                toastrError('Please review form', 'Invalid Details');
                return false;
            }
            
            showOverlay('Confirming Order Number ...');
            self.cancellationsService.confirmGroupKey(self.groupKey, function (data) {
                hideOverlay();
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    self.groupKeyOk = true;
                    self.maskedEmail = data.item;
                }
            });
        };
        
        self.confirmCode = function () {
            toastr.clear();
            self.codeOk = false;
            
            var form = $('form[name=codeForm]');
            form.addClass('my-submitted');
            
            if (form.hasClass('ng-invalid-required')) {
                toastrError('Please review form', 'Invalid Details');
                return false;
            }
            
            showOverlay('Confirming Code ...');
            self.cancellationsService.confirmCode(self.code, function (data) {
                hideOverlay();
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    self.codeOk = true;
                    self.orderItems = data.items;
                    self.recalculateTotal();
                }
            });
        };
        
        self.recalculateTotal = function () {
            self.cancellationsService.getOrderTotal(self.code, function (data) {
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    self.orderTotal = data.item;
                }
            });
        };

        self.cancelItems = function () {
            var coffeeKeys = _(self.orderItems).filter(function (x) {
                return x.cancel;
            }).map(function (x) {
                return x.coffeekey;
            }).value();

            if (!coffeeKeys.length) {
                toastrError('At least one item should be selected');
            } else {
                bbox.confirm(function () {
                    showOverlay('Cancelling Items ...');
                    self.cancellationsService.cancelOrderItems(self.code, coffeeKeys, function (data) {
                        hideOverlay();
                        if (!data.success) {
                            toastrErrorFromList(data.errors);
                        } else {
                            _.forEach(data.items, function (item) {
                                var index = _.findIndex(self.orderItems, function (o) { return o.coffeekey === item.coffeekey; });
                                self.orderItems.splice(index, 1, item);
                            });
                            self.recalculateTotal();
                        }
                    });
                });
            }
        };
    }]
});
