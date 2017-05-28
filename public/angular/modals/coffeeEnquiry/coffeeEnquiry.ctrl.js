'use strict';

namespace('modals').coffeeEnquiryCtrl = function ($uibModalInstance, $http, coffee) {
    var self = this;
    
    self.$onInit = function () {
        self.coffee = coffee;
        self.form = new models.enquiry();
    };

    self.save = function () {
        var form = $('form[name=contactForm]');

        form.addClass('my-submitted');

        if ($(form).hasClass('ng-invalid-required') || $(form).hasClass('ng-invalid-pattern')) {
            toastrError('Please review form', 'Invalid Details');
            return false;
        }

        var data = {
            enquiry: self.form,
            coffeekey: self.coffee.coffeekey
        };

        showOverlay('Sending request ...');
        
        $http.post("/api/EnquiryApi/addcoffeeenquiry", data).then(function (response) {
            hideOverlay();
            if (!response.data.success) {
                toastrErrorFromList(response.data.errors, "Error Sending Enquiry");
            } else {
                $uibModalInstance.close();
            }
        });
    };

    self.closeModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
};
