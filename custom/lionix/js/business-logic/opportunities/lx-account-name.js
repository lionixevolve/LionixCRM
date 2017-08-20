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
                    if ($("#account_name_lxajaxed").length == 0) {
                        console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-account-name.js', '!function()', 'initial');
                        console.log("Loading Lionix code on EditView on module:", crmEditView.module.value);
                        console.groupEnd();
                        opid = document.forms['EditView'].record.value;
                        $("#account_name").append('<div id="account_name_lxajaxed"/>');
                        getLxOpportunityAccountNameByBusinessType(); //popoulate dropdown once when editview loads.
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
function getLxOpportunityAccountNameByBusinessType(opportunityId, currentValue, accountId) {
    var method = "getConfigBusinessType";
    var data = "method=" + method;
    // data += "&opportunityId=" + opportunityId;
    // data += "&currentValue=" + currentValue;
    // data += "&accountId=" + accountId;
    //data += "&use_adodb5="+"1";
    $.ajax({
        // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
        beforeSend: function(jqXHR, settings) {
            console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-account-name.js', 'getLxOpportunityAccountNameByBusinessType()', 'ajax beforeSend');
            console.log("*** start ***");
            console.log("beforeSend callback:", settings.url);
            console.groupEnd();
        }, //end beforeSend
        url: 'lxajax.php',
        type: 'GET',
        data: data,
        // success is a function to be called if the request succeeds.
        success: function(data, status, jqXHR) {
            console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-account-name.js', 'getLxOpportunityAccountNameByBusinessType()', 'ajax success');
            console.log("success callback:", status);
            console.log("data:", data);

            switch (data) {
                case 'b2c':
                    lxValidateCRMfield('EditView', 'account_name', 'Nombre de Cuenta', false);
                    $('#maincontact_c').on("change.lx-hide-account-name", function() {
                        if ($("#maincontact_c").val() == 'new') {
                            $('#account_id').val('');
                            lxShowCRMfield("account_name", false);
                        } else {
                            lxShowCRMfield("account_name", true);
                        }
                    });
                    break;
                case 'b2b':
                    lxValidateCRMfield('EditView', 'account_name', 'Nombre de Cuenta', true);
                    $('#maincontact_c').off("change.lx-hide-account-name");
                    break;
            }

            console.groupEnd();
        }, // end success
        // error is a function to be called if the request fails.
        error: function(jqXHR, status, error) {
            console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-account-name.js', 'getLxOpportunityAccountNameByBusinessType()', 'ajax error');
            console.log("error callback:", status);
            console.log("Function getLxOpportunityAccountNameByBusinessType error:", error);
            console.groupEnd();
        }, // end error
        // complete is a function to be called when the request finishes (after success and error callbacks are executed).
        // complete: function(jqXHR, status) {
        // }, // end complete
        datatype: "text"
    }); // end ajax
} // end function
//eof
