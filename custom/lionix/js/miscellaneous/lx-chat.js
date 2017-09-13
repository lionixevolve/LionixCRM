// This file containts lxchat bussines logic
//Self-Invoking Anonymous Function Notation
// !function(){}(); // easy to read, the result is unimportant.
// (function(){})(); // like above but more parens.
// (function(){}()); // Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html then search for invoked immediately

// function definitions section
window.lxchatSetData = function(newMessage) {
    var currentUser = $(".user_label:eq(0)").text().trim();

    /*Today's date splitted*/
    var d = new Date();
    var curr_date = d.getDate();
    var curr_month = d.getMonth() + 1;
    var curr_year = d.getFullYear();

    if (newMessage != '') {
        var newMessage = "[" + "%2B" + "] " + " [" + curr_date + " - " + curr_month + " - " + curr_year + "]  " + currentUser + " : " + newMessage + " [" + "%2B" + "]\n\n";
    }

    var currentForm = document.forms['DetailView'];
    if (!currentForm) {
        currentForm = document.forms['EditView'];
    }
    var record_id = currentForm.record.value;
    var module_name = currentForm.module.value;

    var data = "method=" + "lxChat" + "&chat_c=" + newMessage + "&record_id=" + record_id + "&module=" + module_name + "&array_position=" + lxchat_array_position + "&save=" + 1;
    $.ajax({
        // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
        beforeSend: function(jqXHR, settings) {
            console.groupCollapsed("LxChat Logic '%s' '%s' '%s' '%s'", module, 'lx-chat.js', 'lxchatSetData()', 'ajax beforeSend');
            console.log("*** start ***");
            console.log("beforeSend callback:", settings.url);
            console.groupEnd();
        },
        url: 'lxajax.php',
        type: 'POST',
        data: data,
        // success is a function to be called if the request succeeds.
        success: function(data, status, jqXHR) {
            console.groupCollapsed("LxChat logic '%s' '%s' '%s' '%s'", module, 'lx-chat.js', 'lxchatSetData()', 'ajax success');
            console.log("success callback:", status);
            console.log("data:", data);
            $("#lxchatnewmsg").val('');
            lxchatGetData();
            console.groupEnd();
        },
        // error is a function to be called if the request fails.
        error: function(jqXHR, status, error) {
            console.groupCollapsed("LxChat logic '%s' '%s' '%s' '%s'", module, 'lx-chat.js', 'lxchatSetData()', 'ajax error');
            console.log("error callback:", status);
            console.log("Function lxchatSetData error:", error);
            console.groupEnd();
        }, // end error
        // complete is a function to be called when the request finishes (after success and error callbacks are executed).
        // complete: function(jqXHR, status) {
        // },
        datatype: "text"
    })
}

window.lxchatGetData = function() {
    var currentForm = document.forms['DetailView'];
    if (!currentForm) {
        currentForm = document.forms['EditView'];
    }
    var record_id = currentForm.record.value;
    var module_name = currentForm.module.value;
    var data = "method=" + "lxChat" + "&record_id=" + record_id + "&module=" + module_name + "&array_position=" + lxchat_array_position;
    $.ajax({
        // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
        beforeSend: function(jqXHR, settings) {
            console.groupCollapsed("LxChat Logic '%s' '%s' '%s' '%s'", module, 'lx-chat.js', 'lxchatGetData()', 'ajax beforeSend');
            console.log("*** start ***");
            console.log("beforeSend callback:", settings.url);
            console.groupEnd();
        },
        url: 'lxajax.php',
        type: 'GET',
        data: data,
        // success is a function to be called if the request succeeds.
        success: function(data, status, jqXHR) {
            console.groupCollapsed("LxChat logic '%s' '%s' '%s' '%s'", module, 'lx-chat.js', 'lxchatGetData()', 'ajax success');
            console.log("success callback:", status);
            console.log("data:", data);
            $("textarea#" + lxchatfield).val(data);
            $('#lxchatcontent').val(data).trigger('autosize');
            $("#lxchatSave").removeAttr("disabled");
            console.groupEnd();
        },
        // error is a function to be called if the request fails.
        error: function(jqXHR, status, error) {
            console.groupCollapsed("LxChat logic '%s' '%s' '%s' '%s'", module, 'lx-chat.js', 'lxchatGetData()', 'ajax error');
            console.log("error callback:", status);
            console.log("Function lxchatGetData error:", error);
            console.groupEnd();
        }, // end error
        // complete is a function to be called when the request finishes (after success and error callbacks are executed).
        complete: function(jqXHR, status) {
            var textarea = document.getElementById('lxchatcontent');
            textarea.scrollTop = textarea.scrollHeight;
        },
        datatype: "text"
    })
}

