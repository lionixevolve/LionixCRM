// function definitions section
lx.lionixCRM.runSpoonFile = function(file,answer) {
    return new Promise(function(resolve, reject) {
        var method = "runSpoonFile";
        var data = {
            "method": method,
            "file": file,
            "answer": answer
        };
        $.ajax({
            // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
            beforeSend: function(jqXHR, settings) {
                console.warn("Bussines logic '%s' '%s' '%s' file '%s' '%s'", 'all modules', 'lx-run-spoon-file.js', 'lx.lionixCRM.runSpoonFile()', file, 'ajax beforeSend');
                console.warn("beforeSend callback:", settings.url);
            }, //end beforeSend
            url: 'lxajax.php',
            type: 'POST',
            data: data,
            // success is a function to be called if the request succeeds.
            success: function(data, status, jqXHR) {
                console.warn("Bussines logic '%s' '%s' '%s' file '%s' '%s'", 'all modules', 'lx-run-spoon-file.js', 'lx.lionixCRM.runSpoonFile()', file, 'ajax success');
                console.warn("success callback:", status);
                console.warn("data:", data);
                if (data == '') {
                    console.warn("Spoon file " + file + " not found.")
                } else {
                    data = JSON.parse(data);
                    console.warn("Spoon file (" + file + ") executed.")
                    $("#preview").html('');
                    alert(data);
                }
                resolve(data);
            }, // end success
            // error is a function to be called if the request fails.
            error: function(jqXHR, status, error) {
                console.error("Bussines logic '%s' '%s' '%s' file '%s' '%s'", 'all modules', 'lx-run-spoon-file.js', 'lx.lionixCRM.runSpoonFile()', file, 'ajax error');
                console.error("error callback:", status);
                console.error("Function lx.lionixCRM.runSpoonFile error:", error);
                $("#preview").html('');
                reject(error);
            }, // end error
            // complete is a function to be called when the request finishes (after success and error callbacks are executed).
            // complete: function(jqXHR, status) {
            // }, // end complete
            datatype: "text"
        }); // end ajax
    });
} // end function
