    'use strict';

namespace('modals').orderCtrl = function ($uibModalInstance, orderService, bbox, countries, orderHeader) {
    var self = this;

    self.$onInit = function () {
        self.countries = countries;
        self.addresses = new models.customerAddresses(orderHeader);
        self.allReceived = orderHeader.allreceived;
        self.groupKey = orderHeader.groupkey;
        self.billingDifferent = orderHeader.billingdifferent ? true : false;
        self.orderItems = [];
        self.orderTotal = 0;
        self.changesWereMade = false;
        self.getOrder();
    };

    self.getOrder = function () {
        orderService.getOrder(self.groupKey, function (data) {
            if (!data.success) {
                toastrErrorFromList(data.errors);
            } else {
                self.orderItems = data.items;
                self.recalculateTotal();
            }
        });
    };

    self.recalculateTotal = function () {
        orderService.getOrderTotal(self.groupKey, function (data) {
            if (!data.success) {
                toastrErrorFromList(data.errors);
            } else {
                self.orderTotal = data.item;
            }
        });
    };

    self.cancelItem = function (item) {
        if (!item.received) {
            toastrError("This operation is only valid of Received items");
            return;
        }

        bbox.confirm(function () {
            item.cancelling  = true;
            orderService.cancelOrderItem(self.groupKey, item.coffeekey, function (data) {
                item.cancelling = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    self.changesWereMade = true;
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    var index = _.findIndex(self.orderItems, function (o) { return o.coffeekey === item.coffeekey; });
                    self.orderItems.splice(index, 1, data.item);
                    self.recalculateTotal();
                }
            });
        });
    };

    self.returnItem = function (item) {
        if (!item.dispatched) {
            toastrError("This operation is only valid of Dispatched items");
            return;
        }

        bbox.confirm(function () {
            item.returning  = true;
            orderService.returnOrderItem(self.groupKey, item.coffeekey, function (data) {
                item.returning = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    self.changesWereMade = true;
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    var index = _.findIndex(self.orderItems, function (o) { return o.coffeekey === item.coffeekey; });
                    self.orderItems.splice(index, 1, data.item);
                    self.recalculateTotal();
                }
            });
        });
    };

    self.requestItemRefund = function (item) {
        if (!item.isrefundable) {
            toastrError("This operation is only valid of Cancelled or Returned items that are non-free");
            return;
        }

        bbox.confirm(function () {
            item.refunding  = true;
            orderService.requestItemRefund(self.groupKey, item.coffeekey, function (data) {
                item.refunding = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    self.changesWereMade = true;
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    var index = _.findIndex(self.orderItems, function (o) { return o.coffeekey === item.coffeekey; });
                    self.orderItems.splice(index, 1, data.item);
                    self.recalculateTotal();
                }
            });
        });
    };
    
    self.dispatchItems = function () {
        var coffeeKeys = _(self.orderItems).filter(function (x) { return x.dispatch; }).map(function (x) { return x.coffeekey; }).value();
        
        if (coffeeKeys.length > 0) {
            
            showOverlay('Dispatching Items ...');
            
            orderService.dispatchItems(self.groupKey, coffeeKeys, function (data) {
                hideOverlay();
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    self.changesWereMade = true;
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    
                    _.forEach(data.items, function (item) {
                        var index = _.findIndex(self.orderItems, function (o) { return o.coffeekey === item.coffeekey; });
                        self.orderItems.splice(index, 1, item);
                    });
                    
                    self.recalculateTotal();
                }
            });
        }
    };

    self.closeModal = function () {
        $uibModalInstance.dismiss(self.changesWereMade);
    };
};
