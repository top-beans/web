    'use strict';

namespace('modals').orderCtrl = function ($uibModalInstance, orderService, groupKey) {
    var self = this;

    self.$onInit = function () {
        self.order = null;
        self.groupKey = groupKey;
        self.getOrder();
    };

    self.getOrder = function () {
        orderService.getOrder(self.groupKey, function (data) {
            if (!data.success) {
                toastrErrorFromList(data.errors);
            } else {
                self.order = data.item;
            }
        });
    };

    self.saveOrder = function () {
        var form = $('form[name=orderForm]');

        form.addClass('my-submitted');

        if ($(form).hasClass('ng-invalid-required') || $(form).hasClass('ng-invalid-pattern')) {
            toastrError('Please review form', 'Invalid Details');
            return false;
        }
        
        showOverlay('Saving Order ...');
        
        orderService.addOrUpdateOrder(self.order, function (data) {
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
