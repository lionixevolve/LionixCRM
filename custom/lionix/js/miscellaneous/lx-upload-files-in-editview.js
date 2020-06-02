// This file containts opportunities bussines logic
// function definitions section
lx.upload.getFileButton = function (element) {
    console.warn(`Rendering button for ${element.field_name} field`);
    if ($(`#${element.field_name}`).length == 0) {
        console.warn(`Field ${element.field_name} not present in EditView`);
    } else {
        data = $(`#${element.field_name}`).val();
        if (data == "") {
            data = {
                note_link: "",
                note_name: "",
            };
        } else {
            data = JSON.parse(data);
        }
        $(`#${element.field_name}`)
            .parent()
            .append(
                `<div id="${
                    element.field_name
                }-file-name"><a href="${data.note_link.replace(
                    "index.php",
                    location.origin + location.pathname
                )}">${data.note_name}</a></div></br>`
            );
        $(`#${element.field_name}`).parent().append(
            `<button id="show_${element.field_name}_loader" type="button" class="btn btn-primary btn-sm">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <i class="fa fa-file" aria-hidden="true"></i>
                <b>${element.button_label}</b>
                </button>`
        );
        $(`#${element.field_name}`)
            .parent()
            .append(
                `<div id="${element.field_name}_loader_hook" style="position: relative;"/>`
            );
        $(`#${element.field_name}`).prop("readonly", "readonly");
        $(`#${element.field_name}`).hide();
        $(`#show_${element.field_name}_loader`).click(function () {
            lx.upload.getFileTemplate(element);
            $(`#show_${element.field_name}_loader`).prop("disabled", true);
            $(`#${element.field_name}`).prop("disabled", true);
            // $(`#${element.field_name}`).toggle();  to view field
        });
        console.warn(`Field ${element.field_name} rendered.`);
    }
};

lx.upload.getFileTemplate = async function (element) {
    let lxajax_method = "uploadFileTemplate";
    data = {
        method: lxajax_method,
        field_name: element.field_name,
        label: element.button_label,
        ok_message: element.ok_message,
        module_name: element.module_name,
        record_id: element.record_id,
    };

    let response = await fetch("lxajax.php", {
        method: "POST",
        body: new URLSearchParams(data),
        headers: new Headers({
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
        }),
    });
    data = await response.text().catch((error) => {
        console.error("Function lx.upload.getFileTemplate error:", error);
    });
    $(`#floating-div-for-upload-${element.field_name}-file`).remove();
    $(`#${element.field_name}_loader_hook`).html(
        `<div id="floating-div-for-upload-${element.field_name}-file" style="display:none" />`
    );
    $replaceMe = $('<div id="lx-replace-me" />');
    $(`#floating-div-for-upload-${element.field_name}-file`)
        .html($replaceMe)
        .addClass("lx-floating-div")
        .draggable({
            cursor: "crosshair",
            cursorAt: {
                top: 56,
                left: 56,
            },
        });
    // populate dropdown with QRN
    $("#lx-replace-me").replaceWith(data);
    $(`#floating-div-for-upload-${element.field_name}-file`).show("slow");
    // buttons functionality
    $(`#exit-${element.field_name}-btn`).click(function () {
        $(`#floating-div-for-upload-${element.field_name}-file`).hide("slow");
        $(`#show_${element.field_name}_loader`).prop("disabled", false);
        $(`#${element.field_name}`).prop("disabled", false);
        // $(`#${element.field_name}`).toggle(); // to view field
    });
    $(`#upload-${element.field_name}-btn`).click(function () {
        $(`#${element.field_name}-file`).hide();
        $(`#upload-${element.field_name}-btn`).hide();
        $(`#exit-${element.field_name}-btn`).hide();
        $(`#preview_${element.field_name}`).html("");
        $(`#preview_${element.field_name}`).html(
            '<img src="custom/lionix/img/loading.gif" alt="Uploading..." style="border-radius: 50%;" />'
        );
        $(
            '<div class="lx-progress"><div class="lx-bar"></div ><div class="lx-percent">0%</div ></div>'
        ).prependTo(`#preview_${element.field_name}`);
        $(`#${element.field_name}-form`)
            .ajaxForm({
                beforeSend: function (jqXHR, settings) {
                    console.warn("ajaxForm beforeSend");
                    console.warn("beforeSend callback:", settings.url);
                    // file to be modified
                    settings.url += "&opportunity_id=" + element.opportunity_id;
                    console.warn("url modified:", settings.url);
                }, //end beforeSend
                // target: '#preview_fieldname', It's not required 'cause #preview_fieldname is been handled on success
                // Progress bar while uploading file...
                uploadProgress: function (
                    event,
                    position,
                    total,
                    percentComplete
                ) {
                    $(
                        `#floating-div-for-upload-${element.field_name}-file`
                    ).css({ height: "260px" });
                    let percentVal = `${percentComplete}%`;
                    $(".lx-bar").width(percentVal);
                    $(".lx-percent").html(percentVal);
                },
                // success is a function to be called if the request succeeds.
                success: function (data, status, jqXHR) {
                    console.warn("ajaxForm success");
                    console.warn("success callback:", status);
                    console.warn("data:", data);
                    data = JSON.parse(data);
                    $(`#preview_${element.field_name}`).text(data.message);
                    $(
                        `#floating-div-for-upload-${element.field_name}-file`
                    ).css({ height: "300px" });
                    if (data.ok) {
                        jsonToStore = {
                            note_name: data.document_name,
                            note_id: data.document_id,
                            note_link: data.document_file,
                        };
                        $(`#${element.field_name}`).val(
                            JSON.stringify(jsonToStore)
                        );
                        $(`#${element.field_name}-file-name`).html(
                            `<a href="${data.document_file.replace(
                                "index.php",
                                location.origin + location.pathname
                            )}">${data.document_name}</a>`
                        );
                        $(
                            `#floating-div-for-upload-${element.field_name}-file`
                        ).css({ width: "235px", height: "200px" });
                        $(`#${element.field_name}-title`).hide();
                        $(`#${element.field_name}-form`).hide();
                        let percentVal = "100%";
                        $(".lx-bar").width(percentVal);
                        $(".lx-percent").html(percentVal);
                        $(`#exit-${element.field_name}-btn`).show();
                    } else {
                        $(".lx-progress").hide();
                        $(`#${element.field_name}-file`).show();
                        $(`#upload-${element.field_name}-btn`).show();
                        $(`#exit-${element.field_name}-btn`).show();
                    }
                }, // success
                // error is a function to be called if the request fails.
                error: function (jqXHR, status, error) {
                    console.error("ajaxForm error");
                    console.error("error callback:", status);
                    console.error(
                        "Function lx.upload.getFileTemplate error:",
                        error
                    );
                }, // end error
                // complete is a function to be called when the request finishes (after success and error callbacks are executed).
                complete: function (jqXHR, status) {
                    console.warn("ajaxForm complete");
                    console.warn("complete callback:", status);
                    console.warn("*** finish ***");
                }, // end complete
                datatype: "text",
            })
            .submit();
    });
}; //lx.upload.getFileTemplate()

