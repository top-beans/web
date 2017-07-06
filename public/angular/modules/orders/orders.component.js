'use strict';

angular.module('orders')

.component('orders', {
    templateUrl: '/angular/modules/orders/orders.template.html',
    controller: ['$scope', '$uibModal', 'moment', 'orderService', function ($scope, $uibModal, moment, orderService) {
        var self = this;

        self.$onInit = function () {
            self.orderHeaders = [];
            self.hasMoreRecords = true;
            self.loading = false;
            self.searchParams = {
                page: 0,
                pagesize: 10,
                criteria: [
                    { searchtext: null },
                    { statuses: [] }
                ],
                orderby: [
                    { createddate: 'DESC' }
                ]
            };
            
            self.filter();
            $('#statuses').multiselect();
        };
        
        self.filter = function() {
            self.orderHeaders = [];
            self.hasMoreRecords = true;
            self.searchParams.page = 0;
            self.searchParams.pagesize = 10;
            self.appendNextPage();
        };
        
        self.appendNextPage = function() {
            if (self.hasMoreRecords && !self.loading) {
                self.loading = true;
                orderService.searchOrderHeaders(self.searchParams, function (data) {
                    self.loading = false;
                    if (!data.success) {
                        toastrErrorFromList(data.errors, "Error Searching Orders");
                    } else {
                        self.orderHeaders = self.orderHeaders.concat(data.items);
                        self.hasMoreRecords = self.orderHeaders.length < data.total;
                        self.searchParams.page += (self.hasMoreRecords ? 1 : 0);
                    }
                });
            }
        };
        
        self.dispatchOrder = function (orderHeader) {
            orderHeader.refunding  = true;
            orderService.dispatchOrder(orderHeader.groupkey, function (data) {
                orderHeader.refunding = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    orderHeader.alldispatched = true;
                    orderHeader.allreceived = false;
                }
            });
        };
        
        self.returnOrder = function (orderHeader) {
            orderHeader.returning  = true;
            orderService.returnOrder(orderHeader.groupkey, function (data) {
                orderHeader.refunding = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    var index = _.findIndex(self.orderHeaders, function (o) { return o.groupkey === orderHeader.groupkey; });
                    self.orderHeaders.splice(index, 1, data.item);                }
            });
        };
        
        self.cancelOrder = function (orderHeader) {
            orderHeader.cancelling  = true;
            orderService.cancelOrder(orderHeader.groupkey, function (data) {
                orderHeader.cancelling = false;
                if (!data.success) {
                    toastrErrorFromList(data.errors);
                } else {
                    if (data.warnings.length) toastrWarningFromList(data.warnings);
                    orderHeader.allcancelled = true;
                    orderHeader.allreceived = false;
                }
            });
        };
        
        self.addOrEditOrder = function (orderHeader) {
            $uibModal.open({
                backdrop: 'static',
                templateUrl: '/angular/modals/order/order.template.html',
                controller: ['$uibModalInstance', 'orderService', 'orderHeader', modals.orderCtrl],
                controllerAs: "$mctrl",
                openedClass: 'page modal-open',
                resolve: {
                    orderHeader: orderHeader
                }
            }).result.then(function (newOrderHeader) {
                if (!newOrderHeader) {
                    return;
                } else if (!orderHeader) {
                    self.orderHeaders.splice(0, 0, newOrderHeader);
                    toastrSuccess("Added Order Successfully");
                } else {
                    var index = _.findIndex(self.orderHeaders, function (o) { return o.groupkey === orderHeader.groupkey; });
                    self.orderHeaders.splice(index, 1, newOrderHeader);
                    toastrSuccess("Updated Order Successfully");
                }
            }, function () { });
        };
    }]
});
