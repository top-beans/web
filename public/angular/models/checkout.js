'use strict';

namespace('models').checkout = function (data) {
    var self = this;
    self.cookie = !data ? null : data.cookie;
    self.user = new models.user(!data ? null : data.user);
    self.deliveryaddress = new models.address(!data ? null : data.deliveryaddress);
    self.billingaddress = new models.address(!data ? null : data.billingaddress);
};