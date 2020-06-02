// function definitions section
lx.lionixCRM.getEnvironment = async function (forceCheck) {
    try {
        if (forceCheck) {
            lx.lionixCRM.config.environment = undefined;
            $("#LionixCRM-environment").remove();
            console.warn("Retrieving environment property...");
            data = await lx.lionixCRM.getConfigOption("environment");
            console.warn(`environment property retrieved, status ${data}`);
        }
        if ($("#LionixCRM-environment").length == 0) {
            if (lx.lionixCRM.config.environment.toLowerCase() === "testing") {
                let pbclass = "progress-bar bg-info";
                let probability = 100;
                let text_bar = "Testing Environment";
                $("#content").before(
                    `<div id="LionixCRM-environment" data-enviroment="Testing Environment" class="progresslx">
                        <div class="${pbclass}" role="progressbar" aria-valuenow="${probability}" aria-valuemin="0" aria-valuemax="100"
                            style="min-width: 10%; width: ${probability}%; height: 30px; font-size:1.9em; line-height: 2.8rem;">${text_bar}
                        </div>
                    </div>`
                );
            } else {
                $("#content").before(
                    '<div id="LionixCRM-environment" data-enviroment="Production Environment" />'
                );
            }
            console.warn("LionixCRM-environment div indicator added.");
        } else {
            console.warn("LionixCRM-environment div indicator already exists.");
        }
    } catch (error) {
        console.error(
            "environment property is not present!",
            "Waiting for LionixCRM config options..."
        );
        document.addEventListener("lxLoadAllConfigOptions", () =>
            lx.lionixCRM.getEnvironment(false)
        );
    }
}; // end function

// This file containts all modules bussines logic
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
            lx.lionixCRM.getEnvironment(false);
            console.warn(
                "Observer on %clx-check-crm-environment.js %cdisconnected",
                "color:blue",
                "color:red"
            );
            // if needed only once, you can stop observing with observer.disconnect();
            observer.disconnect();
            lx.observers.disconnected += 1;
            lx.observers.observing -= 1;
            console.warn("lx.observers", lx.observers);
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
