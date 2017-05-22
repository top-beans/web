'use strict';

namespace('models').address = function (data) {
    var self = this;
    self.addresskey = !data ? null : data.addresskey;
    self.firstname = !data ? null : data.firstname;
    self.lastname = !data ? null : data.lastname;
    self.address1 = !data ? null : data.address1;
    self.address2 = !data ? null : data.address2;
    self.address3 = !data ? null : data.address3;
    self.postcode = !data ? null : data.postcode;
    self.city = !data ? null : data.city;
    self.state = !data ? null : data.state;
    self.email = !data ? null : data.email;
    self.phone = !data ? null : data.phone;
    self.countrykey = !data || !data.countrykey ? models.countries.UK : data.countrykey.toString();
};