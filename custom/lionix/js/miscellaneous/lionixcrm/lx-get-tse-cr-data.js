// function definitions section
lx.lionixCRM.getTSECRData = async function (searchObject) {
    let lxajax_method = "getTSEData";
    let data = {
        method: lxajax_method,
    };
    for (const databaseField in searchObject) {
        if (searchObject.hasOwnProperty(databaseField)) {
            if (databaseField != "fieldname") {
                data[databaseField] = searchObject[databaseField];
            }
        }
    }
    let response = await fetch("lxajax.php", {
        method: "POST",
        body: new URLSearchParams(data),
        headers: new Headers({
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
        }),
    });
    data = await response.json().catch((error) => {
        console.error("Function lx.lionixCRM.getTSECRData error:", error);
        return error;
    });
    if (data.length == 0) {
        console.warn("TSE Costa Rica entry NOT FOUND:", searchObject.cedula_c);
        data = [
            {
                cedula_c: searchObject.cedula_c,
                first_name: "",
                last_name: "",
                lastname2_c: "",
                found: false,
            },
        ];
        toastr["warning"]("Cédula no registrada.", "Búsqueda por cédula...", {
            positionClass: "toast-bottom-center",
            showDuration: "0",
            hideDuration: "0",
            timeOut: "4000",
            extendedTimeOut: "0",
            progressBar: true,
            onShown: function () {
                console.warn(`Focus on ${searchObject.focusfieldname}`);
                $("#" + searchObject.focusfieldname).focus();
            },
        });
    } else {
        console.warn("TSE Costa Rica entry found:", data.length, data);
    }
    return {
        focusfieldname: searchObject.focusfieldname,
        data: data,
    };
};
