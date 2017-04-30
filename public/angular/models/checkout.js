'use strict';

namespace('models').checkout = function (data) {
    var self = this;
    self.cookie = !data ? null : data.cookie;
    self.user = !data ? new models.user() : data.user;
    self.deliveryaddress = !data ? new models.address() : data.deliveryaddress;
    self.billingaddress = !data ? new models.address() : data.billingaddress;
};