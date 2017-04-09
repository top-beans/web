'use strict';

namespace('models').cart = function (data) {
    var self = this;
    self.shoppingcartkey = !data ? null : data.shoppingcartkey;
    self.cookiekey = !data ? null : data.cookiekey;
    self.coffeekey = !data ? null : data.coffeekey;
    self.quantity = !data ? null : data.quantity;
    self.requesttypekey = !data ? null : data.requesttypekey;
    self.createddate = !data ? null : data.createddate;
};