lx.upload.getFileFields = async function (forceCheck) {
    try {
        if (forceCheck) {
            lx.lionixCRM.config.modules = undefined;
            console.warn("Retrieving modules[all] properties...");
            data = await lx.lionixCRM.getConfigOption("modules");
            console.warn("Modules[all] successfully retrieved", data);
        }
        run = true;
        if (lx.events.lxUploadFilesInEditview.status == "ready") {
            run = false;
        }
        if (run) {
            if (lx.lionixCRM.config.allow_upload_files_fields) {
                let execute = false;
                let crmEditView = document.forms["EditView"];
                if (crmEditView && !!crmEditView.module) {
                    module_name = crmEditView.module.value.toLowerCase();
                    switch (module_name) {
                        case "accounts":
                        case "contacts":
                        case "opportunities":
                            text_fields_to_upload_fields_list =
                                lx.lionixCRM.config.modules[module_name]
                                    .upload_files_fields;
                            if (text_fields_to_upload_fields_list.length) {
                                execute = true;
                            } else {
                                lx.events.lxUploadFilesInEditview.status =
                                    "ready";
                                if (lx.lionixCRM.config.debuglx) {
                                    console.warn(
                                        `${module_name} module have not upload fields configured.`
                                    );
                                }
                            }
                            break;
                    }
                    if (execute) {
                        //Adding module data to each field...
                        text_fields_to_upload_fields_list.forEach(function (
                            element
                        ) {
                            element.module_name = crmEditView.module.value;
                            element.record_id = crmEditView.record.value;
                        });
                        //... rendering upload button for each field
                        text_fields_to_upload_fields_list.forEach(function (
                            element
                        ) {
                            if (
                                $(`#show_${element.field_name}_loader`)
                                    .length == 0
                            ) {
                                console.warn(
                                    `Rendering upload files fields for ${crmEditView.module.value} module...`
                                );
                                lx.upload.getFileButton(element);
                            } else {
                                if (lx.lionixCRM.config.debuglx) {
                                    console.warn(
                                        `Field ${element.field_name} was already rendered.`
                                    );
                                }
                            }
                        });
                        //All buttons rendered.
                        lx.events.lxUploadFilesInEditview.status = "ready";
                    }
                } else {
                    if (lx.lionixCRM.config.debuglx) {
                        console.warn(
                            "We are not in an EditView lx.upload.getFileFields cannot run."
                        );
                    }
                }
            } else {
                if (lx.lionixCRM.config.debuglx) {
                    console.warn(
                        "Upload files fields are disabled in LionixCRM config.php file."
                    );
                }
            }
        }
    } catch (error) {
        console.error(
            "Modules[all] properties are not present!",
            "Waiting for LionixCRM config options..."
        );
        document.addEventListener("lxLoadAllConfigOptions", () =>
            lx.upload.getFileFields(false)
        );
    }
}; // end function

//Self-Invoking Anonymous Function Notation
// !function(){}();  easy to read, the result is unimportant.
// (function(){})();  like above but more parens.
// (function(){}());  Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html and look for invoked immediately
!(function () {
    // create an observer instance
    // https://developer.mozilla.org/en/docs/Web/API/MutationObserver
    let observer = new MutationObserver(function (mutations) {
        if (mutations) {
            let crmEditView = document.forms["EditView"];
            if (crmEditView && !!crmEditView.module) {
                if (!lx.events.hasOwnProperty("lxUploadFilesInEditview")) {
                    lx.events.lxUploadFilesInEditview = { status: "send" };
                    console.warn(
                        `Loading upload files fields in ${crmEditView.module.value}...`
                    );
                }
                lx.upload.getFileFields(false);
            }
            // if needed only once, you can stop observing with observer.disconnect();
            // observer.disconnect();
        }
    });
    // Observer target
    let target = document.querySelector("body");
    if (target) {
        // configuration of the observer:
        // NOTE: At the very least, childList, attributes, or characterData must be set to true. Otherwise, "An invalid or illegal string was specified" error is thrown.
        let config = {
            attributes: true,
            childList: true,
            // characterData: true,
            subtree: true,
        };
        // pass in the target node, as well as the observer options
        observer.observe(target, config);
        lx.observers.created += 1;
        lx.observers.observing += 1;
    }
    // end observer
})();
//eof
