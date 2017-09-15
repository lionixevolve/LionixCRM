// This file containts lxchat bussines logic
//Self-Invoking Anonymous Function Notation
// !function(){}(); // easy to read, the result is unimportant.
// (function(){})(); // like above but more parens.
// (function(){}()); // Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html then search for invoked immediately

// function definitions section
window.lxchatSaveNewMessage = function(userMessage) {
    // Save button temporary disabled
    $("#lxchatSave").attr("disabled", true);
    if (userMessage === '') {
        toastr["warning"]("¿Quieres compartir alguna novedad? Escribe un mensaje", "Chat - Mensaje vacío", {"positionClass": "toast-bottom-center"});
    } else {
        // currentUser Name
        var currentUser = $(".user_label:eq(0)").text().trim();

        var d = new Date();
        if (!Array.isArray(window.lxchatMessagesArray)) {
            window.lxchatMessagesArray = [];
        }
        window.lxchatMessagesArray.push({
            "id": "1", // falta el ajax para saber el id del usuario actual
            "msg": userMessage,
            "date": d.toISOString()
        });

        newMessage = JSON.stringify(window.lxchatMessagesArray);

        var currentForm = document.forms['DetailView'];
        if (!currentForm) {
            currentForm = document.forms['EditView'];
        }
        var record_id = currentForm.record.value;
        var module_name = currentForm.module.value;

        var data = "method=" +
        "lxChat" +
        "&chat_c=" + newMessage + "&record_id=" + record_id + "&module=" + module_name + "&array_position=" + lxchat_array_position + "&save=" + 1;
        $.ajax({
            // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
            beforeSend: function(jqXHR, settings) {
                console.groupCollapsed("LxChat Logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lxchatSaveNewMessage()', 'ajax beforeSend');
                console.log("*** start ***");
                console.log("beforeSend url:", settings.url);
                console.log("beforeSend data:", settings.data);
                console.groupEnd();
            },
            url: 'lxajax.php',
            type: 'POST',
            data: data,
            // success is a function to be called if the request succeeds.
            success: function(data, status, jqXHR) {
                console.groupCollapsed("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lxchatSaveNewMessage()', 'ajax success');
                console.log("success callback:", status);
                console.log("data:", data);
                $("#lxchatnewmsg").val('');
                $("textarea#" + lxchatfield).val(data);
                console.log(data);
                window.lxchatMessagesArray = JSON.parse(data);
                document.getElementById("lxchatcontent").innerHTML = window.lxchatMessagesArrayToHTML(window.lxchatMessagesArray);
                var h1 = $('#lxchatcontent')[0].scrollHeight,
                    h2 = $('#lxchatcontent').height();
                $('#lxchatcontent').scrollTop(h1 - h2);
                console.groupEnd();
            },
            // error is a function to be called if the request fails.
            error: function(jqXHR, status, error) {
                console.groupCollapsed("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lxchatSaveNewMessage()', 'ajax error');
                console.log("error callback:", status);
                console.log("Function lxchatSaveNewMessage error:", error);
                console.groupEnd();
            }, // end error
            // complete is a function to be called when the request finishes (after success and error callbacks are executed).
            // complete: function(jqXHR, status) {
            // },
            datatype: "text"
        });
    }
    $("#lxchatSave").removeAttr("disabled");
}

window.lxchatRender = function(lxchatfield, lxchat_array_position) {
    if (!$("#lxchat").length) {
        var currentForm = document.forms['DetailView'];
        if (!currentForm) {
            currentForm = document.forms['EditView'];
        }
        var record_id = currentForm.record.value;
        var module_name = currentForm.module.value;

        var data = "method=" +
        "lxChat" +
        "&record_id=" + record_id + "&module=" + module_name + "&array_position=" + lxchat_array_position + "&save=" + 1;
        $.ajax({
            // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
            beforeSend: function(jqXHR, settings) {
                console.groupCollapsed("LxChat Logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lxchatRender()', 'ajax beforeSend');
                console.log("*** start ***");
                console.log("beforeSend url:", settings.url);
                console.log("beforeSend data:", settings.data);
                console.groupEnd();
            },
            url: 'lxajax.php',
            type: 'POST',
            data: data,
            // success is a function to be called if the request succeeds.
            success: function(data, status, jqXHR) {
                console.groupCollapsed("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lxchatRender()', 'ajax success');
                console.log("success callback:", status);
                console.log("data:", data);
                var currentUser = $(".user_label:eq(0)").text().trim();
                //Current lxchatfield text
                window.lxchatMessagesArray = JSON.parse(data);
                lxchatfieldtext = window.lxchatMessagesArrayToHTML(window.lxchatMessagesArray);
                //Current crm field must be hide
                $('#' + lxchatfield).hide();
                //lxchat div added
                $('<div id="lxchat"><center>LionixCRM Smart CHAT</center></div>').insertAfter('#' + lxchatfield);
                $('#lxchat').attr('style', 'position:relative; width: 550px; border: 3px solid #6495ED;border-radius:5px;text-align:left;background-color: #BCD2EE;');
                $('#lxchat').append('<div id="lxchatcontent" style="width: 100%; height: 200px; background-color: #F5F5F5; overflow-y: auto;">' + lxchatfieldtext + '</div>');
                var h1 = $('#lxchatcontent')[0].scrollHeight,
                    h2 = $('#lxchatcontent').height();
                $('#lxchatcontent').scrollTop(h1 - h2);
                //Textarea for new messages added
                $('<br><textarea id="lxchatnewmsg" placeholder="¿Quieres compartir alguna novedad, ' + currentUser.split(" ")[0] + '?" tabindex="0" title="" cols="80" rows="6" style="width: 550px;height: 90px;background-color: #F6FAFD;"></textarea>').insertAfter('#lxchat');
                //Save new messages button added
                $('<br><input id="lxchatSave" type="button" value="enviar mensaje" />').insertAfter('#lxchatnewmsg');
                $(document).on("click", "#lxchatSave", function(event) {
                    lxchatSaveNewMessage($("#lxchatnewmsg").val());
                });
                console.groupEnd();
            },
            // error is a function to be called if the request fails.
            error: function(jqXHR, status, error) {
                console.groupCollapsed("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lxchatRender()', 'ajax error');
                console.log("error callback:", status);
                console.log("Function lxchatRender error:", error);
                console.groupEnd();
            }, // end error
            // complete is a function to be called when the request finishes (after success and error callbacks are executed).
            // complete: function(jqXHR, status) {
            // },
            datatype: "text"
        });

    }
}

window.lxchatFindFieldToRender = function() {
    var currentForm = document.forms['DetailView'];
    if (!currentForm) {
        currentForm = document.forms['EditView'];
    }
    var record_id = currentForm.record.value;
    var module_name = currentForm.module.value;
    var lxajaxdata = "method=" +
    "lxChatGetSmartChatField";
    $.ajax({
        // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
        beforeSend: function(jqXHR, settings) {
            console.groupCollapsed("LxChat Logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lxchatFindFieldToRender()', 'ajax beforeSend');
            console.log("*** start ***");
            console.log("beforeSend callback:", settings.url);
            console.groupEnd();
        },
        url: 'lxajax.php',
        type: 'GET',
        data: lxajaxdata,
        // success is a function to be called if the request succeeds.
        success: function(data, status, jqXHR) {
            console.groupCollapsed("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lxchatFindFieldToRender()', 'ajax success');
            console.log("success callback:", status);
            console.log("data:", data);
            fieldsArray = JSON.parse(data);
            for (var i = 0; i < fieldsArray.length; i++) {
                if ($(document).find("#" + fieldsArray[i]).length) {
                    if (!record_id) {
                        console.log("lxchat doesn't render when record_id isn't present");
                        lxShowCRMfield(fieldsArray[i]);
                        $('<div id="lxchat" data-render="lxchat does not render when record_id is not present" ></div>').insertAfter('#' + fieldsArray[i]);
                    } else {
                        lxchatfield = fieldsArray[i];
                        lxchat_array_position = i;
                        lxchatRender(lxchatfield, lxchat_array_position);
                    }
                }
            }
        },
        // error is a function to be called if the request fails.
        error: function(jqXHR, status, error) {
            console.groupCollapsed("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lxchatFindFieldToRender()', 'ajax error');
            console.log("error callback:", status);
            console.log("Function lxchatFindFieldToRender error:", error);
            console.groupEnd();
        }, // end error
        // complete is a function to be called when the request finishes (after success and error callbacks are executed).
        complete: function(jqXHR, status) {
            var textarea = document.getElementById('lxchatcontent');
            if (textarea) {
                textarea.scrollTop = textarea.scrollHeight;
            }
        },
        datatype: "text"
    })
    // }
}

window.lxchatMessagesArrayToHTML = function(msgArray) {
    html = "";
    if (Array.isArray(msgArray)) {
        html = "";
        msgArray.forEach(function(msg) {
            var d = new Date(msg.date);
            var dd = d.getDate();
            var dm = d.getMonth() + 1;
            var dy = d.getFullYear();
            var dh = d.getHours();
            var di = d.getMinutes();
            var ampm = (dh > 12)
                ? "pm"
                : "am";
            dh = (dh > 12)
                ? "0" + (dh - 12)
                : dh;
            di = (di < 10)
                ? "0" + di
                : di;
            pStyle = (msg.currentUser)
                ? 'align="right" style="white-space: normal; background-color: #DCF8C6";'
                : 'style="white-space: normal; background-color: #FFF";';
            html += '<p ' + pStyle + '>' + '<b>' + msg.fullName + '</b>' + '</br></br>' + msg.msg + '</br></br>' + '<i>' + dd + '/' + dm + '/' + dy + ' ' + dh + ':' + di + ' ' + ampm + '</i>' + '</p></br>';
        });
    }
    return html;
}

// Observers definitions
!function() {
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
