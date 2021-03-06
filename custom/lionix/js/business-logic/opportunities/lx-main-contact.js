// function definitions section
lx.opportunity.maincontact_fields = [
    {
        field: "maincontactduplicateid_c",
        label: "Id contacto dulicado",
        required: false,
        hidden: true,
        notes: "This field must be always hide",
    },
    {
        field: "maincontactcedula_c",
        required: false,
        label: "Cédula nuevo contacto",
    },
    {
        field: "maincontactfirstname_c",
        required: true,
        label: "Nombre nuevo contacto",
    },
    {
        field: "maincontactlastname_c",
        required: true,
        label: "1er apellido nuevo contacto",
    },
    {
        field: "maincontactlastname2_c",
        required: false,
        label: "2do apellido nuevo contacto",
    },
    {
        field: "maincontactemailaddress_c",
        label: "Correo electrónico nuevo contacto",
        required: true,
        customValidate: true,
        customValidateCallback: function () {
            emailRegExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return emailRegExp.test($("#maincontactemailaddress_c").val());
        },
    },
    {
        field: "maincontactphonemobile_c",
        required: true,
        label: "Télefono celular nuevo contacto",
    },
    {
        field: "maincontactphonework_c",
        required: false,
        label: "Télefono trabajo nuevo contacto",
    },
    {
        field: "maincontacttitle_c",
        required: false,
        label: "Cargo nuevo contacto",
    },
];

lx.opportunity.clearMainContactCFields = function (duplicate) {
    lx.opportunity.maincontact_fields.forEach(function (element) {
        $(`#${element.field}`).val("");
        if (duplicate) {
            $(`#${element.field}`).css("border", "1px solid #a5e8d6");
        }
    });
    if (duplicate) {
        $("#account_id").val("");
        $(".lx-clear-duplicate").remove();
    }
};

lx.opportunity.getnewMainContactCFields = function () {
    let form_name = "EditView";
    if (
        $(
            "#lx_opportunity_maincontact_c_lxajaxed_getnewMainContactCFields_first_time"
        ).length == 0
    ) {
        $("#maincontact_c").append(
            '<div id="lx_opportunity_maincontact_c_lxajaxed_getnewMainContactCFields_first_time"/>'
        );
        lx.opportunity.clearMainContactCFields();
    }
    if ($("#maincontact_c").val() == "new") {
        lx.opportunity.maincontact_fields.forEach(function (element) {
            if (element.customValidate) {
                lx.field.validateCallback(
                    form_name,
                    element.field,
                    element.label,
                    element.customValidateCallback,
                    element.required
                );
            } else {
                if (element.required) {
                    lx.field.validate(
                        form_name,
                        element.field,
                        element.label,
                        true
                    );
                } else {
                    lx.field.validate(
                        form_name,
                        element.field,
                        element.label,
                        false
                    );
                }
            }
            if (element.hidden) {
                lx.field.show(element.field, false);
            } else {
                lx.field.show(element.field, true);
            }
        });
        $("#maincontactfirstname_c").on(
            "focusout.maincontactfirstname_c",
            function () {
                switch ($("#maincontactfirstname_c").val().toUpperCase()) {
                    case "NEW":
                        $("#maincontactfirstname_c").val("");
                        break;
                }
            }
        );
    } else {
        lx.opportunity.maincontact_fields.forEach(function (element) {
            lx.field.validate(form_name, element.field, element.label, false);
        });
        lx.opportunity.maincontact_fields.forEach(function (element) {
            lx.field.show(element.field, false);
        });
        $("#maincontactfirstname_c").off("focusout.maincontactfirstname_c");
    }
};

lx.opportunity.getMainContactDropdown = async function (
    opportunityId,
    currentValue,
    accountId,
    accountName
) {
    let lxajax_method = "getOpportunityMainContactList";
    if (accountName == "") {
        accountId = "";
    }
    let data = {
        method: lxajax_method,
        opportunityId: opportunityId,
        currentValue: currentValue,
        accountId: accountId,
    };
    let response = await fetch("lxajax.php", {
        method: "POST",
        body: new URLSearchParams(data),
        headers: new Headers({
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
        }),
    });
    data = await response.json().catch((error) => {
        console.error("lx.opportunity.getMainContactDropdown error:", error);
    });
    $("#maincontact_c").fillSelect(data);
    lx.opportunity.getnewMainContactCFields();
};

