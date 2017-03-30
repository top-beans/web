"use strict";

function isValidDecimal(value) {
    if (!value) {
        return false;
    } else {
        var re = /^\d+(\.{1}\d{1,2})?$/;
        return re.test(value);
    }
};
