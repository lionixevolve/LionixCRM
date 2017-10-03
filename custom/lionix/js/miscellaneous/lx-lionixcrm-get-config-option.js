// function definitions section
lx.lionixCRM.getConfigOption = function(option) {
    return new Promise(function(resolve, reject) {
        var method = "getLionixCRMConfigOption";
        var data = {
            "method": method,
            "option": option
        };
        $.ajax({
            // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
            beforeSend: function(jqXHR, settings) {
                console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-lionixcrm-get-config-option.js', 'lx.lionixCRM.getConfigOption()', 'ajax beforeSend');
                console.log("*** start ***");
                console.log("beforeSend callback:", settings.url);
                console.groupEnd();
            }, //end beforeSend
            url: 'lxajax.php',
            type: 'POST',
            data: data,
            // success is a function to be called if the request succeeds.
            success: function(data, status, jqXHR) {
                console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-lionixcrm-get-config-option.js', 'lx.lionixCRM.getConfigOption()', 'ajax success');
                console.log("success callback:", status);
                console.log("data:", data);
                if (data == '') {
                    console.log("LionixCRM option " + option + " not found.")
                } else {
                    if (option == 'all') {
                        lx.lionixCRM.config = JSON.parse(data);
                    } else {
                        lx.lionixCRM.config[option] = JSON.parse(data);
                    }
                    console.log("LionixCRM option (" + option + ") updated.")
                }
                console.groupEnd();
                resolve();
            }, // end success
            // error is a function to be called if the request fails.
            error: function(jqXHR, status, error) {
                console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-lionixcrm-get-config-option.js', 'lx.lionixCRM.getConfigOption()', 'ajax error');
                console.log("error callback:", status);
                console.log("Function lx.lionixCRM.getConfigOption error:", error);
                console.groupEnd();
                reject();
            }, // end error
            // complete is a function to be called when the request finishes (after success and error callbacks are executed).
            // complete: function(jqXHR, status) {
            // }, // end complete
            datatype: "text"
        }); // end ajax
    });

} // end function
