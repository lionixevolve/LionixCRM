// function definitions section
lx.lionixCRM.getEnvironment = function(forceCheck) {
    if (forceCheck) {
        lx.lionixCRM.config.environment = undefined;
    }
    if ($("#LionixCRM-environment").length == 0) {
        try {
            if (lx.lionixCRM.config.environment.toLowerCase() === 'testing') {
                let pbclass = "progress-bar bg-info";
                let probability = 100;
                let text_bar = "Testing Environment"
                $('#content').before('<div id="LionixCRM-environment" data-enviroment="Testing Environment" class="progresslx"> <div class="' + pbclass + '" role="progressbar" aria-valuenow="' + probability + '" aria-valuemin="0" aria-valuemax="100" style="min-width: 10%; width: ' + probability + '%; height: 30px; font-size:1.9em; line-height: 2.8rem;">' + text_bar + '</div></div>');
            } else {
                $('#content').before('<div id="LionixCRM-environment" data-enviroment="Production Environment" />');
            }
            console.log("LionixCRM-environment div indicator added.");
        } catch (error) {
            console.log('Environment property is not present!');
            console.log('Retrieving environment property...');
            lx.lionixCRM.getConfigOption('environment').then(function(data) {
                console.log('Environment successfully retrieved [' + data + ']');
                lx.lionixCRM.getEnvironment(false);
            });
        }
    } else {
        console.log("LionixCRM-environment div indicator already exists.");
    }
} // end function

// This file containts all modules bussines logic
//Self-Invoking Anonymous Function Notation
// !function(){}();  easy to read, the result is unimportant.
// (function(){})();  like above but more parens.
// (function(){}());  Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html then search for invoked immediately
!function() {
    // create an observer instance
    // https://developer.mozilla.org/en/docs/Web/API/MutationObserver
    var observer = new MutationObserver(function(mutations) {
        if (mutations) {
            console.log("Check environment observer '%s' '%s' '%s'", 'all modules', 'lx-check-crm-environment.js', '!function()');
            console.log('Running lx.lionixCRM.checkEnvironment() function');
            lx.lionixCRM.getEnvironment(false);
            // if needed only once, you can stop observing with observer.disconnect();
            observer.disconnect();
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
