function lxChangeVisibilityCRMfield(field_name, display, fnCallerName = "") {
    fnCallerName = fnCallerName != "" ? "(Function " + fnCallerName + ")" : "";
    label_field_name_without_c = field_name.replace(/_c$/,'');
    if (display) {
        console.log("lxChangeVisibilityCRMfield showing field " + field_name, fnCallerName);
        $("#" + field_name).parent("div").css("visibility", "visible");
        $(".label[data-label='LBL_" + field_name.toUpperCase() + "']").css("visibility", "visible");
        $(".label[data-label='LBL_" + label_field_name_without_c.toUpperCase() + "']").css("visibility", "visible");
    } else {
        console.log("lxChangeVisibilityCRMfield hidding field " + field_name, fnCallerName);
        $("#" + field_name).parent("div").css("visibility", "hidden");
        $(".label[data-label='LBL_" + field_name.toUpperCase() + "']").css("visibility", "hidden");
        $(".label[data-label='LBL_" + label_field_name_without_c.toUpperCase() + "']").css("visibility", "hidden");
    }
}
