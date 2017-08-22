function lxShowCRMfield(field_name, show, fnCallerName = "") {
    fnCallerName = fnCallerName != "" ? "(Function " + fnCallerName + ")" : "";
    if (show) {
        console.log("lxShowCRMfield showing field " + field_name, fnCallerName);
        $("#" + field_name).parent('div').parent('div').show(1000);
    } else {
        console.log("lxShowCRMfield hidding field " + field_name, fnCallerName);
        $("#" + field_name).parent('div').parent('div').hide(250);
    }
}
