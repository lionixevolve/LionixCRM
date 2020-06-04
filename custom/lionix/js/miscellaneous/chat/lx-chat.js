// This file containts lxchat bussines logic
//Self-Invoking Anonymous Function Notation
// !function(){}();  easy to read, the result is unimportant.
// (function(){})();  like above but more parens.
// (function(){}());  Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html and look for invoked immediately

// function definitions section
lx.chat = {};
lx.chat.field = "";
lx.chat.saveNewMessage = async function (userMessage) {
    // Save button temporary disabled
    $("#lxchatSave").attr("disabled", true);
    if (userMessage === "") {
        console.warn("lx.chat.saveNewMessage do not store empty messages");
    } else {
        // message creation
        let m = moment();
        if (!Array.isArray(lx.chat.messagesArray)) {
            lx.chat.messagesArray = [];
        }
        lx.chat.messagesArray.push({
            id: lx.current_user_id,
            msg: userMessage,
            date: m.toISOString(),
        });
        newMessage = JSON.stringify(
            lx.chat.messagesArray.map(function (e) {
                return { id: e.id, msg: e.msg, date: e.date };
            })
        );
        let currentForm = document.forms["DetailView"];
        if (!currentForm) {
            currentForm = document.forms["EditView"];
        }
        let record_id = currentForm.record.value;
        let module_name = currentForm.module.value;
        let lxajax_method = "lxChat";
        let data = {
            method: lxajax_method,
            module: module_name,
            record_id: record_id,
            field_name: lx.chat.field,
            save: 1,
            chat_c: newMessage,
        };
        let response = await fetch("lxajax.php", {
            method: "POST",
            body: new URLSearchParams(data),
            headers: new Headers({
                "Content-type":
                    "application/x-www-form-urlencoded; charset=UTF-8",
            }),
        });
        data = await response.text().catch((error) => {
            console.error("Function lx.chat.saveNewMessage error:", error);
        });
        $("#lxchatnewmsg").val("");
        $(`textarea#${lx.chat.field}`).val(data);
        data = data == "" ? "[]" : data;
        lx.chat.messagesArray = JSON.parse(data);
        console.warn("Message stored:", lx.chat.messagesArray);
        document.getElementById(
            "lxchatcontent"
        ).innerHTML = lx.chat.messagesArrayToHTML();
        lx.chat.scrollToBottom();
    }
    $("#lxchatSave").removeAttr("disabled");
};

lx.chat.render = async function (givenField) {
    if (!$("#lxchat").length) {
        let currentForm = document.forms["DetailView"];
        if (!currentForm) {
            currentForm = document.forms["EditView"];
        }
        let record_id = currentForm.record.value;
        let module_name = currentForm.module.value;
        let lxajax_method = "lxChat";
        let data = {
            method: lxajax_method,
            module: module_name,
            record_id: record_id,
            field_name: givenField,
        };
        let response = await fetch("lxajax.php", {
            method: "POST",
            body: new URLSearchParams(data),
            headers: new Headers({
                "Content-type":
                    "application/x-www-form-urlencoded; charset=UTF-8",
            }),
        });
        data = await response.text().catch((error) => {
            console.warn("Function lx.chat.render error:", error);
        });
        if (!$("#lxchat").length) {
            let currentUser = $("#with-label").text().trim();
            //Current lx.chat.field text
            data = data == "" ? "[]" : data;
            lx.chat.messagesArray = JSON.parse(data);
            console.warn("lx.chat.render messages:", lx.chat.messagesArray);
            lx.chat.fieldtext = lx.chat.messagesArrayToHTML();
            //Current crm field must be hide
            $(`#${givenField}`).hide();
            //lxchat div added
            $(
                `<div id="lxchat" class="lxchat" data-field="${givenField}"><center><b>LionixCRM Smart CHAT</b></center></div>`
            ).insertAfter(`#${givenField}`);
            lx.chat.field = givenField;
            $("#lxchat").attr(
                "style",
                "position:relative; width: 550px; border: 2px solid #829EB5; border-radius:5px; background-color: #A5E8D6;"
            );
            $("#lxchat").append(
                '<div id="lxchatcontent" style="width: 100%; height: 434px; background-color: #E5DDD5; overflow-y: auto;"></div>'
            );
            document.getElementById(
                "lxchatcontent"
            ).innerHTML = lx.chat.messagesArrayToHTML();
            //Textarea for new messages added
            $(
                `<br><textarea id="lxchatnewmsg" placeholder="¿Quieres compartir alguna novedad, ${
                    currentUser.split(" ")[0]
                }?" tabindex="0" title="" cols="80" rows="6" style="width: 550px;height: 90px;background-color: #F6FAFD;"></textarea>`
            ).insertAfter("#lxchat");
            $(document).on("keypress", "#lxchatnewmsg", function (event) {
                if (event.which == 13 && !event.shiftKey) {
                    lx.chat.validateNewMessage();
                }
            });
            //Save new messages button added
            $(
                '<br><input id="lxchatSave" type="button" value="enviar mensaje" />'
            ).insertAfter("#lxchatnewmsg");
            $(document).on("click", "#lxchatSave", function (event) {
                lx.chat.validateNewMessage();
            });
            lx.chat.scrollToBottom();
            lx.chat.refreshMessagesInterval(true);
            let msg = "Smartchat fields rendered";
            console.warn(`lxChatGetSmartChatField: ${msg}`);
        }
    }
};

