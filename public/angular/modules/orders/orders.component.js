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
                    { status: null }
                ],
                orderby: [
                    { createddate: 'DESC' }
                ]
            };
            
            self.filter();
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
        
        self.refundOrder = function (groupKey) {
            
        };
        
        self.cancelOrder = function (groupKey) {
            
        };
        
        self.addOrEditOrder = function (groupKey) {
            $uibModal.open({
                backdrop: 'static',
                templateUrl: '/angular/modals/order/order.template.html',
                controller: ['$uibModalInstance', 'orderService', 'groupKey', modals.orderCtrl],
                controllerAs: "$mctrl",
                openedClass: 'page modal-open',
                resolve: {
                    coffee: groupKey
                }
            }).result.then(function (newOrder) {
                if (!newOrder) {
                    return;
                } else if (!groupKey) {
                    self.orderHeaders.splice(0, 0, newOrder);
                    toastrSuccess("Added Order Successfully");
                } else {
                    var index = _.findIndex(self.orderHeaders, function (o) { return o.groupkey === groupKey; });
                    self.orderHeaders.splice(index, 1, newOrder);
                    toastrSuccess("Updated Order Successfully");
                }
            }, function () { });
        };
    }]
});
