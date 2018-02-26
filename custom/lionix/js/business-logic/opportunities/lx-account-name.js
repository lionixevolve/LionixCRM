//  This file containts opportunities bussines logic
//  function definitions section
lx.opportunity.getAccountNameByBusinessType = function(observer) {
    lx.lionixCRM.getConfigOption('business_type').then(function(data) {
        console.log("business_type:", data);
        if ($("#account_name_lxajaxed").length == 0 || $('#account_name_lxajaxed').data('business_type') != data) {
            $("#account_name").append('<div id="account_name_lxajaxed" data-business_type="' + data + '" />');
            switch (lx.lionixCRM.config.business_type.toLowerCase()) {
                case 'b2c':
                    lx.field.validate('EditView', 'account_name', 'Nombre de Cuenta', false);
                    $('#maincontact_c').on("change.lx-hide-account-name", function() {
                        lx.field.validate('EditView', 'account_name', 'Nombre de Cuenta', false);
                        if ($("#maincontact_c").val() == 'new') {
                            $('#account_id').val('');
                            lx.field.show("account_name", false);
                        } else {
                            lx.field.show("account_name", true);
                        }
                    });
                    break;
                case 'b2b':
                    lx.field.validate('EditView', 'account_name', 'Nombre de Cuenta', true);
                    $('#maincontact_c').on("change.lx-hide-account-name", function() {
                        lx.field.validate('EditView', 'account_name', 'Nombre de Cuenta', true);
                    });
                    break;
            }
            // only comment out during testing please
            // observer.disconnect();
        }
    });
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
                    console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-account-name.js', '!function()', 'initial');
                    console.log("Loading Lionix code on EditView on module:", crmEditView.module.value);
                    lx.opportunity.getAccountNameByBusinessType(observer);
                }
            }
            // if needed only once, you can stop observing with observer.disconnect();
            // observer.disconnect();
        }
    });
    // Observer target
    var target = document.querySelector('#content');
    if (target) {
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
    }
    // end observer
}();
//eof
