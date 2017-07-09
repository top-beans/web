'use strict';

namespace('models').customerAddresses = function (order) {
    var self = this;
    
    self.deliveryaddress = new models.address(!order ? null : {
        addresskey: order.deliveryaddresskey,
        firstname: order.deliveryfirstname,
        lastname: order.deliverylastname,
        address1: order.deliveryaddress1,
        address2: order.deliveryaddress2,
        address3: order.deliveryaddress3,
        postcode: order.deliverypostcode,
        city: order.deliverycity,
        state: order.deliverystate,
        email: order.deliveryemail,
        phone: order.deliveryphone,
        countrykey: order.deliverycountrykey
    });
    
    self.billingaddress = new models.address(!order ? null : {
        addresskey: order.billingaddresskey,
        firstname: order.billingfirstname,
        lastname: order.billinglastname,
        address1: order.billingaddress1,
        address2: order.billingaddress2,
        address3: order.billingaddress3,
        postcode: order.billingpostcode,
        city: order.billingcity,
        state: order.billingstate,
        email: order.billingemail,
        phone: order.billingphone,
        countrykey: order.billingcountrykey
    });
};