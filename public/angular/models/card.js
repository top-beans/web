'use strict';

namespace('models').card = function () {
    var self = this;
    self.name = null;
    self.card = null;
    self.month = null;
    self.year = null;
    self.cvc = null;
    
    self.cardPattern = '\\d{16}';
    self.monthPattern = '(01|02|03|04|05|06|07|08|09|10|11|12)';
    self.yearPattern = '(' + _.join(_.range(parseInt(moment().format('YYYY')), parseInt(moment().format('YYYY')) + 100, 1), '|') + ')';
    self.cvcPattern = '\\d{3,4}';
};