lx.opportunity.renderMainContactDuplicates = function (duplicates) {
    console.warn("duplicados devueltos:", duplicates);
    $(".main_contact_duplicates ul li").remove();
    if (duplicates.data.length) {
        duplicates.data.forEach(function (element) {
            primary_address = { id: "", email_address: "" };
            element.emails.forEach(function (element) {
                if (element.primary_address === "1") {
                    primary_address.id = element.id;
                    primary_address.email_address = element.email_address;
                }
            });
            duplicate_detail = `<li id="${element.id}`;
            duplicate_detail += `" data-account_id="${element.accounts[0].id}`;
            duplicate_detail += `" data-cedula_c="${element.cedula_c}`;
            duplicate_detail += `" data-first_name="${element.first_name}`;
            duplicate_detail += `" data-last_name="${element.last_name}`;
            duplicate_detail += `" data-lastname2_c="${element.lastname2_c}`;
            duplicate_detail += `" data-phone_mobile="${element.phone_mobile}`;
            duplicate_detail += `" data-phone_work="${element.phone_work}`;
            duplicate_detail += `" data-title="${element.title}`;
            duplicate_detail += `" data-primary_email_address_id="${primary_address.id}`;
            duplicate_detail += `" data-primary_email_address="${primary_address.email_address}`;
            duplicate_detail += '">';
            duplicate_detail += `${element.first_name} ${element.last_name} ${element.lastname2_c} ${element.cedula_c} `;
            element.emails.forEach(function (element) {
                duplicate_detail += element.email_address + " ";
            });
            duplicate_detail += element.phone_mobile + " ";
            duplicate_detail += element.phone_work;
            duplicate_detail += "</li>";
            $(".main_contact_duplicates ul").append(duplicate_detail);
        });
        newh = duplicates.data.length * 20 + 18; // last 18px bit is for duplicates title
        $(
            `#main_contact_duplicates_${duplicates.fieldname} .yui-ac-content`
        ).css("height", `${newh}px`);
        $(
            `#main_contact_duplicates_${duplicates.fieldname} .yui-ac-content`
        ).show(500);
        $(".main_contact_duplicates li").off(
            "mouseover.main_contact_duplicates_list"
        );
        $(".main_contact_duplicates li").on(
            "mouseover.main_contact_duplicates_list",
            function () {
                $(this).toggleClass("yui-ac-highlight");
                $(this).siblings("li").removeClass("yui-ac-highlight");
            }
        );
        $(".main_contact_duplicates li").off(
            "click.main_contact_duplicates_list"
        );
        $(".main_contact_duplicates li").on(
            "click.main_contact_duplicates_list",
            function () {
                $(".main_contact_duplicates .yui-ac-content").hide(500);

                console.warn("contact_id", $(this).prop("id"));
                console.warn(
                    "email_id",
                    $(this).data("primary_email_address_id")
                );
                $("#maincontactduplicateid_c").val($(this).prop("id"));
                $("#account_id").val($(this).data("account_id"));
                $("#maincontactcedula_c").val($(this).data("cedula_c"));
                $("#maincontactfirstname_c").val($(this).data("first_name"));
                $("#maincontactlastname_c").val($(this).data("last_name"));
                $("#maincontactlastname2_c").val($(this).data("lastname2_c"));
                $("#maincontactphonemobile_c").val(
                    $(this).data("phone_mobile")
                );
                $("#maincontactphonework_c").val($(this).data("phone_work"));
                $("#maincontactemailaddress_c").val(
                    $(this).data("primary_email_address")
                );
                $("#maincontacttitle_c").val($(this).data("title"));

                $("#maincontactcedula_c").css("border", "1px solid #0045ff");
                $("#maincontactfirstname_c").css("border", "1px solid #0045ff");
                $("#maincontactlastname_c").css("border", "1px solid #0045ff");
                $("#maincontactlastname2_c").css("border", "1px solid #0045ff");
                $("#maincontactphonemobile_c").css(
                    "border",
                    "1px solid #0045ff"
                );
                $("#maincontactphonework_c").css("border", "1px solid #0045ff");
                $("#maincontactemailaddress_c").css(
                    "border",
                    "1px solid #0045ff"
                );
                $("#maincontacttitle_c").css("border", "1px solid #0045ff");

                main_contact_clear_button =
                    '<span class="id-ff multiple"> <button type="button" style="margin: 8px" class="button lastChild lx-clear-duplicate" value="Limpiar nuevo contacto" onclick="lx.opportunity.clearMainContactCFields(true)"><img src="themes/SuiteP/images/id-ff-clear.png"></button></span>';
                $(".lx-clear-duplicate").remove();

                $("#maincontactcedula_c").after(main_contact_clear_button);
                $("#maincontactfirstname_c").after(main_contact_clear_button);
                $("#maincontactlastname_c").after(main_contact_clear_button);
                $("#maincontactlastname2_c").after(main_contact_clear_button);
                $("#maincontactphonemobile_c").after(main_contact_clear_button);
                $("#maincontactphonework_c").after(main_contact_clear_button);
                $("#maincontactemailaddress_c").after(
                    main_contact_clear_button
                );
                $("#maincontacttitle_c").after(main_contact_clear_button);
            }
        );
    }
};

