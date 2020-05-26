// function definitions section
lx.lionixCRM.getConfigOption = async function (option) {
    let lxajax_method = "getLionixCRMConfigOption";
    let data = {
        method: lxajax_method,
        option: option,
    };
    let response = await fetch("lxajax.php", {
        method: "POST",
        body: new URLSearchParams(data),
        headers: new Headers({
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
        }),
    });
    data = await response.json().catch((error) => {
        console.error("Function lx.lionixCRM.getConfigOption error:", error);
        return error;
    });
    if (data == "") {
        console.warn(`LionixCRM option (${option}) not found.`);
    } else {
        if (option == "all") {
            lx.lionixCRM.config = data;
        } else {
            lx.lionixCRM.config[option] = data;
        }
        console.warn(`LionixCRM option (${option}) updated.`);
    }
};
