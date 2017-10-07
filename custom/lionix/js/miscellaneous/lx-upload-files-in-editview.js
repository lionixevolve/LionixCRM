// This file containts opportunities bussines logic
// function definitions section
lx.upload.getFileButton = function(element) {
    console.log('Rendering button from %s', element.field_name);
    if ($('#' + element.field_name).length == 0) {
        console.log('Field %s not present in EditView', element.field_name);
    } else {
        data = $('#' + element.field_name).val();
        if (data == '') {
            data = {
                "note_link": '',
                "note_name": ''
            };
        } else {
            data = JSON.parse(data);
        }
        $('#' + element.field_name).parent().append('<div id="' + element.field_name + '-file-name"><a href="' + data.note_link.replace('index.php', location.origin + location.pathname) + '">' + data.note_name + '</a></div></br>');
        $('#' + element.field_name).parent().append('<button id="show_' + element.field_name + '_loader" type="button" class="btn btn-primary btn-sm">+ ' + element.button_label + '</button>');
        $('#' + element.field_name).parent().append('<div id="' + element.field_name + '_loader_hook" style="position: relative;"/>');
        $('#' + element.field_name).prop('readonly', 'readonly');
        $('#' + element.field_name).hide();
        $('#show_' + element.field_name + '_loader').click(function() {
            lx.upload.getFileTemplate(element);
            $('#show_' + element.field_name + '_loader').prop('disabled', true);
            $('#' + element.field_name).prop('disabled', true);
            // $('#' + element.field_name).toggle(); // to view field
        });
        console.log('Field ' + element.field_name + ' rendered.')
    }
}

lx.upload.getFileTemplate = function(element) {
    var method = "uploadFileTemplate";
    data = {
        "method": method,
        "field_name": element.field_name,
        "label": element.button_label,
        "ok_message": element.ok_message,
        "module_name": element.module_name,
        "record_id": element.record_id
    }
    $.ajax({
        // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
        beforeSend: function(jqXHR, settings) {
            console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'lx.upload.getFileTemplate()', 'ajax beforeSend');
            console.log("*** start ***");
            console.log("beforeSend callback:", settings.url);
            console.log("beforeSend callback:", settings.data);
        }, //end beforeSend
        url: "lxajax.php",
        type: 'POST',
        data: data,
        // success is a function to be called if the request succeeds.
        success: function(data, status, jqXHR) {
            console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'lx.upload.getFileTemplate()', 'ajax success');
            console.log("success callback:", status);
            console.log("data:", data);
            $('#floating-div-for-upload-' + element.field_name + '-file').remove();
            $('#' + element.field_name + '_loader_hook').html('<div id="floating-div-for-upload-' + element.field_name + '-file" style="display:none" />');
            $replaceMe = $('<div id="lx-replace-me" />');
            $('#floating-div-for-upload-' + element.field_name + '-file').html($replaceMe).addClass("lx-floating-div").draggable({
                cursor: "crosshair",
                cursorAt: {
                    top: 56,
                    left: 56
                }
            })
            // populate dropdown with QRN
            $('#lx-replace-me').replaceWith(data);
            $('#floating-div-for-upload-' + element.field_name + '-file').show("slow");
            // buttons functionality
            $('#exit-' + element.field_name + '-btn').click(function() {
                $('#floating-div-for-upload-' + element.field_name + '-file').hide("slow");
                $('#show_' + element.field_name + '_loader').prop('disabled', false);
                $('#' + element.field_name).prop('disabled', false);
                // $('#' + element.field_name).toggle(); // to view field
            });
            $('#upload-' + element.field_name + '-btn').click(function() {
                $('#' + element.field_name + '-file').hide();
                $('#upload-' + element.field_name + '-btn').hide();
                $('#exit-' + element.field_name + '-btn').hide();
                $('#preview_' + element.field_name).html('');
                $('#preview_' + element.field_name).html('<img src="custom/lionix/img/loading.gif" alt="Uploading..." style="border-radius: 50%;" />');
                $('<div class="lx-progress"><div class="lx-bar"></div ><div class="lx-percent">0%</div ></div>').prependTo('#preview_' + element.field_name);
                $('#' + element.field_name + '-form').ajaxForm({
                    beforeSend: function(jqXHR, settings) {
                        console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'lx.upload.getFileTemplate()', 'ajaxForm beforeSend');
                        console.log("*** start ***");
                        console.log("beforeSend callback:", settings.url);
                        // file to be modified
                        settings.url += "&opportunity_id=" + element.opportunity_id;
                        console.log("url modified:", settings.url);
                    }, //end beforeSend
                    // target: '#preview_fieldname', //It's not required 'cause #preview_fieldname is been handled on success
                    // Progress bar while uploading file...
                    uploadProgress: function(event, position, total, percentComplete) {
                        $('#floating-div-for-upload-' + element.field_name + '-file').css({"height": "260px"});
                        var percentVal = percentComplete + '%';
                        $('.lx-bar').width(percentVal)
                        $('.lx-percent').html(percentVal);
                    },
                    // success is a function to be called if the request succeeds.
                    success: function(data, status, jqXHR) {
                        console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'lx.upload.getFileTemplate()', 'ajaxForm success');
                        console.log("success callback:", status);
                        console.log("data:", data);
                        data = JSON.parse(data);
                        $('#preview_' + element.field_name).text(data.message);
                        $('#floating-div-for-upload-' + element.field_name + '-file').css({"height": "300px"});
                        if (data.ok) {
                            jsonToStore = {
                                "note_name": data.document_name,
                                "note_id": data.document_id,
                                "note_link": data.document_file
                            }
                            $('#' + element.field_name).val(JSON.stringify(jsonToStore));
                            $('#' + element.field_name + '-file-name').html('<a href="' + data.document_file.replace('index.php', location.origin + location.pathname) + '">' + data.document_name + '</a>');
                            $('#floating-div-for-upload-' + element.field_name + '-file').css({"width": "235px", "height": "200px"});
                            $('#' + element.field_name + '-title').hide();
                            $('#' + element.field_name + '-form').hide();
                            var percentVal = '100%';
                            $('.lx-bar').width(percentVal);
                            $('.lx-percent').html(percentVal);
                            $('#exit-' + element.field_name + '-btn').show();
                        } else {
                            $('.lx-progress').hide();
                            $('#' + element.field_name + '-file').show();
                            $('#upload-' + element.field_name + '-btn').show();
                            $('#exit-' + element.field_name + '-btn').show();
                        }

                    }, // success
                    // error is a function to be called if the request fails.
                    error: function(jqXHR, status, error) {
                        console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'lx.upload.getFileTemplate()', 'ajaxForm error');
                        console.log("error callback:", status);
                        console.log("Function lx.upload.getFileTemplate error:", error);
                    }, // end error
                    // complete is a function to be called when the request finishes (after success and error callbacks are executed).
                    complete: function(jqXHR, status) {
                        console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'lx.upload.getFileTemplate()', 'ajaxForm complete');
                        console.log("complete callback:", status);
                        console.log("*** finish ***");
                    }, // end complete
                    datatype: "text"
                }).submit();
            });

        }, // end success
        // error is a function to be called if the request fails.
        error: function(jqXHR, status, error) {
            console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'lx.upload.getFileTemplate()', 'ajax error');
            console.log("error callback:", status);
            console.log("Function lx.upload.getFileTemplate error:", error);
        }, // end error
        // complete is a function to be called when the request finishes (after success and error callbacks are executed).
        complete: function(jqXHR, status) {
            console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'lx.upload.getFileTemplate()', 'ajax complete');
            console.log("complete callback:", status);
            console.log("*** finish ***");
        }, // end complete
        datatype: "text"
    })/*Fin del Ajax*/
} //lx.upload.getFileTemplate()

