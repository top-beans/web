"use strict";

$.LoadingOverlaySetup({
    color: "rgba(0, 0, 0, 0.6)",
    image: ""
});

function showOverlay(inputMsg) {
    toastr.clear();
    var msg = inputMsg || 'Please Wait ...';
    
    $.LoadingOverlay("show", {
        custom: "<h5 class='loading-circle'><span class='fa fa-spinner fa-spin'></span> " + msg + "</h5>"
    });
}

function hideOverlay() {
    $.LoadingOverlay("hide");
}