window.lxchatRender = function(lxchatfield) {
    if (!$("#lxchat").length) {
        var currentUser = $(".user_label:eq(0)").text().trim();
        //Current lxchatfield text
        lxchatfieldtext = $('#' + lxchatfield).text();
        //Current crm field must be hide
        $('#' + lxchatfield).hide();
        //lxchat div added
        $('<div id="lxchat"><center>CHAT</center></div>').insertAfter('#' + lxchatfield);
        $('#lxchat').attr('style', 'position:relative'); //aca no estaba esta línea
        $('#lxchat').attr('style', 'width: 400px; border: 3px solid #6495ED;border-radius:5px;text-align:left;background-color: #BCD2EE;');
        $('#lxchat').append('<textarea id="lxchatcontent" cols="80" rows="6" readonly="readonly" style="width: 100%; overflow: scroll; background-color: #F6FAFD;">' + lxchatfieldtext + '</textarea>');
        if (lxchatfieldtext.length === 1) {
            $('#lxchat').attr('style', 'width: 400px; border: 3px solid #6495ED;border-radius:5px;text-align:left;background-color: #BCD2EE;');
            $('#lxchatcontent').attr('style', 'width: 100%; height: 27px;background-color: #F6FAFD;');
        } else {
            $('#lxchatcontent').attr('style', 'width: 100%; height: 200px;background-color: #F6FAFD;');
        }
        //Textarea for new messages added
        $('<br><textarea id="lxchatnewmsg" tabindex="0" title="" cols="80" rows="6" style="width: 400px;height: 90px;background-color: #F6FAFD;"></textarea>').insertAfter('#lxchat');
        //jeditable plugin on new messages textarea
        $("#lxchatnewmsg").editable(function() {});
        //Save new messages button added
        $('<br><input id="lxchatSave" type="button" value="¿Quieres compartir alguna novedad, '+currentUser+'?" />').insertAfter('#lxchatnewmsg');
        $(document).on("click", "#lxchatSave", function(event) {
            $("#lxchatSave").attr("disabled", true);
            lxchatSetData($("#lxchatnewmsg").val());
        });
    }
}

window.lxchatFindFieldToRender = function() {
    var currentForm = document.forms['DetailView'];
    if (!currentForm) {
        currentForm = document.forms['EditView'];
    }
    var record_id = currentForm.record.value;
    if (!record_id) {
        console.log("lxchat doesn't render when record_id isn't present");
    } else {
        var lxajaxdata = "method=" + "lxChatConfigOverrideField";
        $.ajax({
            // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
            beforeSend: function(jqXHR, settings) {
                console.groupCollapsed("LxChat Logic '%s' '%s' '%s' '%s'", module, 'lx-chat.js', 'lxchatFindFieldToRender()', 'ajax beforeSend');
                console.log("*** start ***");
                console.log("beforeSend callback:", settings.url);
                console.groupEnd();
            },
            url: 'lxajax.php',
            type: 'GET',
            data: lxajaxdata,
            // success is a function to be called if the request succeeds.
            success: function(data, status, jqXHR) {
                console.groupCollapsed("LxChat logic '%s' '%s' '%s' '%s'", module, 'lx-chat.js', 'lxchatFindFieldToRender()', 'ajax success');
                console.log("success callback:", status);
                console.log("data:", data);
                fieldsArray = JSON.parse(data);
                for (var i = 0; i < fieldsArray.length; i++) {
                    if ($(document).find("#" + fieldsArray[i]).length) {
                        lxchatRender(fieldsArray[i]);
                        lxchatfield = fieldsArray[i];
                        lxchat_array_position = i;
                    }
                }
            },
        // error is a function to be called if the request fails.
            error: function(jqXHR, status, error) {
                console.groupCollapsed("LxChat logic '%s' '%s' '%s' '%s'", module, 'lx-chat.js', 'lxchatFindFieldToRender()', 'ajax error');
                console.log("error callback:", status);
                console.log("Function lxchatFindFieldToRender error:", error);
                console.groupEnd();
            }, // end error
            // complete is a function to be called when the request finishes (after success and error callbacks are executed).
            complete: function(jqXHR, status) {
                var textarea = document.getElementById('lxchatcontent');
                if(textarea){
                    textarea.scrollTop = textarea.scrollHeight;
                }
            },
            datatype: "text"
        })
    }
}

// Observers definitions
! function() {
    if ($("#edit_button").length || $("#SAVE").length || $("#SAVE_HEADER").length) {
        // now it ensures that lxchat isn't already present
        if (!$("#lxchat").length) {
            lxchatFindFieldToRender();
        }
    }
    // On other modules
    // create an observer instance
    // https://developer.mozilla.org/en/docs/Web/API/MutationObserver
    var observer = new MutationObserver(function(mutations) {

        // If a specific mutation is need iterate over the array
        // mutations.forEach(function(mutation) {
        // There are 3 types attributes, childList and characterData
        // if (mutation.type == "attributes") {
        // if (mutation.target.nodeName == "DIV" && mutation.target.id == 'tabcontent0') {
        /*when found do your code*/
        // console.log(mutation);
        //}
        //}
        // });

        // If any change will trigger the effect check only if the array exists
        // if (mutations) { /*your code*/ }
        if (mutations) {
            $(document).ready(function() {
                var lxchatfield = '';
                var lxchat_array_position = '';
                // if #edit_button exists is a detailview, if not, then if a #SAVE or #SAVE_HEADER button exists is a editview
                if ($("#edit_button").length || $("#SAVE").length || $("#SAVE_HEADER").length) {
                    // now it ensures that lxchat isn't already present
                    if (!$("#lxchat").length) {
                        lxchatFindFieldToRender();
                    }
                }
            });
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
        characterData: true,
        subtree: true
    };
    // pass in the target node, as well as the observer options
    observer.observe(target, config);
    // end observer
}();

// eof
