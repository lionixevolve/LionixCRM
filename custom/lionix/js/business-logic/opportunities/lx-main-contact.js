// This file containts opportunities bussines logic

// function definitions section
lx.opportunity.getnewMainContactCFields = function() {
    var form_name = 'EditView';
    if ($("#lx_opportunity_maincontact_c_lxajaxed_getnewMainContactCFields_first_time").length == 0) {
        $("#maincontact_c").append('<div id="lx_opportunity_maincontact_c_lxajaxed_getnewMainContactCFields_first_time"/>');
        $("#maincontactfirstname_c").val('');
        $("#maincontactlastname_c").val('');
        $("#maincontactlastname2_c").val('');
        $("#maincontactphonework_c").val('');
        $("#maincontactemailaddress_c").val('');
        $("#maincontacttitle_c").val('');
        $("#maincontactcedula_c").val('');
    }
    if ($("#maincontact_c").val() == 'new') {
        $('#maincontactfirstname_c').on("focusout.maincontactfirstname_c", function() {
            switch ($("#maincontactfirstname_c").val().toUpperCase()) {
                case "NEW":
                    $("#maincontactfirstname_c").val('');
                    break;
            }
        });
        lx.field.show("maincontactfirstname_c", true);
        lx.field.show("maincontactlastname_c", true);
        lx.field.show("maincontactlastname2_c", true);
        lx.field.show("maincontactphonework_c", true);
        lx.field.show("maincontactemailaddress_c", true);
        lx.field.show("maincontacttitle_c", true);
        lx.field.show("maincontactcedula_c", true);
        lx.field.validate(form_name, 'maincontactfirstname_c', 'Nombre nuevo contacto', true);
        lx.field.validate(form_name, 'maincontactlastname_c', '1er apellido nuevo contacto', true);
        lx.field.validate(form_name, 'maincontactlastname2_c', '2do apellido nuevo contacto', false);
        lx.field.validate(form_name, 'maincontactphonework_c', 'Teléfono nuevo contacto', true);
        lx.field.validate(form_name, 'maincontactemailaddress_c', 'Correo electrónico nuevo contacto', true);
        lx.field.validate(form_name, 'maincontacttitle_c', 'Cargo nuevo contacto', true);
        lx.field.validate(form_name, 'maincontactcedula_c', 'Cédula nuevo contacto', false);
    } else {
        lx.field.validate(form_name, 'maincontactfirstname_c', 'Nombre nuevo contacto', false);
        lx.field.validate(form_name, 'maincontactlastname_c', '1er apellido nuevo contacto', false);
        lx.field.validate(form_name, 'maincontactlastname2_c', '2do apellido nuevo contacto', false);
        lx.field.validate(form_name, 'maincontactphonework_c', 'Teléfono nuevo contacto', false);
        lx.field.validate(form_name, 'maincontactemailaddress_c', 'Correo electrónico nuevo contacto', false);
        lx.field.validate(form_name, 'maincontacttitle_c', 'Cargo nuevo contacto', false);
        lx.field.validate(form_name, 'maincontactcedula_c', 'Cédula nuevo contacto', false);
        $('#maincontactfirstname_c').off("focusout.maincontactfirstname_c");
        lx.field.show("maincontactfirstname_c", false);
        lx.field.show("maincontactlastname_c", false);
        lx.field.show("maincontactlastname2_c", false);
        lx.field.show("maincontactphonework_c", false);
        lx.field.show("maincontactemailaddress_c", false);
        lx.field.show("maincontacttitle_c", false);
        lx.field.show("maincontactcedula_c", false);
    }
}

lx.opportunity.getMainContactDropdown = function(opportunityId, currentValue, accountId) {
    var method = "getOpportunityMainContactList";
    var data = "method=" + method;
    data += "&opportunityId=" + opportunityId;
    data += "&currentValue=" + currentValue;
    data += "&accountId=" + accountId;
    //data += "&use_adodb5="+"1";
    $.ajax({
        // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
        beforeSend: function(jqXHR, settings) {
            console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-main-contact.js', 'lx.opportunity.getMainContactDropdown()', 'ajax beforeSend');
            console.log("*** start ***");
            console.log("beforeSend callback:", settings.url);
        }, //end beforeSend
        url: 'lxajax.php',
        type: 'GET',
        data: data,
        // success is a function to be called if the request succeeds.
        success: function(data, status, jqXHR) {
            console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-main-contact.js', 'lx.opportunity.getMainContactDropdown()', 'ajax success');
            console.log("success callback:", status);
            console.log("data:", data);
            $('#maincontact_c').fillSelect($.parseJSON(data));
        }, // end success
        // error is a function to be called if the request fails.
        error: function(jqXHR, status, error) {
            console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-main-contact.js', 'lx.opportunity.getMainContactDropdown()', 'ajax error');
            console.log("error callback:", status);
            console.log("Function lx.opportunity.getMainContactDropdown error:", error);
        }, // end error
        // complete is a function to be called when the request finishes (after success and error callbacks are executed).
        // complete: function(jqXHR, status) {
        // }, // end complete
        datatype: "text"
    }); // end ajax
    lx.opportunity.getnewMainContactCFields();
} // end function

//Self-Invoking Anonymous Function Notation
// !function(){}(); // easy to read, the result is unimportant.
// (function(){})(); // like above but more parens.
// (function(){}()); // Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html then search for invoked immediately
!function() {
    // create an observer instance
    // https://developer.mozilla.org/en/docs/Web/API/MutationObserver
    var observer = new MutationObserver(function(mutations) {
        if (mutations) {
            var crmEditView = document.forms['EditView'];
            if (crmEditView) {
                if (crmEditView.module.value == 'Opportunities') {
                    if ($("#maincontact_c_lxajaxed").length == 0) {
                        console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-main-contact.js', '!function()', 'initial');
                        console.log("Loading Lionix code on EditView on module:", crmEditView.module.value);
                        opid = document.forms['EditView'].record.value;
                        $("#maincontact_c").append('<div id="maincontact_c_lxajaxed"/>');
                        lx.opportunity.getMainContactDropdown(opid, $("#maincontact_c").val(), $("#account_id").val()); //popoulate dropdown once when editview loads.
                        $('#account_name').on("focusout.account-name", function() {
                            lx.opportunity.getMainContactDropdown(opid, $("#maincontact_c").val(), $("#account_id").val()); //popoulate dropdown once when editview loads.
                        });
                        $('#maincontact_c').on("change.lx-main-contact-c", function() {
                            lx.opportunity.getnewMainContactCFields();
                        });
                    }
                }
            }
            // if needed only once, you can stop observing with observer.disconnect();
            // observer.disconnect();
        }
    });
    // Observer target
    var target = document.querySelector('#content');
    // configuration of the observer:
    // NOTE: At the very least, childList, attributes, or characterData must be set to true. Otherwise, "An invalid or illegal string was specified" error is thrown.
    var config = {
        attributes: true,
        childList: true,
        // characterData: true,
        subtree: true
    };
    // pass in the target node, as well as the observer options
    observer.observe(target, config);
    // end observer
}();
//eof
