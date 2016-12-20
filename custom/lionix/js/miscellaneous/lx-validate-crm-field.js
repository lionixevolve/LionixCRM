function lxValidateCRMfield(form_name, field_name, label, validate, fnCallerName = "") {
    fnCallerName = (fnCallerName != "") ? "(Function " + fnCallerName + ")" : "";
    if (validate) {
        console.log("lxValidateCRMfield adding validation on form " + form_name + " to field " + field_name, fnCallerName);
        //addToValidate is defined somewhere on SuiteCRM
        addToValidate(form_name, field_name, 'varchar', true, "Falta campo requerido: " + label);
        $('#' + field_name + '_label').html(label + ': <font color="red">*</font>');
    } else {
        console.log("lxValidateCRMfield removing validation on form " + form_name + " to field " + field_name, fnCallerName);
        //removeFromValidate is defined somewhere on suitecrm
        removeFromValidate(form_name, field_name);
        $('#' + field_name + '_label').html(label + ': ');
    }
}
