'use strict';

angular.module('contactForm').

component('contactForm', {
    templateUrl: '/angular/modules/contactForm/contactForm.template.html',
    controller: ['$http', function ($http) {
        var self = this;

        self.form = {
            name: null,
            description: null,
            email: null,
            number: null
        };

        self.validate = function () {
            var errors = [];
            
            if (!self.form.name) {
                errors.push("Name is required");
            }
            
            if (!self.form.description) {
                errors.push("Description is required");
            }
            
            if (!isValidEmail(self.form.email) && !self.form.number) {
                errors.push("Valid email or contact number is required");
            }

            if (errors.length > 0) {
                toastrErrorFromList(errors, "Validation Errors");
            }
            
            return errors.length === 0;
        };

        self.save = function () {

            if (!self.validate()) {
                return;
            }
            
            $http.post("/api/EnquiryApi/add", self.form).then(function (response) {
                if (!response.data.success) {
                    toastrErrorFromList(response.data.errors, "Error Sending Enquiry");
                } else {
                    toastrSuccess("Message sent successfully");
                }
            });
        };
    }]
});