lx.upload.getFileFields = function() {
    var execute = false;
    var crmEditView = document.forms['EditView'];
    if (crmEditView) {
        module_name = crmEditView.module.value.toLowerCase();
        switch (module_name) {
            case 'accounts':
            case 'contacts':
            case 'opportunities':
                if (lx.lionixCRM.config.modules[module_name].upload_files_fields != undefined) {
                    text_fields_to_upload_fields_list = lx.lionixCRM.config.modules[module_name].upload_files_fields;
                    execute = true;
                }
                break;
        }
        if (execute) {
            console.log('Rendering upload files fields for ' + crmEditView.module.value + ' module...')
            text_fields_to_upload_fields_list.forEach(function(element) {
                element.module_name = crmEditView.module.value;
                element.record_id = crmEditView.record.value;
            });
            text_fields_to_upload_fields_list.forEach(function(element) {
                if ($('#show_' + element.field_name + '_loader').length == 0) {
                    lx.upload.getFileButton(element);
                }
            });
        }
    }
} // end function

//Self-Invoking Anonymous Function Notation
// !function(){}(); // easy to read, the result is unimportant.
// (function(){})(); // like above but more parens.
// (function(){}()); // Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html then search for invoked immediately
!function() {
    // On other modules
    // create an observer instance
    // https://developer.mozilla.org/en/docs/Web/API/MutationObserver
    var observer = new MutationObserver(function(mutations) {
        if (mutations) {
            var crmEditView = document.forms['EditView'];
            if (crmEditView) {
                console.log("Upload files in EditView '%s' '%s' '%s' '%s'", 'all modules', 'lx-upload-files-in-editview.js', '!function()', 'observer');
                console.log('Running lx.upload.getFileFields() function');
                lx.lionixCRM.getConfigOption('modules').then(function() {
                    lx.upload.getFileFields();
                });
            }
            // if needed only once, you can stop observing with observer.disconnect();
            // observer.disconnect();
        }
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
//eof
