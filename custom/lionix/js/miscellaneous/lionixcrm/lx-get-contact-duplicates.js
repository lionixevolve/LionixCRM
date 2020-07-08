lx.lionixCRM.getContactDuplicates = async function (searchObject) {
    console.warn("Starting search for contact duplicates...", searchObject);
    let lxajax_method = "getContactDuplicates";
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
        console.error("lx.lionixcrm.getContactDuplicates error:", error);
        return error;
    });
    if (data == "") {
        console.warn("Not duplicates found.");
    } else {
        console.warn("Contact duplicates found.");
    }
    return { fieldname: searchObject.fieldname, data: data };
};
