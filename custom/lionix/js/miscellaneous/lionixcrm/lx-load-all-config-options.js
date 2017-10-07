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
            if (Object.keys(lx.lionixCRM.config).length === 0 && lx.lionixCRM.config.constructor === Object) {
                console.log("Check environment '%s' '%s' '%s' '%s'", 'all modules', 'lx-lionixcrm-load-config-options.js', '!function()', 'observer');
                console.log('Loading all LionixCRM config options');
                lx.lionixCRM.getConfigOption('all');
            }
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
