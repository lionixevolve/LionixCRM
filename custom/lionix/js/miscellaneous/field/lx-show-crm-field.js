lx.field.show = function (field_name, show, fnCallerName = "") {
    fnCallerName = fnCallerName != "" ? `(Function ${fnCallerName})` : "";
    if (show) {
        console.warn(
            `lx.field.show showing field %c${field_name} %c${fnCallerName}`,
            "color: blue",
            "color: green"
        );
        $(`#${field_name}`)
            .parent("div")
            .parent("div")
            .show(1000);
    } else {
        console.warn(
            `lx.field.show hidding field %c${field_name} %c${fnCallerName}`,
            "color: blue",
            "color: green"
        );
        $(`#${field_name}`)
            .parent("div")
            .parent("div")
            .hide(250);
    }
};
