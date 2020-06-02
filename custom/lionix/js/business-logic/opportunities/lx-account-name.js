//  function definitions section
lx.opportunity.getAccountNameByBusinessType = async function (forceCheck) {
    try {
        if (forceCheck) {
            lx.lionixCRM.config.business_type = undefined;
            $("#account_name_lxajaxed").remove();
            console.warn("Retrieving business_type property...");
            data = await lx.lionixCRM.getConfigOption("business_type");
            console.warn("business_type successfully retrieved", data);
        }
        data = lx.lionixCRM.config.business_type.toLowerCase();
        if (
            $("#account_name_lxajaxed").length == 0 ||
            $("#account_name_lxajaxed").data("business_type") != data
        ) {
            switch (data) {
                case "b2c":
                    lx.field.validate(
                        "EditView",
                        "account_name",
                        "Nombre de Cuenta",
                        false
                    );
                    $("#maincontact_c").on(
                        "change.lx-hide-account-name",
                        function () {
                            lx.field.validate(
                                "EditView",
                                "account_name",
                                "Nombre de Cuenta",
                                false
                            );
                            if ($("#maincontact_c").val() == "new") {
                                $("#account_id").val("");
                                lx.field.show("account_name", false);
                            } else {
                                lx.field.show("account_name", true);
                            }
                        }
                    );
                    break;
                case "b2b":
                    lx.field.validate(
                        "EditView",
                        "account_name",
                        "Nombre de Cuenta",
                        true,
                        "lx.opportunity.getAccountNameByBusinessType"
                    );
                    $("#maincontact_c").on(
                        "change.lx-hide-account-name",
                        function () {
                            lx.field.validate(
                                "EditView",
                                "account_name",
                                "Nombre de Cuenta",
                                true,
                                "lx.opportunity.getAccountNameByBusinessType"
                            );
                        }
                    );
                    break;
            }
            $("#account_name_lxajaxed").remove();
            $("#account_name").append(
                `<div id="account_name_lxajaxed" data-business_type="${data}" />`
            );
            console.warn(
                "DIV indicator added %caccount_name_lxajaxed",
                "color: blue"
            );
        }
    } catch (error) {
        console.error(
            "business_type property is not present!",
            "Waiting for LionixCRM config options..."
        );
        document.addEventListener("lxLoadAllConfigOptions", () =>
            lx.opportunity.getAccountNameByBusinessType(false)
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
                if (crmEditView.module.value == "Opportunities") {
                    lx.opportunity.getAccountNameByBusinessType(false);
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
