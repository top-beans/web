"use strict";

$.LoadingOverlaySetup({
    color: "rgba(255, 255, 255, 0.6)",
    image: ""
});

function showOverlay(inputMsg) {
    toastr.clear();
    var msg = inputMsg || 'Please Wait ...';
    
    $.LoadingOverlay("show", {
        custom: "<h4><span class='fa fa-spinner fa-spin'></span> " + msg + "</h4>"
    });
}

function hideOverlay() {
    $.LoadingOverlay("hide");
}