lx.chat.findFieldToRender = async function () {
    if (!$("#lxchat").length) {
        let currentForm = document.forms["DetailView"];
        if (!currentForm) {
            currentForm = document.forms["EditView"];
        }
        let record_id = currentForm.record.value;
        let lxajax_method = "lxChatGetSmartChatField";
        let data = {
            method: lxajax_method,
        };
        let response = await fetch("lxajax.php", {
            method: "POST",
            body: new URLSearchParams(data),
            headers: new Headers({
                "Content-type":
                    "application/x-www-form-urlencoded; charset=UTF-8",
            }),
        });
        data = await response.text().catch((error) => {
            console.error("lxajax.php lxChatGetSmartChatField error:", error);
        });
        if (data == "[]") {
            if (!$(".lxchat").length) {
                let msg = "smartchat fields configuration not found";
                console.warn(`lxChatGetSmartChatField: ${msg}`);
                $(
                    `<div id="lxchat" class="lxchat" data-render="${msg}" ></div>`
                ).insertAfter(`.tab-content:first`);
            }
        } else {
            lx.chat.candidateFieldsArray = JSON.parse(data);
            for (let i = 0; i < lx.chat.candidateFieldsArray.length; i++) {
                if (
                    !!$(document).find(`#${lx.chat.candidateFieldsArray[i]}`)
                        .length
                ) {
                    if (!$(".lxchat").length && !record_id) {
                        //Chat field must be hidden on new records
                        console.warn(
                            `Hidding field ${lx.chat.candidateFieldsArray[i]}...`
                        );
                        let msg = `...field ${lx.chat.candidateFieldsArray[i]} hidden on new records`;
                        $(
                            `<div id="lxchat" class="lxchat" data-render="${msg}" ></div>`
                        ).insertAfter(`.tab-content:first`);
                        if (
                            $(`#${lx.chat.candidateFieldsArray[i]}`).is(
                                ":visible"
                            )
                        ) {
                            lx.field.show(
                                lx.chat.candidateFieldsArray[i],
                                false
                            );
                            console.warn(msg);
                        }
                    } else {
                        await lx.chat.render(lx.chat.candidateFieldsArray[i]);
                    }
                }
            }
        }
    }
};

lx.chat.messagesArrayToHTML = function () {
    msgArray = lx.chat.messagesArray;
    html = "";
    if (Array.isArray(msgArray)) {
        html = "";
        msgArray.forEach(function (msg, i) {
            let m = moment(msg.date);
            pStyle = msg.currentUser
                ? 'style="white-space: pre; word-wrap: break-word; border: 1px solid #829EB5; background-color: #DCF8C6;" align="right"' //my msgs
                : 'style="white-space: pre; word-wrap: break-word; border: 1px solid #829EB5; background-color: #F6FAFD;"'; // their msgs
            html += `<p id="lxchatmsg${i}"${pStyle}><b>${
                msg.fullName
            }</b></br>${msg.msg}</br><i>${m.calendar()}</i></p>`;
        });
    }
    return html;
};

