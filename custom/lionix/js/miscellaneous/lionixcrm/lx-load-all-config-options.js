// This file containts all modules bussines logic
//Self-Invoking Anonymous Function Notation
// !function(){}(); // easy to read, the result is unimportant.
// (function(){})(); // like above but more parens.
// (function(){}()); // Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html and look for invoked immediately
!(function () {
    // create an observer instance
    // https://developer.mozilla.org/en/docs/Web/API/MutationObserver
    let observer = new MutationObserver(async function (mutations) {
        if (mutations) {
            let run = true;
            if (lx.events.lxLoadAllConfigOptions) {
                if (lx.events.lxLoadAllConfigOptions.status == "ready") {
                    run = false;
                }
            }
            if (run) {
                if (!lx.events.hasOwnProperty("lxLoadAllConfigOptions")) {
                    lx.events.lxLoadAllConfigOptions = { status: "send" };
                }

                if (lx.events.lxLoadAllConfigOptions.status == "send") {
                    if (
                        Object.keys(lx.lionixCRM.config).length === 0 &&
                        lx.lionixCRM.config.constructor === Object
                    ) {
                        console.warn("Loading all LionixCRM config options...");
                        lx.events.lxLoadAllConfigOptions.status = "waiting";
                        lx.events.lxLoadAllConfigOptions.status = await lx.lionixCRM.getConfigOption(
                            "all"
                        );
                        document.dispatchEvent(
                            new Event("lxLoadAllConfigOptions")
                        );
                    }
                    if (lx.events.lxLoadAllConfigOptions.status == "ready") {
                        console.warn(
                            "Observer on %clx-load-all-config-options.js %cdisconnected",
                            "color:blue",
                            "color:red"
                        );
                        // if needed only once, you can stop observing with observer.disconnect();
                        observer.disconnect();
                        lx.observers.disconnected += 1;
                        lx.observers.observing -= 1;
                        console.warn("lx.observers", lx.observers);
                    }
                }
            }
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
