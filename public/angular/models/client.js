'use strict';

namespace('models').client = function (data) {
    var self = this;
    self.clientkey = !data ? null : data.clientkey;
    self.clientcode = !data ? null : data.clientcode;
    self.name = !data ? null : data.name;
    self.address = !data ? null : data.address;
    self.postcode = !data ? null : data.postcode;
    self.email = !data ? null : data.email;
    self.number = !data ? null : data.number;
    self.website = !data ? null : data.website;
    self.twitter = !data ? null : data.twitter;
    self.facebook = !data ? null : data.facebook;
    self.incorporationdate = !data ? null : data.incorporationdate;
    self.turnover = !data ? null : data.turnover;
    self.profit = !data ? null : data.profit;
    self.loss = !data ? null : data.loss;
    self.netcurrentassets = !data ? null : data.netcurrentassets;
    self.shareholdersfunds = !data ? null : data.shareholdersfunds;
    self.approvedforbusiness = !data || !data.approvedforbusiness ? false : data.approvedforbusiness;
};