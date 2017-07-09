'use strict';

angular.module('orders')

.component('orders', {
    templateUrl: '/angular/modules/orders/orders.template.html',
    controller: ['$http', '$uibModal', 'orderService', 'bbox', function ($http, $uibModal, orderService, bbox) {
        var self = this;

        self.$onInit = function () {
            self.countries = [];
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
            
            self.getCountries();
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
            
        self.getCountries = function() {
            $http.get('/api/CountryApi/getcountries').then(function (response) {
                if (!response.data.success) {
                    toastrErrorFromList(response.data.errors);
                } else {
                    self.countries = response.data.items;
                }
            });
        };
        
        self.dispatchOrder = function (orderHeader) {
            if (!orderHeader.allreceived) {
                toastrError("This operation is only valid of Received Orders");
                return;
            }
            
            bbox.confirm(function () {
                orderHeader.dispatching  = true;
                orderService.dispatchOrder(orderHeader.groupkey, function (data) {
                    orderHeader.dispatching = false;
                    if (!data.success) {
                        toastrErrorFromList(data.errors);
                    } else {
                        if (data.warnings.length) toastrWarningFromList(data.warnings);
                        var index = _.findIndex(self.orderHeaders, function (o) { return o.groupkey === orderHeader.groupkey; });
                        self.orderHeaders.splice(index, 1, data.item);
                    }
                });
            });
        };
        
        self.returnOrder = function (orderHeader) {
            if (!orderHeader.alldispatched) {
                toastrError("This operation is only valid of Dispatched Orders");
                return;
            }
            
            bbox.confirm(function () {
                orderHeader.returning  = true;
                orderService.returnOrder(orderHeader.groupkey, function (data) {
                    orderHeader.returning = false;
                    if (!data.success) {
                        toastrErrorFromList(data.errors);
                    } else {
                        if (data.warnings.length) toastrWarningFromList(data.warnings);
                        var index = _.findIndex(self.orderHeaders, function (o) { return o.groupkey === orderHeader.groupkey; });
                        self.orderHeaders.splice(index, 1, data.item);
                    }
                });
            });
        };
        
        self.cancelOrder = function (orderHeader) {
            if (!orderHeader.allreceived) {
                toastrError("This operation is only valid of Received Orders");
                return;
            }
            
            bbox.confirm(function () {
                orderHeader.cancelling  = true;
                orderService.cancelOrder(orderHeader.groupkey, function (data) {
                    orderHeader.cancelling = false;
                    if (!data.success) {
                        toastrErrorFromList(data.errors);
                    } else {
                        if (data.warnings.length) toastrWarningFromList(data.warnings);
                        var index = _.findIndex(self.orderHeaders, function (o) { return o.groupkey === orderHeader.groupkey; });
                        self.orderHeaders.splice(index, 1, data.item);
                    }
                });
            });
        };
        
        self.addOrEditOrder = function (orderHeader) {
            $uibModal.open({
                backdrop: 'static',
                templateUrl: '/angular/modals/order/order.template.html?3',
                controller: ['$uibModalInstance', 'orderService', 'bbox', 'countries', 'orderHeader', modals.orderCtrl],
                controllerAs: "$mctrl",
                openedClass: 'page modal-open',
                resolve: {
                    countries: function () { return self.countries; },
                    orderHeader: orderHeader
                }
            }).result.then(function () {
            }, function () {
                
                showOverlay('Updating Order ...');
                orderService.getOrderHeader(orderHeader.groupkey, function (data) {
                    hideOverlay();
                    if (!data.success) {
                        toastrErrorFromList(data.errors);
                    } else {
                        var index = _.findIndex(self.orderHeaders, function (o) { return o.groupkey === orderHeader.groupkey; });
                        
                        if (!data.item) {
                            self.orderHeaders.splice(index, 1);
                        } else {
                            self.orderHeaders.splice(index, 1, data.item);
                        }
                    }
                });
            });
        };
    }]
});
