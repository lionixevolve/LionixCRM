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
                console.warn("Bussines logic '%s' '%s' '%s' option '%s' '%s'", 'all modules', 'lx-lionixcrm-get-config-option.js', 'lx.lionixCRM.getConfigOption()', option, 'ajax beforeSend');
                console.warn("beforeSend callback:", settings.url);
            }, //end beforeSend
            url: 'lxajax.php',
            type: 'POST',
            data: data,
            // success is a function to be called if the request succeeds.
            success: function(data, status, jqXHR) {
                console.warn("Bussines logic '%s' '%s' '%s' option '%s' '%s'", 'all modules', 'lx-lionixcrm-get-config-option.js', 'lx.lionixCRM.getConfigOption()', option, 'ajax success');
                console.warn("success callback:", status);
                console.warn("data:", data);
                if (data == '') {
                    console.warn("LionixCRM option " + option + " not found.")
                } else {
                    data = JSON.parse(data);
                    if (option == 'all') {
                        lx.lionixCRM.config = data;
                    } else {
                        lx.lionixCRM.config[option] = data;
                    }
                    console.warn("LionixCRM option (" + option + ") updated.")
                }

                resolve(data);
            }, // end success
            // error is a function to be called if the request fails.
            error: function(jqXHR, status, error) {
                console.error("Bussines logic '%s' '%s' '%s' option '%s' '%s'", 'all modules', 'lx-lionixcrm-get-config-option.js', 'lx.lionixCRM.getConfigOption()', option, 'ajax error');
                console.error("error callback:", status);
                console.error("Function lx.lionixCRM.getConfigOption error:", error);
                reject(error);
            }, // end error
            // complete is a function to be called when the request finishes (after success and error callbacks are executed).
            // complete: function(jqXHR, status) {
            // }, // end complete
            datatype: "text"
        }); // end ajax
    });
} // end function
