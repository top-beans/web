'use strict';

angular.module('contactForm').

component('contactForm', {
    templateUrl: '/angular/modules/contactForm/contactForm.template.html',
    controller: ['$http', function ($http) {
        var self = this;

        self.$onInit = function () {
            self.form = new models.enquiry();
        };

        self.save = function () {
            var form = document.getElementById('contactForm');
            
            if ($(form).hasClass('ng-invalid-required') || $(form).hasClass('ng-invalid-pattern')) {
                toastrError('Please review form', 'Invalid Details');
                return false;
            }
            
            showOverlay('Sending request ...');

            $http.post("/api/EnquiryApi/add", self.form).then(function (response) {
                hideOverlay();
                if (!response.data.success) {
                    toastrErrorFromList(response.data.errors, "Error Sending Enquiry");
                } else {
                    toastrSuccess("Message sent successfully");
                }
            });
        };
    }]
});
