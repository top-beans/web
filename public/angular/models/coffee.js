'use strict';

namespace('models').coffee = function (data) {
    var self = this;
    self.coffeekey = !data ? null : data.coffeekey;
    self.coffeecode = !data ? null : data.coffeecode;
    self.packaging = !data ? null : data.packaging;
    self.availability = !data ? null : data.availability;
    self.warehouse = !data ? null : data.warehouse;
    self.screensize = !data ? null : data.screensize;
    self.availableamount = !data ? null : data.availableamount;
    self.remainingamount = !data ? null : data.remainingamount;
    self.cropyear = !data ? null : data.cropyear;
    self.cuppingscore = !data ? null : data.cuppingscore;
    self.currency = '£';
    self.price = !data ? null : data.price;
    self.name = !data ? null : data.name;
    self.processingmethod = !data ? null : data.processingmethod;
    self.country = !data ? null : data.country;
    self.region = !data ? null : data.region;
    self.producer = !data ? null : data.producer;
    self.producerstory = !data ? null : data.producerstory;
    self.pricebaseunit = !data ? null : data.pricebaseunit;
    self.packagingunit = !data ? null : data.packagingunit;
    self.baseunitsperpackage = !data ? null : data.baseunitsperpackage;
    self.maxfreesamplequantity = !data ? null : data.maxfreesamplequantity;
    self.sensorialdescriptors = !data ? null : data.sensorialdescriptors;
    self.cultivars = !data ? null : data.cultivars;
    self.active = !data ? true : data.active;
    
    self.fullStars = function () {
        var score = self.cuppingscore < 0 ? 0 : (self.cuppingscore > 100 ? 100 : self.cuppingscore);
        return _.range(Math.floor(score / 10));
    };

    self.halfStar = function () {
        var score = self.cuppingscore < 0 ? 0 : (self.cuppingscore > 100 ? 100 : self.cuppingscore);
        return (score % 10) !== 0;
    };

    self.borderStars = function () {
        var score = self.cuppingscore < 0 ? 0 : (self.cuppingscore > 100 ? 100 : self.cuppingscore);
        return _.range(10 - Math.ceil(score / 10));
    };
};