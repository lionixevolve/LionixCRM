//  This file containts opportunities bussines logic
//  function definitions section
lx.contact.renderContactDuplicates = function (duplicates) {
    console.warn("duplicados devueltos:", duplicates);
    $(".contact_duplicates ul li").remove();
    if (duplicates.data.length) {
        duplicates.data.forEach(function (element) {
            primary_address = {};
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
            duplicate_detail += `">`;
            duplicate_detail += `${element.first_name} ${element.last_name} ${element.lastname2_c} ${element.cedula_c} `;
            element.emails.forEach(function (element) {
                duplicate_detail += element.email_address + " ";
            });
            duplicate_detail += element.phone_mobile + " ";
            duplicate_detail += element.phone_work;
            // Client specific only fields
            // duplicate_detail += ' - ' + element.{fieldname};
            // Client specific only fields
            duplicate_detail += "</li>";
            $(".contact_duplicates ul").append(duplicate_detail);
        });
        newh = duplicates.data.length * 20 + 18; // last 18px bit is for duplicates title
        $(`#contact_duplicates_${duplicates.fieldname} .yui-ac-content`).css(
            "height",
            `${newh}px`
        );
        $(`#contact_duplicates_${duplicates.fieldname} .yui-ac-content`).show(
            500
        );
        $(".contact_duplicates li").off("mouseover.contact_duplicates_list");
        $(".contact_duplicates li").on(
            "mouseover.contact_duplicates_list",
            function () {
                $(this).toggleClass("yui-ac-highlight");
                $(this).siblings("li").removeClass("yui-ac-highlight");
            }
        );
        $(".contact_duplicates li").off("click.contact_duplicates_list");
        $(".contact_duplicates li").on(
            "click.contact_duplicates_list",
            function () {
                $(".contact_duplicates .yui-ac-content").hide(500);
                console.warn(
                    `Redirecting to contacts Editview: - ${window.location.origin}${window.location.pathname}?module=Contacts&return_module=Contacts&action=EditView&record=${this.id}`
                );
                toastr["info"](
                    `Redireccionando a ${$(this).data("first_name")}...`,
                    "Posibles duplicados encontrados",
                    {
                        positionClass: "toast-bottom-center",
                        showDuration: "0",
                        hideDuration: "0",
                        timeOut: "4000",
                        extendedTimeOut: "0",
                        progressBar: true,
                    }
                );
                window.location.href = `${
                    window.location.origin + window.location.pathname
                }?module=Contacts&return_module=Contacts&action=EditView&record=${
                    this.id
                }`;
            }
        );
    }
};

lx.contact.resultsSearchTSECRHandler = async function (forceCheck) {
    try {
        if (forceCheck) {
            lx.lionixCRM.config.modules = undefined;
            $("#cedula_c_lxajaxed").remove();
            console.error("Retrieving modules[contacts] properties...");
            data = await lx.lionixCRM.getConfigOption("modules");
            console.error("Modules[contacts] successfully retrieved", data);
        }
        if ($("#cedula_c_lxajaxed").length == 0) {
            keyup_status = "disabled";
            $("#cedula_c").off("keyup.results_search_tsecr");
            if (lx.lionixCRM.config.modules.contacts.results_search_tsecr) {
                keyup_status = "enabled";
                $("#cedula_c").on(
                    "keyup.results_search_tsecr",
                    async function () {
                        if ($(this).val().length != 9) {
                            $("#first_name").val("");
                            $("#last_name").val("");
                            $("#lastname2_c").val("");
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
                            $("#cedula_c").val(tsecrlist.data[0].cedula_c);
                            $("#first_name").val(tsecrlist.data[0].first_name);
                            $("#last_name").val(tsecrlist.data[0].last_name);
                            $("#lastname2_c").val(
                                tsecrlist.data[0].lastname2_c
                            );
                            $("#first_name").focus();
                            $("#first_name").trigger("keypress");
                        }
                    }
                );
            }
            $("#cedula_c").append(
                `<div id="cedula_c_lxajaxed" data-results_search_tsecr="${keyup_status}"/>`
            );
            console.warn(
                `keyup.results_search_tsecr on #cedula_c ${keyup_status}`
            );
        }
    } catch (error) {
        console.error(
            "resultsSearchTSECRHandler",
            "Modules[contacts] properties are not present!",
            "Waiting for LionixCRM config options..."
        );
        document.addEventListener("lxLoadAllConfigOptions", () =>
            lx.contact.resultsSearchTSECRHandler(false)
        );
    }
};

