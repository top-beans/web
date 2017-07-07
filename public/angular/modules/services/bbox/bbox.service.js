'use strict';

angular.module('bbox').

service('bbox', [function () {
    var self = this;

    self.confirm = function (callbackYes, callbackNo) {
        bootbox.confirm({
            title: "Confirm",
            message: "Are you sure?",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel',
                    className: 'btn btn-default btn-xs'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirm',
                    className: 'btn btn-primary btn-xs'
                }
            },
            callback: function (result) {
                if (result) {
                    callbackYes();
                } else if (callbackNo) {
                    callbackNo();
                }
            }
        });
    };
}]);