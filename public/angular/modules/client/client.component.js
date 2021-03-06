'use strict';

angular.module('client')

.component('client', {
    templateUrl: '/angular/modules/client/client.template.html',
    bindings: { 
        client: '<',
        callback: '&'
    },
    controller: ['$http', function ($http) {
        var self = this;

        self.validateSave = function() {
            var errors = [];
            
            if (!self.client.name) {
                errors.push("Name is required");
            }
            
            if (self.client.incorporationdate && !moment(self.client.incorporationdate).isValid()) {
                errors.push("A valid incorporation date is required");
            }
            
            if (errors.length) {
                toastrErrorFromList(errors, "Validation Failed");
            }
            
            return errors.length === 0;
        };
        
        self.saveClient = function() {
            if (!self.validateSave()) {
                return;
            }

            if (self.client.incorporationdate) {
                self.client.incorporationdate = moment(self.client.incorporationdate).format("YYYY-MM-DD");
            }
            
            showOverlay();
            $http.post("/api/ClientsApi/addorupdateclient", self.client).then(function (response) {
                hideOverlay();
                
                if (!response.data.success) {
                    toastrErrorFromList(response.data.errors, "Error Saving Client");
                } else {
                    self.callback({ newClient: response.data.item });
                    toastrSuccess("Saved Client Successfully");
                }
            });
        };
    }]
});
