"use strict";

function isValidInt(value) {
    if (!value) {
        return false;
    } else {
        var re = /^\d+?$/;
        return re.test(value);
    }
};
