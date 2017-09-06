// This file containts all modules bussines logic
//Self-Invoking Anonymous Function Notation
// !function(){}(); // easy to read, the result is unimportant.
// (function(){})(); // like above but more parens.
// (function(){}()); // Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html then search for invoked immediately
!function() {
    // create an observer instance
    // https://developer.mozilla.org/en/docs/Web/API/MutationObserver
    if ($("#LionixCRM-environment").length == 0) {
        console.groupCollapsed("Check environment '%s' '%s' '%s' '%s'", 'all modules', 'lx-check-crm-environment.js', '!function()', 'initial');
        console.log('Running getLionixCRMConfigOptionEnvironment() function');
        console.groupEnd();
        getLionixCRMConfigOptionEnvironment();
    }
    var observer = new MutationObserver(function(mutations) {
        if (mutations) {
            if ($("#LionixCRM-environment").length == 0) {
                console.groupCollapsed("Check environment '%s' '%s' '%s' '%s'", 'all modules', 'lx-check-crm-environment.js', '!function()', 'observer');
                console.log('Running getLionixCRMConfigOptionEnvironment() function');
                console.groupEnd();
                getLionixCRMConfigOptionEnvironment();
            }
            // if needed only once, you can stop observing with observer.disconnect();
            observer.disconnect();
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
function getLionixCRMConfigOptionEnvironment(opportunityId, currentValue, accountId) {
    if ($("#LionixCRM-environment").length == 0) {
        var method = "getLionixCRMConfigOption";
        var data = "method=" + method;
        data += "&option=" + "environment";
        // data += "&opportunityId=" + opportunityId;
        // data += "&currentValue=" + currentValue;
        // data += "&accountId=" + accountId;
        //data += "&use_adodb5="+"1";
        $.ajax({
            // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
            beforeSend: function(jqXHR, settings) {
                console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-check-crm-environment.js', 'getLionixCRMConfigOptionEnvironment()', 'ajax beforeSend');
                console.log("*** start ***");
                console.log("beforeSend callback:", settings.url);
                console.groupEnd();
            }, //end beforeSend
            url: 'lxajax.php',
            type: 'GET',
            data: data,
            // success is a function to be called if the request succeeds.
            success: function(data, status, jqXHR) {
                console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-check-crm-environment.js', 'getLionixCRMConfigOptionEnvironment()', 'ajax success');
                console.log("success callback:", status);
                console.log("data:", data);
                if ($("#LionixCRM-environment").length == 0) {
                    if (data.toLowerCase() === 'testing') {
                        let pbclass = "progress-bar bg-info";
                        let probability = 100;
                        let text_bar = "Testing Environment"
                        $('#content').before('<div id="LionixCRM-environment" data-enviroment="Testing Environment" class="progresslx"> <div class="' + pbclass + '" role="progressbar" aria-valuenow="' + probability + '" aria-valuemin="0" aria-valuemax="100" style="min-width: 10%; width: ' + probability + '%; height: 30px; font-size:1.9em; line-height: 2.8rem;">' + text_bar + '</div></div>');
                    } else {
                        $('#content').before('<div id="LionixCRM-environment" data-enviroment="Production Environment" />');
                    }
                    console.log("LionixCRM-environment added.");
                }else{
                    console.log("LionixCRM-environment exists.");
                }
                console.groupEnd();
            }, // end success
            // error is a function to be called if the request fails.
            error: function(jqXHR, status, error) {
                console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-check-crm-environment.js', 'getLionixCRMConfigOptionEnvironment()', 'ajax error');
                console.log("error callback:", status);
                console.log("Function getLionixCRMConfigOptionEnvironment error:", error);
                console.groupEnd();
            }, // end error
            // complete is a function to be called when the request finishes (after success and error callbacks are executed).
            // complete: function(jqXHR, status) {
            // }, // end complete
            datatype: "text"
        }); // end ajax
    } // end first if
} // end function
//eof
