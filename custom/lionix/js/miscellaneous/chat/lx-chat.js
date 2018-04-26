// This file containts lxchat bussines logic
//Self-Invoking Anonymous Function Notation
// !function(){}();  easy to read, the result is unimportant.
// (function(){})();  like above but more parens.
// (function(){}());  Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html then search for invoked immediately

// function definitions section
lx.chat = {};
lx.chat.field = '';
lx.chat.saveNewMessage = function(userMessage) {
    // Save button temporary disabled
    $("#lxchatSave").attr("disabled", true);
    if (userMessage === '') {
        console.log('lx.chat.saveNewMessage do not stores empty messages');
    } else {
        // message creation
        var m = moment();
        if (!Array.isArray(lx.chat.messagesArray)) {
            lx.chat.messagesArray = [];
        }
        lx.chat.messagesArray.push({"id": lx.current_user_id, "msg": userMessage, "date": m.toISOString()});
        newMessage = JSON.stringify(lx.chat.messagesArray.map(function(e) {
            return {"id": e.id, "msg": e.msg, "date": e.date}
        }));

        var currentForm = document.forms['DetailView'];
        if (!currentForm) {
            currentForm = document.forms['EditView'];
        }
        var record_id = currentForm.record.value;
        var module_name = currentForm.module.value;

        var data = {
            "method": "lxChat",
            "module": module_name,
            "record_id": record_id,
            "field_name": lx.chat.field,
            "save": 1,
            "chat_c": newMessage
        };
        $.ajax({
            // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
            beforeSend: function(jqXHR, settings) {
                console.log("LxChat Logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.saveNewMessage()', 'ajax beforeSend');
                console.log("beforeSend url:", settings.url);
                console.log("beforeSend data:", settings.data);
            },
            url: 'lxajax.php',
            type: 'POST',
            data: data,
            // success is a function to be called if the request succeeds.
            success: function(data, status, jqXHR) {
                console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.saveNewMessage()', 'ajax success');
                console.log("success callback:", status);
                console.log("data:", data);
                $("#lxchatnewmsg").val('');
                $("textarea#" + lx.chat.field).val(data);
                data = (data == '')
                    ? "[]"
                    : data;
                lx.chat.messagesArray = JSON.parse(data);
                document.getElementById("lxchatcontent").innerHTML = lx.chat.messagesArrayToHTML();
            },
            // error is a function to be called if the request fails.
            error: function(jqXHR, status, error) {
                console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.saveNewMessage()', 'ajax error');
                console.log("error callback:", status);
                console.log("Function lx.chat.saveNewMessage error:", error);
            }, // end error
            // complete is a function to be called when the request finishes (after success and error callbacks are executed).
            complete: function(jqXHR, status) {
                console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.saveNewMessage()', 'ajax complete');
                console.log("complete status:", status);
                lx.chat.scrollToBottom();
            },
            datatype: "text"
        });
    }
    $("#lxchatSave").removeAttr("disabled");
}

lx.chat.render = function(givenField) {
    if (!$("#lxchat").length) {
        var currentForm = document.forms['DetailView'];
        if (!currentForm) {
            currentForm = document.forms['EditView'];
        }
        var record_id = currentForm.record.value;
        var module_name = currentForm.module.value;

        var data = {
            "method": "lxChat",
            "module": module_name,
            "record_id": record_id,
            "field_name": givenField
        };
        $.ajax({
            // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
            beforeSend: function(jqXHR, settings) {
                console.log("LxChat Logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.render()', 'ajax beforeSend');
                console.log("beforeSend url:", settings.url);
                console.log("beforeSend data:", settings.data);
            },
            url: 'lxajax.php',
            type: 'POST',
            data: data,
            // success is a function to be called if the request succeeds.
            success: function(data, status, jqXHR) {
                console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.render()', 'ajax success');
                console.log("success callback:", status);
                console.log("data:", data);
                if (!$("#lxchat").length) {
                    var currentUser = $(".user_label:eq(0)").text().trim();
                    //Current lx.chat.field text
                    data = (data == '')
                        ? "[]"
                        : data;
                    lx.chat.messagesArray = JSON.parse(data);
                    lx.chat.fieldtext = lx.chat.messagesArrayToHTML();
                    //Current crm field must be hide
                    $('#' + givenField).hide();
                    //lxchat div added
                    $('<div id="lxchat" data-field="' + givenField + '"><center><b>LionixCRM Smart CHAT</b></center></div>').insertAfter('#' + givenField);
                    lx.chat.field = givenField;
                    $('#lxchat').attr('style', 'position:relative; width: 550px; border: 2px solid #829EB5; border-radius:5px; background-color: #A5E8D6;');
                    $('#lxchat').append('<div id="lxchatcontent" style="width: 100%; height: 434px; background-color: #E5DDD5; overflow-y: auto;"></div>');
                    document.getElementById("lxchatcontent").innerHTML = lx.chat.messagesArrayToHTML();
                    //Textarea for new messages added
                    $('<br><textarea id="lxchatnewmsg" placeholder="¿Quieres compartir alguna novedad, ' + currentUser.split(" ")[0] + '?" tabindex="0" title="" cols="80" rows="6" style="width: 550px;height: 90px;background-color: #F6FAFD;"></textarea>').insertAfter('#lxchat');
                    $(document).on("keypress", "#lxchatnewmsg", function(event) {
                        if (event.which == 13 && !event.shiftKey) {
                            lx.chat.validateNewMessage();
                        }
                    });
                    //Save new messages button added
                    $('<br><input id="lxchatSave" type="button" value="enviar mensaje" />').insertAfter('#lxchatnewmsg');
                    $(document).on("click", "#lxchatSave", function(event) {
                        lx.chat.validateNewMessage();
                    });
                }
            },
            // error is a function to be called if the request fails.
            error: function(jqXHR, status, error) {
                console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.render()', 'ajax error');
                console.log("error callback:", status);
                console.log("Function lx.chat.render error:", error);
            }, // end error
            // complete is a function to be called when the request finishes (after success and error callbacks are executed).
            complete: function(jqXHR, status) {
                console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.render()', 'ajax complete');
                console.log("complete status:", status);
                lx.chat.scrollToBottom();
                lx.chat.refreshMessagesInterval(true);
            },
            datatype: "text"
        });
    }
}

lx.chat.findFieldToRender = function() {
    if (!$("#lxchat").length) {
        var currentForm = document.forms['DetailView'];
        if (!currentForm) {
            currentForm = document.forms['EditView'];
        }
        var record_id = currentForm.record.value;
        var module_name = currentForm.module.value;
        var lxajaxdata = {
            "method": "lxChatGetSmartChatField"
        }
        $.ajax({
            // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
            beforeSend: function(jqXHR, settings) {
                console.log("LxChat Logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.findFieldToRender()', 'ajax beforeSend');
                console.log("beforeSend callback:", settings.url);
            },
            url: 'lxajax.php',
            type: 'POST',
            data: lxajaxdata,
            // success is a function to be called if the request succeeds.
            success: function(data, status, jqXHR) {
                console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.findFieldToRender()', 'ajax success');
                console.log("success callback:", status);
                console.log("data:", data);
                if (!$("#lxchat").length) {
                    if (data == '') {
                        console.log("lxChatGetSmartChatField: lxchat doesn't render when smartchat configuration isn't present on your LionixCRM config.php file");
                    } else {
                        lx.chat.candidateFieldsArray = JSON.parse(data);
                        for (var i = 0; i < lx.chat.candidateFieldsArray.length; i++) {
                            if ($(document).find("#" + lx.chat.candidateFieldsArray[i]).length) {
                                if (!record_id) {
                                    console.log("lxChatGetSmartChatField: lxchat doesn't render when record_id isn't present");
                                    lx.field.show(lx.chat.candidateFieldsArray[i], false);
                                    $('<div id="lxchat" data-render="lxchat does not render when record_id is not present" ></div>').insertAfter('#' + lx.chat.candidateFieldsArray[i]);
                                } else {
                                    lx.chat.render(lx.chat.candidateFieldsArray[i]);
                                }
                            }
                        }
                    }
                }
            },
            // error is a function to be called if the request fails.
            error: function(jqXHR, status, error) {
                console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.findFieldToRender()', 'ajax error');
                console.log("error callback:", status);
                console.log("Function lx.chat.findFieldToRender error:", error);
            }, // end error
            // complete is a function to be called when the request finishes (after success and error callbacks are executed).
            // complete: function(jqXHR, status) {
            //     console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.findFieldToRender()', 'ajax complete');
            //     console.log("complete status:", status);
            //
            // },
            datatype: "text"
        })
        // }
    }
}

lx.chat.messagesArrayToHTML = function() {
    msgArray = lx.chat.messagesArray;
    html = "";
    if (Array.isArray(msgArray)) {
        html = "";
        msgArray.forEach(function(msg, i) {
            var m = moment(msg.date);
            pStyle = (msg.currentUser)
                ? 'style="white-space: pre; word-wrap: break-word; border: 1px solid #829EB5; background-color: #DCF8C6;" align="right"' //my msgs
                : 'style="white-space: pre; word-wrap: break-word; border: 1px solid #829EB5; background-color: #F6FAFD;"' // their msgs
            html += '<p id="lxchatmsg' + i + '"' + pStyle + '>' + '<b>' + msg.fullName + '</b>' + '</br>' + msg.msg + '</br>' + '<i>' + m.calendar() + '</i>' + '</p>';
        });
    }
    return html;
}

lx.chat.start = function(forceCheck) {
    if (forceCheck) {
        console.log('Retrieving allow_smartchat property...');
        lx.lionixCRM.getConfigOption('allow_smartchat').then(function(data) {
            console.log('allow_smartchat successfully retrieved', data);
            lx.chat.start(false);
        });
    }
    if (lx.lionixCRM.config.allow_smartchat) {
        if (!lx.current_user_id) {
            var currentForm = document.forms['DetailView'];
            if (!currentForm) {
                currentForm = document.forms['EditView'];
            }
            var record_id = currentForm.record.value;
            var module_name = currentForm.module.value;
            var lxajaxdata = {
                "method": "getCurrentUserId"
            }
            $.ajax({
                // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
                beforeSend: function(jqXHR, settings) {
                    console.log("LxChat Logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.start', 'ajax beforeSend');
                    console.log("beforeSend callback:", settings.url);
                },
                url: 'lxajax.php',
                type: 'POST',
                data: lxajaxdata,
                // success is a function to be called if the request succeeds.
                success: function(data, status, jqXHR) {
                    console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.start', 'ajax success');
                    console.log("success callback:", status);
                    console.log("data:", data);
                    lx.current_user_id = data;
                    lx.chat.start(false);
                },
                // error is a function to be called if the request fails.
                error: function(jqXHR, status, error) {
                    console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.start', 'ajax error');
                    console.log("error callback:", status);
                    console.log("Function lx.chat.start error:", error);
                }, // end error
                // complete is a function to be called when the request finishes (after success and error callbacks are executed).
                // complete: function(jqXHR, status) {
                // },
                datatype: "text"
            });

        }
        if (!$("#lxchat").length) {
            lx.chat.findFieldToRender();
        }
    } else {
        if (lx.lionixCRM.config.debuglx) {
            console.log('smartchat is disabled in LionixCRM config.php file.');
        }
    }
}

lx.chat.scrollToBottom = function() {
    var h1 = $('#lxchatcontent')[0].scrollHeight,
        h2 = $('#lxchatcontent').height();
    $('#lxchatcontent').scrollTop(h1 - h2);
}

lx.chat.refreshMessagesInterval = function(refresh) {
    if (lx.chat.interval_momentjs_ids_list == undefined) {
        lx.chat.interval_momentjs_ids_list = [];
    }
    if (lx.chat.interval_messages_ids_list == undefined) {
        lx.chat.interval_messages_ids_list = [];
    }
    if (refresh) {
        console.log('Starting new LionixCRM Smart CHAT refresh messages interval...');
        // chat momentjs
        lx.chat.interval_momentjs_ids_list.forEach(function(element) {
            window.clearInterval(element);
        });
        new_id = window.setInterval(function() {
            if (!$("#lxchatcontent").length) {
                lx.chat.refreshMessagesInterval(false);
            } else {
                console.log('Refreshing momentjs strings on LionixCRM Smart CHAT, interval id: %s, see you in 15 secs...', lx.chat.interval_momentjs_ids_list[0]);
                document.getElementById("lxchatcontent").innerHTML = lx.chat.messagesArrayToHTML();
            }
        }, 15000);
        lx.chat.interval_momentjs_ids_list.unshift(new_id);
        console.log('New LionixCRM momentjs refresh strings interval id set: %s', new_id);
        // chat messages
        lx.chat.interval_messages_ids_list.forEach(function(element) {
            window.clearInterval(element);
        });
        new_id = window.setInterval(function() {
            console.log('Retrieving messages from database on LionixCRM Smart CHAT, interval id: %s, see you in 5 mins...', lx.chat.interval_messages_ids_list[0]);
            lx.chat.getMessages();
        }, 300000);
        lx.chat.interval_messages_ids_list.unshift(new_id);
        console.log('New LionixCRM getMessages interval id set: %s', new_id);
    } else {
        lx.chat.interval_momentjs_ids_list.forEach(function(element) {
            window.clearInterval(element);
        });
        lx.chat.interval_messages_ids_list.forEach(function(element) {
            window.clearInterval(element);
        });
        lx.chat.interval_momentjs_ids_list = undefined;
        lx.chat.interval_messages_ids_list = undefined;
        console.log('LionixCRM messages interval stopped');
    }
}

lx.chat.validateNewMessage = function() {
    var userMessage = $("#lxchatnewmsg").val().trim();
    if (userMessage === '') {
        toastr["warning"]("Mensaje vacío no permitido", "Smart Chat", {
            "positionClass": "toast-bottom-center",
            "showDuration": "0",
            "hideDuration": "0",
            "timeOut": "1000",
            "extendedTimeOut": "0",
            "progressBar": true,
            "onShown": function() {
                $('#lxchatnewmsg').val('');
                $('#lxchatnewmsg').focus();
            }
        });
    } else {
        toastr["success"]("Guardando mensaje...", "Smart Chat", {
            "positionClass": "toast-bottom-center",
            "showDuration": "1",
            "hideDuration": "1",
            "timeOut": "1",
            "extendedTimeOut": "1",
            "onShown": function() {
                lx.chat.saveNewMessage(userMessage);
                $('#lxchatnewmsg').focus();
            }
        });
    }
}

lx.chat.getMessages = function() {
    // Save button temporary disabled
    $("#lxchatSave").attr("disabled", true);
    var currentForm = document.forms['DetailView'];
    if (!currentForm) {
        currentForm = document.forms['EditView'];
    }
    var record_id = currentForm.record.value;
    var module_name = currentForm.module.value;

    var data = {
        "method": "lxChat",
        "module": module_name,
        "record_id": record_id,
        "field_name": lx.chat.field,
        "save": 0
    };
    $.ajax({
        // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
        beforeSend: function(jqXHR, settings) {
            console.log("LxChat Logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.getMessages()', 'ajax beforeSend');
            console.log("beforeSend url:", settings.url);
            console.log("beforeSend data:", settings.data);
        },
        url: 'lxajax.php',
        type: 'POST',
        data: data,
        // success is a function to be called if the request succeeds.
        success: function(data, status, jqXHR) {
            console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.getMessages()', 'ajax success');
            console.log("success callback:", status);
            console.log("data:", data);
            $("textarea#" + lx.chat.field).val(data);
            data = (data == '')
                ? "[]"
                : data;
            lx.chat.messagesArray = JSON.parse(data);
            document.getElementById("lxchatcontent").innerHTML = lx.chat.messagesArrayToHTML();
        },
        // error is a function to be called if the request fails.
        error: function(jqXHR, status, error) {
            console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.getMessages()', 'ajax error');
            console.log("error callback:", status);
            console.log("Function lx.chat.getMessages error:", error);
        }, // end error
        // complete is a function to be called when the request finishes (after success and error callbacks are executed).
        complete: function(jqXHR, status) {
            console.log("LxChat logic '%s' '%s' '%s' '%s'", module_name, 'lx-chat.js', 'lx.chat.getMessages()', 'ajax complete');
            console.log("complete status:", status);
            lx.chat.scrollToBottom();
        },
        datatype: "text"
    });
    $("#lxchatSave").removeAttr("disabled");
}

// Observers definitions
!function() {
    if ($("#edit_button").length || $("#SAVE").length || $("#SAVE_HEADER").length) {
        // now it ensures that lxchat isn't already present
        if (!$("#lxchat").length) {
            lx.chat.start(false);
        }
    }
    // On other modules
    // create an observer instance
    // https://developer.mozilla.org/en/docs/Web/API/MutationObserver
    var observer = new MutationObserver(function(mutations) {
        if (mutations) {
            var currentForm = document.forms['DetailView'];
            if (!currentForm) {
                currentForm = document.forms['EditView'];
            }
            if (currentForm) {
                $(document).ready(function() {
                    // if #edit_button exists is a detailview, if not, then if a #SAVE or #SAVE_HEADER button exists is a editview
                    if ($("#edit_button").length || $("#SAVE").length || $("#SAVE_HEADER").length) {
                        // now it ensures that lxchat isn't already present
                        if (!$("#lxchat").length) {
                            lx.chat.start(false);
                        }
                    }
                });
            }
            // if needed only once, you can stop observing with observer.disconnect();
            // observer.disconnect();
        }
    });
    // Observer target
    var target = document.querySelector('#content');
    if (target) {
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
    }
    // end observer
}();

// eof
