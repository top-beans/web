'use strict';

angular.module('cookieService').

service('cookieService', ['$cookies', 'uuid2', function ($cookies, uuid2) {
    var self = this;
    
    self.cookiekeyId = '__hy';

    self.get = function () {
        
        if (!$cookies.get(self.cookiekeyId)) {
            var now = new Date();
            var exp = new Date(now.getTime() + 1 * 24 * 60 * 60 * 1000); // Days * Hrs * Mins * Secs * Millis
            $cookies.put(self.cookiekeyId, uuid2.newuuid(), { expires: exp });
        }
        
        return $cookies.get(self.cookiekeyId);
    };
    
    self.remove = function () {
        $cookies.remove(self.cookiekeyId);
    };
}]);