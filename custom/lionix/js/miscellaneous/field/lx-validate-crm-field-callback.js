lx.field.validateCallback = function (
    form_name,
    field_name,
    label,
    callback,
    lxvalidate,
    fnCallerName = ""
) {
    //validateCallback is defined somewhere on SuiteCRM by default, we only use it here
    if (typeof validate[form_name] == "undefined") {
        //addForm is defined somewhere on SuiteCRM by default, we only use it here
        addForm(form_name);
    }
    fnCallerName = fnCallerName != "" ? `(Function ${fnCallerName})` : "";
    label_field_name_without_c = field_name.replace(/_c$/, "");
    console.warn(
        `lx.field.validate adding validation on form ${form_name} to field %c${field_name} %c${fnCallerName}`,
        "color: blue",
        "color: green"
    );
    //addToValidateCallback is defined somewhere on SuiteCRM by default, we only use it here
    addToValidateCallback(
        form_name,
        field_name,
        "varchar",
        lxvalidate,
        `Formato inválido: ${label}`,
        callback
    );
    $(`.label[data-label='LBL_${field_name.toUpperCase()}']`).html(
        `${label}: <font color="red">*</font>`
    );
    $(
        `.label[data-label='LBL_${label_field_name_without_c.toUpperCase()}']`
    ).html(`${label}: <font color="red">*</font>`);
};
