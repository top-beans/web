'use strict';

angular.module('enquiries')

.component('enquiries', {
    templateUrl: '/angular/modules/enquiries/enquiries.template.html',
    controller: ['$http', 'moment', function ($http, moment) {
        var self = this;

        self.enquiries = [];
        self.loading = false;
        self.hasMore = true;
        
        self.filter = {
            page: 0,
            pagesize: 20,
            criteria: [],
            orderby: [{ createddate: 'DESC' }]
        };

        self.fetch = function () {
            if (!self.loading && self.hasMore) {
                self.loading = true;
                $http.post("/api/EnquiryApi/search", self.filter).then(function (response) {
                    self.loading = false;
                    if (!response.data.success) {
                        toastrErrorFromList(response.data.errors);
                    } else {
                        self.enquiries = self.enquiries.concat(response.data.items);
                        self.hasMore = self.enquiries.length < response.data.total;
                        self.filter.page += (self.hasMore ? 1 : 0);
                    }
                });
            }
        };
        
        self.$onInit = function () {
            self.fetch();
        };
    }]
});