lx.opportunity.resultsSearchTSECRHandler = async function (forceCheck) {
    try {
        if (forceCheck) {
            lx.lionixCRM.config.modules = undefined;
            $("#maincontactcedula_c_lxajaxed").remove();
            console.warn("Retrieving modules[opportunities] properties...");
            data = await lx.lionixCRM.getConfigOption("modules");
            console.warn("Modules[opportunities] successfully retrieved", data);
        }
        if ($("#maincontactcedula_c_lxajaxed").length == 0) {
            keyup_status = "disabled";
            $("#maincontactcedula_c").off("keyup.results_search_tsecr");
            if (
                lx.lionixCRM.config.modules.opportunities.results_search_tsecr
            ) {
                keyup_status = "enabled";
                $("#maincontactcedula_c").on(
                    "keyup.results_search_tsecr",
                    async function () {
                        if ($(this).val().length != 9) {
                            $("#maincontactfirstname_c").val("");
                            $("#maincontactlastname_c").val("");
                            $("#maincontactlastname2_c").val("");
                        } else {
                            typed_cedula = $(this).val();
                            console.warn(
                                `Last key pressed: ${String.fromCharCode(
                                    event.which
                                )} typed cédula: %c${typed_cedula}`,
                                "color: blue"
                            );
                            tsecrlist = await lx.lionixCRM.getTSECRData({
                                // Infoticos must be on same database server this SuiteCRM instance.
                                focusfieldname: this.id,
                                cedula_c: typed_cedula,
                            });
                            console.warn(
                                "After query to lx.lionixCRM.getTSECRData:",
                                tsecrlist
                            );
                            $("#maincontactcedula_c").val(
                                tsecrlist.data[0].cedula_c
                            );
                            $("#maincontactfirstname_c").val(
                                tsecrlist.data[0].first_name
                            );
                            $("#maincontactlastname_c").val(
                                tsecrlist.data[0].last_name
                            );
                            $("#maincontactlastname2_c").val(
                                tsecrlist.data[0].lastname2_c
                            );
                            $("#maincontactfirstname_c").focus();
                            $("#maincontactfirstname_c").trigger("keypress");
                        }
                    }
                );
            }
            $("#maincontactcedula_c").append(
                `<div id="maincontactcedula_c_lxajaxed" data-results_search_tsecr="${keyup_status}"/>`
            );
            console.warn(
                `keyup.results_search_tsecr on #maincontactcedula_c ${keyup_status}`
            );
        }
    } catch (error) {
        console.error(
            "Modules[opportunities] properties are not present!",
            "Waiting for LionixCRM config options..."
        );
        document.addEventListener("lxLoadAllConfigOptions", () =>
            lx.opportunity.resultsSearchTSECRHandler(false)
        );
    }
};

