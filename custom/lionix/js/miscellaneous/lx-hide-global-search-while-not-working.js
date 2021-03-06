// This file containts all modules bussines logic
// function definitions section
//Self-Invoking Anonymous Function Notation
// !function(){}(); // easy to read, the result is unimportant.
// (function(){})(); // like above but more parens.
// (function(){}()); // Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html and look for invoked immediately
!(function () {
    // create an observer instance
    // https://developer.mozilla.org/en/docs/Web/API/MutationObserver
    $(".desktop-bar > ul > li > form[name=UnifiedSearch]").hide();
    let observer = new MutationObserver(function (mutations) {
        if (mutations) {
            if (
                $(".desktop-bar > ul > li > form[name=UnifiedSearch]").is(
                    ":visible"
                ) ||
                $(".desktop-bar > ul > li.navbar-search").is(":visible") ||
                $(".tablet-bar > ul > li.navbar-search").is(":visible") ||
                $(".mobile-bar > ul > li.navbar-search").is(":visible")
            ) {
                console.warn(
                    `Hidding Unified Search on all modules %clx-hide-global-search-while-not-working.js`,
                    "color: green"
                );
                $(".desktop-bar > ul > li > form[name=UnifiedSearch]").hide();
                $(".desktop-bar > ul > li.navbar-search").hide();
                $(".tablet-bar > ul > li.navbar-search").hide();
                $(".mobile-bar > ul > li.navbar-search").hide();
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
