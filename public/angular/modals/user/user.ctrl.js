    'use strict';

namespace('modals').userCtrl = function ($uibModalInstance, $http, md5, user, maxLoginTries, isReadOnly) {
    var self = this;

    self.user = new models.user(user);
    self.maxLoginTries = maxLoginTries;
    self.isReadOnly = isReadOnly;

    self.$onInit = function () {
        self.PasswordStore = self.user.password;
        self.user.password = null;
        self.passwordConfirm = null;
    };

    self.validate = function () {
        var errors = [];

        if (!self.user.username) {
            errors.push("username is Required");
        }

        if (self.user.password || self.passwordConfirm) {
            if (!self.user.password) {
                errors.push("password is Required");
            }

            if (self.user.password !== self.passwordConfirm) {
                errors.push("Passwords do not match");
            }
        }

        if (errors.length > 0) {
            toastrErrorFromList(errors, "Validation Input Errors");
        }

        return errors.length === 0;
    };

    self.saveUser = function () {
        if (!self.validate()) {
            return;
        }

        var obj = $.extend({}, self.user, {
            password: self.user.password ? md5.createHash(self.user.password) : self.PasswordStore
        });

        $http.patch("/api/UsersApi/merge", obj).then(function (response) {
            if (!response.data.success) {
                toastrErrorFromList(response.data.errors);
            } else {
                $uibModalInstance.close(response.data.item);
            }
        });
    };

    self.closeModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
};
