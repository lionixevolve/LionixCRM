lx.lionixCRM.getContactDuplicates = function(searchObject) {
    so = searchObject;
    return new Promise(function(resolve, reject) {
        var method = "getContactDuplicates";
        var data = {
            "method": method,
            "first_name": so.first_name,
            "last_name": so.last_name,
            "lastname2_c": so.lastname2_c,
            "email_address": so.email_address,
            "cedula_c": so.cedula_c
        };
        $.ajax({
            // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
            beforeSend: function(jqXHR, settings) {
                console.log("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-get-contact-duplicates.js', 'lx.lionixcrm.getContactDuplicates', 'ajax beforeSend');
                console.log("*** start ***");
                console.log("beforeSend callback:", settings.url);
            }, //end beforeSend
            url: 'lxajax.php',
            type: 'POST',
            data: data,
            // success is a function to be called if the request succeeds.
            success: function(data, status, jqXHR) {
                console.log("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-get-contact-duplicates.js', 'lx.lionixcrm.getContactDuplicates', 'ajax success');
                console.log("success callback:", status);
                console.log("data:", data);
                if (data == '') {
                    console.log("Not duplicates found.")
                } else {
                    data = JSON.parse(data);
                    console.log("Contact duplicates found.")
                }
                resolve({"fieldname": so.fieldname, "data": data});
            }, // end success
            // error is a function to be called if the request fails.
            error: function(jqXHR, status, error) {
                console.log("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-get-contact-duplicates.js', 'lx.lionixcrm.getContactDuplicates', 'ajax error');
                console.log("error callback:", status);
                console.log("Function lx.lionixCRM.getConfigOption error:", error);
                reject(error);
            }, // end error
            // complete is a function to be called when the request finishes (after success and error callbacks are executed).
            // complete: function(jqXHR, status) {
            // },  end complete
            datatype: "text"
        }); // end ajax
    });
}
