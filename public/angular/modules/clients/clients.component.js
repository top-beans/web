'use strict';

angular.module('clients')

.component('clients', {
    templateUrl: '/angular/modules/clients/clients.template.html',
    controller: ['$http', '$scope', 'moment', function ($http, $scope, moment) {
        var self = this;

        self.clients = [];
        
        self.addClient = function() {
            self.clients.unshift(new models.client({
                clientcode: "NEW"
            }));
        };
        
        self.editClient = function(client, $event) {
            var target = $event.currentTarget;
            
            $(target).toggleClass("fa-compress");
            $(target).parent().parent().nextUntil(".summary").toggle();
            
            if (!client.callback) {
                client.callback = function (newClient) {
                    client.name = newClient.name;
                    client.clientcode = newClient.clientcode;
                    client.address = newClient.address;
                    client.postcode = newClient.postcode;
                };
            }
        };
        
        self.exportAllClients = function() {
            location.href = "/api/ClientsApi/exportalltoexcel";
        };
        
        self.exportSearchClients = function() {
            location.href = "/excelexport/ClientsApi/exporttoexcel/" + $.param(self.searchParams);
        };
        
        $scope.$watchGroup([
            function () { return self.searchParams.criteria[0].searchtext; },
            function () { return self.searchParams.criteria[1].clientcode; },
            function () { return self.searchParams.criteria[2].approvedforbusiness; }
        ], function () {
            self.filter();
        });
        
        self.searchParams = {
            page: 0,
            pagesize: 2147483647,
            criteria: [
                { searchtext: null }, 
                { clientcode: null }, 
                { approvedforbusiness: false }
            ],
            orderby: [],
            hasMoreRecords: true,
            notFetching: true
        };
        
        self.filter = function() {
            self.searchParams.hasMoreRecords = true;
            self.clients = [];
            self.appendNextPage()
        };
        
        self.appendNextPage = function() {
            if (self.searchParams.hasMoreRecords && self.searchParams.notFetching) {
                
                self.searchParams.notFetching = false;
                
                $http.post("/api/ClientsApi/searchclients", self.searchParams).then(function (response) {
                    self.searchParams.notFetching = true;
                    if (!response.data.success) {
                        toastrErrorFromList(response.data.errors, "Error Searching");
                    } else {
                        self.clients = self.clients.concat(_.map(response.data.items, function (x) { 
                            return new models.client(x);
                        }));
                        self.searchParams.hasMoreRecords = self.clients.length < response.data.total;
                        self.searchParams.page += (self.searchParams.hasMoreRecords ? 1 : 0)
                    }
                });
            }
        };
        
        self.toggleSort = function($event) {
            var target = $event.currentTarget;
            var sortColumn = $(target).attr("sortColumn");
            
            if (!sortColumn) {
                toastrError("Invalid Sort Column", "Error Sorting");
                return;
            }
            
            var column = _.find(self.searchParams.orderby, function(h) {
                return h.hasOwnProperty(sortColumn);
            });
            
            if (!column) {
                column = {};
                column[sortColumn] = $(target).hasClass("dropup") ? "ASC" : "DESC";
            } else {
                var index = _.indexOf(self.searchParams.orderby, column);
                self.searchParams.orderby.splice(index, 1);
            }
            
            if (column[sortColumn] === "DESC") {
                $(target).addClass("dropup");
                column[sortColumn] = "ASC";
            } else {
                $(target).removeClass("dropup");
                column[sortColumn] = "DESC";
            }
            
            self.searchParams.orderby.splice(0, 0, column);
            self.filter()
        }
        
        self.$onInit = function () {
            self.filter();
        };
    }]
});
