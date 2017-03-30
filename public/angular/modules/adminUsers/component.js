'use strict';

angular.module('adminUsers')

.component('adminUsers', {
    templateUrl: '/angular/modules/adminUsers/template.html',
    controller: ['$http', '$uibModal', 'moment', function ($http, $uibModal, moment) {
        var self = this;

        self.maxLoginTries = 3;
        self.users = [];
        self.loading = true;

        $http.get("/api/UsersApi/getall").then(function (response) {
            self.loading = false;
            if (!response.data.success) {
                toastrErrorFromList(response.data.errors);
            } else {
                self.users = response.data.items;
            }
        });

        self.addOrEditUser = function (oldUser) {
            $uibModal.open({
                backdrop: 'static',
                templateUrl: '/angular/modals/user/user.template.html',
                controller: ['$uibModalInstance', '$http', 'md5', modals.userCtrl],
                controllerAs: "$mctrl",
                openedClass: 'page modal-open',
                resolve: {
                    user: oldUser,
                    maxLoginTries: self.maxLoginTries,
                    isReadOnly: false,
                    isCustomerUser: false
                }
            }).result.then(function (newUser) {
                if (!newUser) {
                    return;
                } else if (!oldUser) {
                    self.users.push(newUser);
                    toastrSuccess("Added User Successfully");
                } else {
                    var index = _.indexOf(self.users, oldUser);
                    self.users.splice(index, 1, newUser);
                    toastrSuccess("Updated User Successfully");
                }
            }, function () { });
        };
    }]
});
