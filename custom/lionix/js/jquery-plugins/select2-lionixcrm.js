// This file containts users bussines logic
//Self-Invoking Anonymous Function Notation
// !function(){}(); // easy to read, the result is unimportant.
// (function(){})(); // like above but more parens.
// (function(){}()); // Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html then search for invoked immediately
! function() {
    // create an observer instance
    // https://developer.mozilla.org/en/docs/Web/API/MutationObserver
    var observer = new MutationObserver(function(mutations) {
        if (mutations) {
            $('select:not([class^="select2"])').each(function(index) {
                if (!$(this).hasClass("select2-hidden-accessible")) {
                    $(this).select2({
                        closeOnSelect: false,
                        dropdownAutoWidth: 'true',
                        width: 'auto', //mucho más ancho
                        theme: "bootstrap",
                        dropdownParent: $(this).closest('div')
                    })
                }
            });
            var crmDetailView = document.forms['DetailView'];
            if (crmDetailView) {
                if (crmDetailView.module.value == 'AOW_WorkFlow') {
                    $('select').select2('destroy');
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
    var target = document.querySelector('body'); //uncomment to run

    // configuration of the observer:
    // NOTE: At the very least, childList, attributes, or characterData must be set to true. Otherwise, "An invalid or illegal string was specified" error is thrown.
    var config = {
        attributes: true,
        childList: true,
        // characterData: true,
        // subtree: true
    };
    // pass in the target node, as well as the observer options
    observer.observe(target, config); // uncomment to run
    // end observer
}();

// function definitions section
//eof