lx.contact.resultsListDuplicatesHandler = async function (forceCheck) {
    try {
        if (forceCheck) {
            lx.lionixCRM.config.modules = undefined;
            $("#full_name_lxajaxed").remove();
            console.warn("Retrieving modules[contacts] properties...");
            data = await lx.lionixCRM.getConfigOption("modules");
            console.warn("Modules[contacts] successfully retrieved", data);
        }
        if ($("#full_name_lxajaxed").length == 0) {
            keyup_status = "disabled";
            $("#first_name, #last_name, #lastname2_c").off(
                "focusout.results_list_duplicates"
            );
            if (lx.lionixCRM.config.modules.contacts.results_list_duplicates) {
                keyup_status = "enabled";
                $("#first_name, #last_name, #lastname2_c").on(
                    "keypress.results_list_duplicates",
                    async function () {
                        ffn = $("#first_name");
                        fln = $("#last_name");
                        fln2 = $("#lastname2_c");
                        // femail = $('#Contacts0emailAddress0');
                        // fced = $('#cedula_c');
                        if (
                            $(ffn).val().length > 2 &&
                            ($(fln).val().length > 2 ||
                                $(fln2).val().length > 2)
                        ) {
                            if (
                                $(`#contact_duplicates_${this.id}`).length == 0
                            ) {
                                // width: 750px can be changed in each Client
                                width = lx.lionixCRM.config.modules.contacts
                                    .results_list_duplicates_width
                                    ? lx.lionixCRM.config.modules.contacts
                                          .results_list_duplicates_width
                                    : 750;
                                $(this).before(
                                    `<div id="contact_duplicates_${this.id}" class="contact_duplicates yui-ac-container" style="position: relative; left: 200px; top:0px;">
                                        <div class="yui-ac-content" style="width: ${width}px; height: 60px; display: none; ">
                                            <div class="yui-ac-bd">Posibles duplicados encontrados<ul id="#ul_${this.id}"></ul></div>
                                        </div>
                                    </div>`
                                );
                            }
                            $("#first_name, #last_name, #lastname2_c").off(
                                "focusout.results_list_duplicates"
                            );
                            $("#first_name, #last_name, #lastname2_c").on(
                                "focusout.results_list_duplicates",
                                function () {
                                    $(
                                        ".contact_duplicates .yui-ac-content"
                                    ).hide(500);
                                }
                            );
                            duplicates = await lx.lionixCRM.getContactDuplicates(
                                {
                                    fieldname: this.id,
                                    first_name: $(ffn).val(),
                                    last_name: $(fln).val(),
                                    lastname2_c: $(fln2).val(),
                                    // "cedula_c": $(fced).val(),
                                    // "email_address": $(femail).val()
                                }
                            );
                            lx.contact.renderContactDuplicates(duplicates);
                        }
                    }
                );
            }
            $("#cedula_c").append(
                `<div id="full_name_lxajaxed" data-results_list_duplicates="${keyup_status}"/>`
            );
            console.warn(
                `keypress.results_list_duplicates on #first_name, #last_name and #lastname2_c ${keyup_status}`
            );
        }
    } catch (error) {
        console.error(
            "resultsListDuplicatesHandler",
            "Modules[contacts] properties are not present!",
            "Waiting for LionixCRM config options..."
        );
        document.addEventListener("lxLoadAllConfigOptions", () =>
            lx.contact.resultsListDuplicatesHandler(false)
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
                if (crmEditView.module.value == "Contacts") {
                    lx.contact.resultsSearchTSECRHandler(false);
                    lx.contact.resultsListDuplicatesHandler(false);
                }
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
