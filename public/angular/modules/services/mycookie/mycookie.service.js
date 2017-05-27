'use strict';

angular.module('cookieService').

service('cookieService', ['$cookies', '$http', function ($cookies, $http) {
    var self = this;
    
    self.cookiekeyId = '__hy';

    self.get = function (callback) {
        
        if ($cookies.get(self.cookiekeyId)) {
            if (callback) callback($cookies.get(self.cookiekeyId));
        } else {
            $http.get("/api/GuidApi/get").then(function (response) {
                if (!response.data.success) {
                    toastrErrorFromList(response.data.errors);
                } else if (callback) {
                    var now = new Date();
                    var exp = new Date(now.getTime() + 1 * 24 * 60 * 60 * 1000); // Days * Hrs * Mins * Secs * Millis
                    
                    $cookies.put(self.cookiekeyId, response.data.item, { expires: exp, path: '/' });
                    callback($cookies.get(self.cookiekeyId));
                }
            }, function (error) {

            });
        }
    };
    
    self.remove = function () {
        $cookies.remove(self.cookiekeyId);
    };
}]);