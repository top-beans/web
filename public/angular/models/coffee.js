'use strict';

namespace('models').coffee = function (data) {
    var self = this;
    $.extend(self, data);
    
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