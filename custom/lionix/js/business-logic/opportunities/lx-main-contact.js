// This file containts opportunities bussines logic
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
                        console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-main-contact.js', '!function()', 'initial');
                        console.log("Loading Lionix code on EditView on module:", crmEditView.module.value);
                        console.groupEnd();
                        opid = document.forms['EditView'].record.value;
                        $("#maincontact_c").append('<div id="maincontact_c_lxajaxed"/>');
                        getLxOpportunityMainContactDropdown(opid, $("#maincontact_c").val(), $("#account_id").val()); //popoulate dropdown once when editview loads.
                        $('#account_name').on("focusout.account-name", function() {
                            getLxOpportunityMainContactDropdown(opid, $("#maincontact_c").val(), $("#account_id").val()); //popoulate dropdown once when editview loads.
                        });
                        $('#maincontact_c').on("change.lx-main-contact-c", function() {
                            getnewMainContactCFields();
                        });
                    }
                }
            }
            // if needed only once, you can stop observing with observer.disconnect();
            // observer.disconnect();
        }
        // When need to find something special
        // mutations.forEach(function(mutation) {
        //     console.log('select2-lionixcrm mutation:', mutation);
        //     //     if (mutation.type == "attributes") {
        //     //         if (mutation.target.nodeName == "FORM" && mutation.target.id == "some-form-id") {
        //     //             //when found do your code
        //     //             //your code
        //     //             // if needed only once, you can stop observing with observer.disconnect();
        //     //             //observer.disconnect();
        //     //         }
        //     //     }
        // });
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

// function definitions section
function getLxOpportunityMainContactDropdown(opportunityId, currentValue, accountId) {
    var method = "getOpportunityMainContactList";
    var data = "method=" + method;
    data += "&opportunityId=" + opportunityId;
    data += "&currentValue=" + currentValue;
    data += "&accountId=" + accountId;
    //data += "&use_adodb5="+"1";
    $.ajax({
        // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
        beforeSend: function(jqXHR, settings) {
            console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-main-contact.js', 'getLxOpportunityMainContactDropdown()', 'ajax beforeSend');
            console.log("*** start ***");
            console.log("beforeSend callback:", settings.url);
            console.groupEnd();
        }, //end beforeSend
        url: 'lxajax.php',
        type: 'GET',
        data: data,
        // success is a function to be called if the request succeeds.
        success: function(data, status, jqXHR) {
            console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-main-contact.js', 'getLxOpportunityMainContactDropdown()', 'ajax success');
            console.log("success callback:", status);
            console.log("data:", data);
            $('#maincontact_c').fillSelect($.parseJSON(data));
            console.groupEnd();
        }, // end success
        // error is a function to be called if the request fails.
        error: function(jqXHR, status, error) {
            console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-main-contact.js', 'getLxOpportunityMainContactDropdown()', 'ajax error');
            console.log("error callback:", status);
            console.log("Function getLxOpportunityMainContactDropdown error:", error);
            console.groupEnd();
        }, // end error
        // complete is a function to be called when the request finishes (after success and error callbacks are executed).
        // complete: function(jqXHR, status) {
        // }, // end complete
        datatype: "text"
    }); // end ajax
    getnewMainContactCFields();
} // end function

function getnewMainContactCFields() {
    var form_name = 'EditView';
    if ($("#maincontact_c").val() == 'new') {
        $("#maincontactfirstname_c").parent('div').parent('div').show();
        $('#maincontactfirstname_c').on("focusout.maincontactfirstname_c", function() {
            switch ($("#maincontactfirstname_c").val().toUpperCase()) {
                case "new":
                    $("#maincontactfirstname_c").val('');
                    break;
            }
        });
        $("#maincontactlastname_c").parent('div').parent('div').show();
        $("#maincontactlastname2_c").parent('div').parent('div').show();
        $("#maincontactphonework_c").parent('div').parent('div').show();
        $("#maincontactemailaddress_c").parent('div').parent('div').show();
        $("#maincontacttitle_c").parent('div').parent('div').show();
        $("#maincontactcedula_c").parent('div').parent('div').show();
        lxValidateCRMfield(form_name, 'maincontactfirstname_c', 'Nombre nuevo contacto', true);
        lxValidateCRMfield(form_name, 'maincontactlastname_c', '1er apellido nuevo contacto', true);
        lxValidateCRMfield(form_name, 'maincontactlastname2_c', '2do apellido nuevo contacto', true);
        lxValidateCRMfield(form_name, 'maincontactphonework_c', 'Teléfono nuevo contacto', true);
        lxValidateCRMfield(form_name, 'maincontactemailaddress_c', 'Correo electrónico nuevo contacto', true);
        lxValidateCRMfield(form_name, 'maincontacttitle_c', 'Cargo nuevo contacto', true);
        lxValidateCRMfield(form_name, 'maincontactcedula_c', 'Cédula nuevo contacto', false);
    } else {
        lxValidateCRMfield(form_name, 'maincontactfirstname_c', 'Nombre nuevo contacto', false);
        lxValidateCRMfield(form_name, 'maincontactlastname_c', '1er apellido nuevo contacto', false);
        lxValidateCRMfield(form_name, 'maincontactlastname2_c', '2do apellido nuevo contacto', false);
        lxValidateCRMfield(form_name, 'maincontactphonework_c', 'Teléfono nuevo contacto', false);
        lxValidateCRMfield(form_name, 'maincontactemailaddress_c', 'Correo electrónico nuevo contacto', false);
        lxValidateCRMfield(form_name, 'maincontacttitle_c', 'Cargo nuevo contacto', false);
        lxValidateCRMfield(form_name, 'maincontactcedula_c', 'Cédula nuevo contacto', false);
        $('#maincontactfirstname_c').off("focusout.maincontactfirstname_c");
        $("#maincontactfirstname_c").parent('div').parent('div').hide();
        $("#maincontactlastname_c").parent('div').parent('div').hide();
        $("#maincontactlastname2_c").parent('div').parent('div').hide();
        $("#maincontactphonework_c").parent('div').parent('div').hide();
        $("#maincontactemailaddress_c").parent('div').parent('div').hide();
        $("#maincontacttitle_c").parent('div').parent('div').hide();
        $("#maincontactcedula_c").parent('div').parent('div').hide();
    }
}

//eof
