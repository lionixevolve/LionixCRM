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
                if (crmEditView.module.value == "AOS_Quotes") {
                    if (!!$("input[name^='product_name']").length) {
                        // input field size changes only once when added to the form
                        $("input[name^='product_name']")
                            .filter(function () {
                                if ($(this).css("width") != "400px") {
                                    console.warn(
                                        `Input ${$(this).attr(
                                            "id"
                                        )} product_name current size %c${$(
                                            this
                                        ).css("width")}`,
                                        "color: blue"
                                    );
                                    console.warn(
                                        `Input ${$(this).attr(
                                            "id"
                                        )} size set to %c400px %clx-product-name-size.js`,
                                        "color: blue",
                                        "color: green"
                                    );
                                }
                                return $(this).css("width") != "400px";
                            })
                            .css("width", "400px");
                        // search bar below input field must be changed every time it appears
                        // observer must check attributes for this one
                        $(".yui-ac-content").css("width", "600px");
                        // if needed only once, you can stop observing with observer.disconnect();
                        // observer.disconnect();
                    }
                }
            }
        }
    });
    // Observer target
    let target = document.querySelector("body"); //uncomment to run
    if (target == null) {
        //This part is for iFrame apps
        let target = document.querySelector("#EditView"); //uncomment to run
    }
    // configuration of the observer:
    // NOTE: At the very least, childList, attributes, or characterData must be set to true. Otherwise, "An invalid or illegal string was specified" error is thrown.
    let config = {
        attributes: true,
        childList: true,
        characterData: true,
        subtree: true,
    };
    // pass in the target node, as well as the observer options
    observer.observe(target, config); // uncomment to run
    lx.observers.created += 1;
    lx.observers.observing += 1;
    // end observer
})();