lx.chat.start = async function () {
    let run = true;
    if (lx.lionixCRM.config.allow_smartchat == undefined) {
        document.addEventListener("lxLoadAllConfigOptions", () => {
            lx.chat.start();
        });
        run = false;
    }
    if (run) {
        if (lx.lionixCRM.config.allow_smartchat) {
            if (!lx.current_user_id) {
                let currentForm = document.forms["DetailView"];
                if (!currentForm) {
                    currentForm = document.forms["EditView"];
                }
                let lxajax_method = "getCurrentUserId";
                let data = {
                    method: lxajax_method,
                };
                let response = await fetch("lxajax.php", {
                    method: "POST",
                    body: new URLSearchParams(data),
                    headers: new Headers({
                        "Content-type":
                            "application/x-www-form-urlencoded; charset=UTF-8",
                    }),
                });
                lx.current_user_id = await response.text().catch((error) => {
                    console.error("lxajax.php getCurrentUserId error:", error);
                });
            }
            if (!$("#lxchat").length) {
                await lx.chat.findFieldToRender();
            }
        } else {
            lx.events.lxChat.status = "disabled";
            console.warn("smartchat is disabled in LionixCRM config.php file.");
        }
    }
};

lx.chat.scrollToBottom = function () {
    let h1 = $("#lxchatcontent")[0].scrollHeight,
        h2 = $("#lxchatcontent").height();
    $("#lxchatcontent").scrollTop(h1 - h2);
};

lx.chat.refreshMessagesInterval = function (refresh) {
    if (lx.chat.interval_momentjs_ids_list == undefined) {
        lx.chat.interval_momentjs_ids_list = [];
    }
    if (lx.chat.interval_messages_ids_list == undefined) {
        lx.chat.interval_messages_ids_list = [];
    }
    if (refresh) {
        console.warn(
            "Starting new LionixCRM Smart CHAT refresh messages interval..."
        );
        // chat momentjs
        lx.chat.interval_momentjs_ids_list.forEach(function (element) {
            window.clearInterval(element);
        });
        new_id = window.setInterval(function () {
            if (!$("#lxchatcontent").length) {
                lx.chat.refreshMessagesInterval(false);
            } else {
                console.warn(
                    "Refreshing momentjs strings on LionixCRM Smart CHAT, interval id: %s, see you in 15 secs...",
                    lx.chat.interval_momentjs_ids_list[0]
                );
                document.getElementById(
                    "lxchatcontent"
                ).innerHTML = lx.chat.messagesArrayToHTML();
            }
        }, 15000);
        lx.chat.interval_momentjs_ids_list.unshift(new_id);
        console.warn(
            "New LionixCRM momentjs refresh strings interval id set: %s",
            new_id
        );
        // chat messages
        lx.chat.interval_messages_ids_list.forEach(function (element) {
            window.clearInterval(element);
        });
        new_id = window.setInterval(function () {
            console.warn(
                "Retrieving messages from database on LionixCRM Smart CHAT, interval id: %s, see you in 5 mins...",
                lx.chat.interval_messages_ids_list[0]
            );
            lx.chat.getMessages();
        }, 300000);
        lx.chat.interval_messages_ids_list.unshift(new_id);
        console.warn("New LionixCRM getMessages interval id set: %s", new_id);
    } else {
        lx.chat.interval_momentjs_ids_list.forEach(function (element) {
            window.clearInterval(element);
        });
        lx.chat.interval_messages_ids_list.forEach(function (element) {
            window.clearInterval(element);
        });
        lx.chat.interval_momentjs_ids_list = undefined;
        lx.chat.interval_messages_ids_list = undefined;
        console.warn("LionixCRM messages interval stopped");
    }
};

