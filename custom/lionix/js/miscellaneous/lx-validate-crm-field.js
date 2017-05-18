function lxValidateCRMfield(form_name, field_name, label, validate, fnCallerName = "") {
    fnCallerName = (fnCallerName != "") ? "(Function " + fnCallerName + ")" : "";
    label_field_name_without_c = field_name.replace(/_c$/,'');
    if (validate) {
        console.log("lxValidateCRMfield adding validation on form " + form_name + " to field " + field_name, fnCallerName);
        //addToValidate is defined somewhere on SuiteCRM by default, we only use it here
        addToValidate(form_name, field_name, 'varchar', true, "Falta campo requerido: " + label);
        $(".label[data-label='LBL_"+field_name.toUpperCase()+"']").html(label + ': <font color="red">*</font>');
        $(".label[data-label='LBL_" + label_field_name_without_c.toUpperCase() + "']").html(label + ': <font color="red">*</font>');
    } else {
        console.log("lxValidateCRMfield removing validation on form " + form_name + " to field " + field_name, fnCallerName);
        //removeFromValidate is defined somewhere on SuiteCRM by default, we only use it here
        removeFromValidate(form_name, field_name);
        $(".label[data-label='LBL_"+field_name.toUpperCase()+"']").html(label + ': ');
        $(".label[data-label='LBL_" + label_field_name_without_c.toUpperCase() + "']").html(label + ': ');
    }
}