lx.opportunity.resultsListDuplicatesHandler = async function (forceCheck) {
    try {
        if (forceCheck) {
            lx.lionixCRM.config.modules = undefined;
            $("#main_full_name_lxajaxed").remove();
            console.warn("Retrieving modules[opportunities] properties...");
            data = await lx.lionixCRM.getConfigOption("modules");
            console.warn("Modules[opportunities] retrieved", data);
        }
        if ($("#main_full_name_lxajaxed").length == 0) {
            keyup_status = "disabled";
            $(
                "#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c, #maincontactemailaddress_c"
            ).off("keypress.results_list_duplicates");
            if (
                lx.lionixCRM.config.modules.opportunities
                    .results_list_duplicates
            ) {
                keyup_status = "enabled";
                $(
                    "#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c, #maincontactemailaddress_c"
                ).on("keypress.results_list_duplicates", async function () {
                    ffn = $("#maincontactfirstname_c");
                    fln = $("#maincontactlastname_c");
                    fln2 = $("#maincontactlastname2_c");
                    femail = $("#maincontactemailaddress_c");
                    fced = $("#maincontactcedula_c");
                    if (
                        ($(ffn).val().length > 2 &&
                            ($(fln).val().length > 2 ||
                                $(fln2).val().length > 2)) ||
                        /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
                            femail.val()
                        )
                    ) {
                        if (
                            $(`#main_contact_duplicates_${this.id}`).length == 0
                        ) {
                            // width: 750px can be changed in each Client
                            width = lx.lionixCRM.config.modules.opportunities
                                .results_list_duplicates_width
                                ? lx.lionixCRM.config.modules.opportunities
                                      .results_list_duplicates_width
                                : 750;
                            $(this).before(
                                `<div id="main_contact_duplicates_${this.id}" class="main_contact_duplicates yui-ac-container" style="position: relative; left: 200px; top:0px;">
                                    <div class="yui-ac-content" style="width: ${width}px; height: 60px; display: none; ">
                                        <div class="yui-ac-bd">Posibles duplicados encontrados<ul id="#ul_${this.id}"></ul></div>
                                    </div>
                                </div>`
                            );
                        }
                        $(
                            "#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c"
                        ).off("focusout.results_list_duplicates");
                        $(
                            "#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c"
                        ).on("focusout.results_list_duplicates", function () {
                            $(".main_contact_duplicates .yui-ac-content").hide(
                                500
                            );
                        });
                        duplicates = await lx.lionixCRM.getContactDuplicates({
                            fieldname: this.id,
                            first_name: $(ffn).val(),
                            last_name: $(fln).val(),
                            lastname2_c: $(fln2).val(),
                            // ,"cedula_c": $(fced).val(),
                            email_address: $(femail).val(),
                        });
                        lx.opportunity.renderMainContactDuplicates(duplicates);
                    }
                });
            }
            $("#maincontactcedula_c").append(
                `<div id="main_full_name_lxajaxed" data-results_list_duplicates="${keyup_status}"/>`
            );
            console.warn(`keypress.results_list_duplicates ${keyup_status}`);
        }
    } catch (error) {
        console.error(
            "Modules[opportunities] properties are not present!",
            "Waiting for LionixCRM config options..."
        );
        document.addEventListener("lxLoadAllConfigOptions", () =>
            lx.opportunity.resultsListDuplicatesHandler(false)
        );
    }
};

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
                if (crmEditView.module.value == "Opportunities") {
                    if ($("#maincontact_c").length == 1) {
                        if ($("#maincontact_c_lxajaxed").length == 0) {
                            opid = document.forms["EditView"].record.value;
                            $("#maincontact_c").append(
                                '<div id="maincontact_c_lxajaxed"/>'
                            );
                            lx.opportunity.getMainContactDropdown(
                                opid,
                                $("#maincontact_c").val(),
                                $("#account_id").val()
                            ); //populate dropdown once when editview loads.
                            $("#account_name").on(
                                "focusout.account-name",
                                function () {
                                    lx.opportunity.getMainContactDropdown(
                                        opid,
                                        $("#maincontact_c").val(),
                                        $("#account_id").val(),
                                        $("#account_name").val()
                                    ); //populate dropdown once when editview loads.
                                }
                            );
                            $("#maincontact_c").on(
                                "change.lx-main-contact-c",
                                function () {
                                    lx.opportunity.getnewMainContactCFields();
                                }
                            );
                            lx.opportunity.resultsSearchTSECRHandler(false);
                            lx.opportunity.resultsListDuplicatesHandler(false);
                        }
                    }
                }
            }
            // only comment out during testing please
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
