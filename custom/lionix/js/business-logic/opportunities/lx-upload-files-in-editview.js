// This file containts opportunities bussines logic
// function definitions section
function getUploadFileButton(element) {
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
        getUploadFileTemplate(element);
        $('#show_' + element.field_name + '_loader').prop('disabled', true);
        $('#' + element.field_name).prop('disabled', true);
        // $('#' + element.field_name).toggle(); // to view field
    });
}

function getUploadFileTemplate(element) {
    var method = "uploadFileTemplate";
    data = {
        "method": method,
        "field_name": element.field_name,
        "label": element.button_label,
        "ok_message": element.ok_message
    }
    $.ajax({
        // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
        beforeSend: function(jqXHR, settings) {
            console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'getUploadFileTemplate()', 'ajax beforeSend');
            console.log("*** start ***");
            console.log("beforeSend callback:", settings.url);
            console.log("beforeSend callback:", settings.data);
            console.groupEnd();
        }, //end beforeSend
        url: "lxajax.php",
        type: 'POST',
        data: data,
        // success is a function to be called if the request succeeds.
        success: function(data, status, jqXHR) {
            console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'getUploadFileTemplate()', 'ajax success');
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
                        console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'getUploadFileTemplate()', 'ajaxForm beforeSend');
                        console.log("*** start ***");
                        console.log("beforeSend callback:", settings.url);
                        // file to be modified
                        settings.url += "&opportunity_id=" + element.opportunity_id;
                        console.log("url modified:", settings.url);
                        console.groupEnd();
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
                        console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'getUploadFileTemplate()', 'ajaxForm success');
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
                        console.groupEnd();
                    }, // success
                    // error is a function to be called if the request fails.
                    error: function(jqXHR, status, error) {
                        console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'getUploadFileTemplate()', 'ajaxForm error');
                        console.log("error callback:", status);
                        console.log("Function getUploadFileTemplate error:", error);
                        console.groupEnd();
                    }, // end error
                    // complete is a function to be called when the request finishes (after success and error callbacks are executed).
                    complete: function(jqXHR, status) {
                        console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'getUploadFileTemplate()', 'ajaxForm complete');
                        console.log("complete callback:", status);
                        console.log("*** finish ***");
                        console.groupEnd();
                    }, // end complete
                    datatype: "text"
                }).submit();
            });
            console.groupEnd();
        }, // end success
        // error is a function to be called if the request fails.
        error: function(jqXHR, status, error) {
            console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'getUploadFileTemplate()', 'ajax error');
            console.log("error callback:", status);
            console.log("Function getUploadFileTemplate error:", error);
            console.groupEnd();
        }, // end error
        // complete is a function to be called when the request finishes (after success and error callbacks are executed).
        complete: function(jqXHR, status) {
            console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', 'getUploadFileTemplate()', 'ajax complete');
            console.log("complete callback:", status);
            console.log("*** finish ***");
            console.groupEnd();
        }, // end complete
        datatype: "text"
    })/*Fin del Ajax*/
} //getUploadFileTemplate()

//Self-Invoking Anonymous Function Notation
// !function(){}(); // easy to read, the result is unimportant.
// (function(){})(); // like above but more parens.
// (function(){}()); // Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html then search for invoked immediately
!function() {
    // On opportunities module
    var crmEditView = document.forms['EditView'];
    var opportunity_id = 'opportunity_id_not_defined';
    if (crmEditView) {
        opportunity_id = crmEditView.record.value;
    }
    // var text_fields_to_upload_file_list = [
    //     // {
    //     //     "field_name": "text_field_name_255_chars_long",  // 'text_field_name_255_chars_long', //meter en un arreglo los campos para que los leas el javascript y el logic en el php
    //     //     "button_label": "Generalmente es una *Orden de compra*?",
    //     //     "ok_message": "*Orden de compra* agregada correctamente."
    //     // }
    // ];
    var text_fields_to_upload_file_list = [
        {
            "field_name": "fpurchaseorder_c",  // 'text_field_name_255_chars_long', //meter en un arreglo los campos para que los leas el javascript y el logic en el php
            "button_label": "Orden de compra",
            "ok_message": "Orden de compra agregada correctamente."
        }
    ];
    // this appends opportunity_id to each element
    text_fields_to_upload_file_list.forEach(function(element) {
        element.opportunity_id = opportunity_id;
    });
    if (crmEditView) {
        console.groupCollapsed("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-upload-files-in-detailview.js', '!function()', 'initial');
        console.log("Loading Lionix code on EditView on module:", crmEditView.module.value);
        console.groupEnd();
        if (crmEditView.module.value == 'Opportunities') {
            text_fields_to_upload_file_list.forEach(function(element) {
                getUploadFileButton(element);
            });
        }
    }

    // On other modules
    // create an observer instance
    // https://developer.mozilla.org/en/docs/Web/API/MutationObserver
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type == "attributes") {
                if (mutation.target.nodeName == "FORM") {
                    //when found do your code
                    text_fields_to_upload_file_list.forEach(function(element) {
                        if ($('#show_' + element.field_name + '_loader').length == 0) {
                            getUploadFileButton(element);
                        }
                    });
                    // if needed only once, you can stop observing with observer.disconnect();
                    // observer.disconnect();
                }
            }
        });
    });
    // Observer target
    var target = document.querySelector('#content');
    // configuration of the observer:
    // NOTE: At the very least, childList, attributes, or characterData must be set to true. Otherwise, "An invalid or illegal string was specified" error is thrown.
    var config = {
        attributes: true,
        childList: true,
        characterData: true,
        subtree: true
    };
    // pass in the target node, as well as the observer options
    observer.observe(target, config);
    // end observer
}();
//eof