lx.chat.validateNewMessage = function () {
    let userMessage = $("#lxchatnewmsg").val().trim();
    if (userMessage === "") {
        toastr["warning"]("Mensaje vacío no permitido", "Smart Chat", {
            positionClass: "toast-bottom-center",
            showDuration: "0",
            hideDuration: "0",
            timeOut: "1000",
            extendedTimeOut: "0",
            progressBar: true,
            onShown: function () {
                $("#lxchatnewmsg").val("");
                $("#lxchatnewmsg").focus();
            },
        });
    } else {
        toastr["success"]("Guardando mensaje...", "Smart Chat", {
            positionClass: "toast-bottom-center",
            showDuration: "1",
            hideDuration: "1",
            timeOut: "1",
            extendedTimeOut: "1",
            onShown: function () {
                lx.chat.saveNewMessage(userMessage);
                $("#lxchatnewmsg").focus();
            },
        });
    }
};

lx.chat.getMessages = async function () {
    // Save button temporary disabled
    $("#lxchatSave").attr("disabled", true);
    let currentForm = document.forms["DetailView"];
    if (!currentForm) {
        currentForm = document.forms["EditView"];
    }
    let record_id = currentForm.record.value;
    let module_name = currentForm.module.value;
    let lxajax_method = "lxChat";
    let data = {
        method: lxajax_method,
        module: module_name,
        record_id: record_id,
        field_name: lx.chat.field,
        save: 0,
    };
    let response = await fetch("lxajax.php", {
        method: "POST",
        body: new URLSearchParams(data),
        headers: new Headers({
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
        }),
    });
    data = await response.text().catch((error) => {
        console.warn("Function lx.chat.getMessages error:", error);
    });
    console.warn("getMessages data:", data);
    $(`textarea#${lx.chat.field}`).val(data);
    data = data == "" ? "[]" : data;
    lx.chat.messagesArray = JSON.parse(data);
    document.getElementById(
        "lxchatcontent"
    ).innerHTML = lx.chat.messagesArrayToHTML();
    lx.chat.scrollToBottom();
    $("#lxchatSave").removeAttr("disabled");
};

// Observers definitions
!(function () {
    // On other modules
    // create an observer instance
    // https://developer.mozilla.org/en/docs/Web/API/MutationObserver
    let observer = new MutationObserver(async function (mutations) {
        if (mutations) {
            let currentForm = document.forms["DetailView"];
            if (!currentForm) {
                currentForm = document.forms["EditView"];
            }
            if (currentForm) {
                if (!lx.events.hasOwnProperty("lxChat")) {
                    lx.events.lxChat = {
                        status: "send",
                    };
                }
                if (lx.events.lxChat.status == "disabled") {
                    console.warn(
                        "Observer on %clx-chat.js %cdisconnected",
                        "color:blue",
                        "color:red"
                    );
                    // if needed only once, you can stop observing with observer.disconnect();
                    observer.disconnect();
                    lx.observers.disconnected += 1;
                    lx.observers.observing -= 1;
                    console.warn("lx.observers", lx.observers);
                }
                // if #edit_button exists is a detailview, else if a #SAVE or #SAVE_HEADER button exists is a editview
                if (
                    $("#edit_button").length ||
                    $("#SAVE").length ||
                    $("#SAVE_HEADER").length
                ) {
                    // now it ensures that lxchat isn't already present
                    if (!$("#lxchat").length) {
                        await lx.chat.start();
                    }
                }
            }
        }
    });
    // Observer target
    let target = document.querySelector("body");
    if (target) {
        // configuration of the observer:
        // NOTE: At the very least, childList, attributes, or characterData must be set to true. Otherwise, "An invalid or illegal string was specified" error is thrown.
        let config = {
            attributes: true,
            childList: true,
            characterData: true,
            subtree: true,
        };
        // pass in the target node, as well as the observer options
        observer.observe(target, config);
        lx.observers.created += 1;
        lx.observers.observing += 1;
    }
    // end observer
})();

// eof
