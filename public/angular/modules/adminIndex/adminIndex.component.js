'use strict';

angular.module('adminIndex').

component('adminIndex', {
    templateUrl: '/angular/modules/adminIndex/adminIndex.template.html',
    controller: ['$http', 'md5', function ($http, md5) {
        var self = this;

        self.credentials = {
            username: null,
            password: null
        };

        self.login = function () {

            if (!self.credentials.username || !self.credentials.password) {
                toastrError("Username and Password are both required");
                return;
            }

            var obj = $.extend({}, self.credentials, {
                password: md5.createHash(self.credentials.password)
            });

            $http.post("/api/SecurityApi/login", obj).then(function (response) {
                if (!response.data.success) {
                    toastrErrorFromList(response.data.errors, "Login Failed");
                } else {
                    location.href = "/Admin/useradmin";
                }
            });
        };
    }]
});
