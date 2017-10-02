// This file containts all modules bussines logic
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
                if (crmEditView.module.value == 'Notes') {
                    console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'notes', 'lx-remove-contacts-from-related-to.js', '!function()', 'initial');
                    console.log("Loading Lionix code on EditView on module:", crmEditView.module.value);
                    // Notes has already the contact_id field to link Contacts, and the related to field fails when Contact is selected, so is better it not appear at all
                    $("#parent_type option[value='Contacts']").remove();
                    console.groupEnd();
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
