// function definitions section
lx.lionixCRM.getTSECRData = function(searchObject) {
    /*
    // searchObject example
    searchObject = {
        // Infoticos must be on same database server this SuiteCRM instance.
        "focusfieldname": "replace with input textfield id",
        "cedula_c": "replace with cédula to looking for"
    }
    */
    so = searchObject;
    return new Promise(function(resolve, reject) {
        // var method = "getContactDuplicates";
        var method = "getTSEData";
        var data = {
            "method": method,
            "cedula_c": so.cedula_c
        };
        $.ajax({
            // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
            beforeSend: function(jqXHR, settings) {
                console.log("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-get-tse-cr-data.js', 'lx.lionixCRM.getTSECRData', 'ajax beforeSend');
                console.log("beforeSend callback:", settings.url);
            }, //end beforeSend
            url: 'lxajax.php',
            type: 'POST',
            data: data,
            // success is a function to be called if the request succeeds.
            success: function(data, status, jqXHR) {
                console.log("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-get-tse-cr-data.js', 'lx.lionixCRM.getTSECRData', 'ajax success');
                console.log("success callback:", status);
                console.log("data:", data);
                data = JSON.parse(data);
                if (data.length == 0) {
                    console.log("TSE Costa Rica entry NOT FOUND:", so.cedula_c);
                    data = [
                        {
                            cedula_c: so.cedula_c,
                            first_name: "",
                            last_name: "",
                            lastname2_c: "",
                            found: false
                        }
                    ];
                    toastr["warning"]("Cédula no registrada.", "Búsqueda por cédula...", {
                        "positionClass": "toast-bottom-center",
                        "showDuration": "0",
                        "hideDuration": "0",
                        "timeOut": "4000",
                        "extendedTimeOut": "0",
                        "progressBar": true,
                        "onShown": function() {
                            console.log('Focus on ' + so.focusfieldname + 'after toastr.');
                            $('#' + so.focusfieldname).focus();
                        }
                    });
                } else {
                    console.log("TSE Costa Rica entry found:", data.length, data);
                }
                resolve({"focusfieldname": so.focusfieldname, "data": data});
            }, // end success
            // error is a function to be called if the request fails.
            error: function(jqXHR, status, error) {
                console.log("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-get-tse-cr-data.js', 'lx.lionixCRM.getTSECRData', 'ajax error');
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
} // end function
