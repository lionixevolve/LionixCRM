// function definitions section
lx.lionixCRM.runSpoonFile = async function (
    file,
    answer,
    previewDivId = "preview"
    //* previewDivId is a div showing a message while spoon file is being executed so it needs to be resetted after completion.
) {
    let lxajax_method = "runSpoonFile";
    let data = {
        method: lxajax_method,
        file: file,
        answer: answer,
    };
    let response = await fetch("lxajax.php", {
        method: "POST",
        body: new URLSearchParams(data),
        headers: new Headers({
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
        }),
    });
    data = await response.json().catch((error) => {
        console.error("Function lx.lionixCRM.runSpoonFile error:", error);
        $(`#${previewDivId}`).html("");
        return error;
    });
    if (data == "") {
        console.warn(`Spoon file ${file} not found.`);
    } else {
        console.warn(`Spoon file (${file}) executed.`);
        $(`#${previewDivId}`).html("");
        alert(data);
    }
    return data;
}; // end function
