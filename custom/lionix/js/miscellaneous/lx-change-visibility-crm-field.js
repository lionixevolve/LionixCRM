lx.field.visible = function(field_name, display, fnCallerName = "") {
    fnCallerName = fnCallerName != ""
        ? "(Function " + fnCallerName + ")"
        : "";
    label_field_name_without_c = field_name.replace(/_c$/, '');
    if (display) {
        console.log("lx.field.visible setting css visibility to visible on field " + field_name, fnCallerName);
        $("#" + field_name).css("visibility", "visible");
        $("#" + field_name).parent("div").css("visibility", "visible");
        $(".label[data-label='LBL_" + field_name.toUpperCase() + "']").css("visibility", "visible");
        $(".label[data-label='LBL_" + label_field_name_without_c.toUpperCase() + "']").css("visibility", "visible");
    } else {
        console.log("lx.field.visible setting css visibility to hidden on field " + field_name, fnCallerName);
        $("#" + field_name).css("visibility", "hidden");
        $("#" + field_name).parent("div").css("visibility", "hidden");
        $(".label[data-label='LBL_" + field_name.toUpperCase() + "']").css("visibility", "hidden");
        $(".label[data-label='LBL_" + label_field_name_without_c.toUpperCase() + "']").css("visibility", "hidden");
    }
}
