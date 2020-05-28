// function definitions section
lx.lionixCRM.getTSECRData = function(searchObject) {
    return new Promise(function(resolve, reject) {
        // var method = "getContactDuplicates";
        var method = "getTSEData";
        var data = {
            "method": method,
            "cedula_c": searchObject.cedula_c
        };
        $.ajax({
            // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
            beforeSend: function(jqXHR, settings) {
                console.warn("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-get-tse-cr-data.js', 'lx.lionixCRM.getTSECRData', 'ajax beforeSend');
                console.warn("beforeSend callback:", settings.url);
            }, //end beforeSend
            url: 'lxajax.php',
            type: 'POST',
            data: data,
            // success is a function to be called if the request succeeds.
            success: function(data, status, jqXHR) {
                console.warn("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-get-tse-cr-data.js', 'lx.lionixCRM.getTSECRData', 'ajax success');
                console.warn("success callback:", status);
                console.warn("data:", data);
                data = JSON.parse(data);
                if (data.length == 0) {
                    console.warn("TSE Costa Rica entry NOT FOUND:", searchObject.cedula_c);
                    data = [
                        {
                            cedula_c: searchObject.cedula_c,
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
                            console.warn('Focus on ' + searchObject.focusfieldname + 'after toastr.');
                            $('#' + searchObject.focusfieldname).focus();
                        }
                    });
                } else {
                    console.warn("TSE Costa Rica entry found:", data.length, data);
                }
                resolve({"focusfieldname": searchObject.focusfieldname, "data": data});
            }, // end success
            // error is a function to be called if the request fails.
            error: function(jqXHR, status, error) {
                console.error("Bussines logic '%s' '%s' '%s' '%s'", 'all modules', 'lx-get-tse-cr-data.js', 'lx.lionixCRM.getTSECRData', 'ajax error');
                console.error("error callback:", status);
                console.error("Function lx.lionixCRM.getConfigOption error:", error);
                reject(error);
            }, // end error
            // complete is a function to be called when the request finishes (after success and error callbacks are executed).
            // complete: function(jqXHR, status) {
            // },  end complete
            datatype: "text"
        }); // end ajax
    });
} // end function
