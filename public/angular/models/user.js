'use strict';

namespace('models').user = function (data) {
    var self = this;
    self.username  = !data ? null : data.username;
    self.password = !data ? null : data.password;
    self.tries = !data ? 0 : data.tries || 0;
    self.lastlogin = !data ? null : data.lastlogin;
    self.usertypekey = !data ? null : data.usertypekey;
};