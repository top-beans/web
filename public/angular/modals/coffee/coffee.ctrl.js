    'use strict';

namespace('modals').coffeeCtrl = function ($uibModalInstance, coffeeService, coffee) {
    var self = this;

    self.$onInit = function () {
        self.coffee = new models.coffee(coffee);
        self.priceRegex = '(\\d+|\\d*\\.\\d{1,2})';
        self.scoreRegex = '(\\d{1,2}|\\d{1,2}\\.\\d)';
        self.posnmRegex = '\\d+';
    };

    self.saveCoffee = function () {
        var form = $('form[name=coffeeForm]');

        form.addClass('my-submitted');

        if ($(form).hasClass('ng-invalid-required') || $(form).hasClass('ng-invalid-pattern')) {
            toastrError('Please review form', 'Invalid Details');
            return false;
        }
        
        showOverlay('Saving coffee ...');
        
        coffeeService.addOrUpdateCoffee(self.coffee, function (data) {
            hideOverlay();
            if (!data.success) {
                toastrErrorFromList(data.errors);
            } else {
                $uibModalInstance.close(data.item);
            }
        });
    };

    self.closeModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
};
