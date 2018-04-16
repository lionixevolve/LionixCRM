// function definitions section
lx.opportunity.maincontact_fields = [
    {
        "field": 'maincontactduplicateid_c',
        "label": 'Id contacto dulicado',
        "required": false,
        "hidden": true,
        "notes": "This field must be always hide"
    }, {
        "field": 'maincontactcedula_c',
        "required": false,
        "label": 'Cédula nuevo contacto'
    }, {
        "field": 'maincontactfirstname_c',
        "required": true,
        "label": 'Nombre nuevo contacto'
    }, {
        "field": 'maincontactlastname_c',
        "required": true,
        "label": '1er apellido nuevo contacto'
    }, {
        "field": 'maincontactlastname2_c',
        "required": false,
        "label": '2do apellido nuevo contacto'
    }, {
        "field": 'maincontactemailaddress_c',
        "label": 'Correo electrónico nuevo contacto',
        "required": true,
        "customValidate": true,
        "customValidateCallback": function() {
            emailRegExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return emailRegExp.test($('#maincontactemailaddress_c').val());
        }
    }, {
        "field": 'maincontactphonemobile_c',
        "required": true,
        "label": 'Télefono celular nuevo contacto'
    }, {
        "field": 'maincontactphonework_c',
        "required": false,
        "label": 'Télefono trabajo nuevo contacto'
    }, {
        "field": 'maincontacttitle_c',
        "required": false,
        "label": 'Cargo nuevo contacto'
    }
];

lx.opportunity.clearMainContactCFields = function(duplicate) {
    lx.opportunity.maincontact_fields.forEach(function(element) {
        $('#' + element.field).val('');
        if (duplicate) {
            $('#' + element.field).css('border', '1px solid #a5e8d6');
        }
    });
    if (duplicate) {
        $('#account_id').val('');
        $('.lx-clear-duplicate').remove();
    }
}

lx.opportunity.getnewMainContactCFields = function() {
    var form_name = 'EditView';
    if ($("#lx_opportunity_maincontact_c_lxajaxed_getnewMainContactCFields_first_time").length == 0) {
        $("#maincontact_c").append('<div id="lx_opportunity_maincontact_c_lxajaxed_getnewMainContactCFields_first_time"/>');
        lx.opportunity.clearMainContactCFields();
    }
    if ($("#maincontact_c").val() == 'new') {
        lx.opportunity.maincontact_fields.forEach(function(element) {
            if (element.customValidate) {
                lx.field.validateCallback(form_name, element.field, element.label, element.customValidateCallback, element.required);
            } else {
                if (element.required) {
                    lx.field.validate(form_name, element.field, element.label, true);
                } else {
                    lx.field.validate(form_name, element.field, element.label, false);
                }
            }
            if (element.hidden) {
                lx.field.show(element.field, false);
            } else {
                lx.field.show(element.field, true);
            }
        });
        $('#maincontactfirstname_c').on("focusout.maincontactfirstname_c", function() {
            switch ($("#maincontactfirstname_c").val().toUpperCase()) {
                case "NEW":
                    $("#maincontactfirstname_c").val('');
                    break;
            }
        });
    } else {
        lx.opportunity.maincontact_fields.forEach(function(element) {
            lx.field.validate(form_name, element.field, element.label, false);
        });
        lx.opportunity.maincontact_fields.forEach(function(element) {
            lx.field.show(element.field, false);
        });
        $('#maincontactfirstname_c').off("focusout.maincontactfirstname_c");
    }
}

lx.opportunity.getMainContactDropdown = function(opportunityId, currentValue, accountId, accountName) {
    var method = "getOpportunityMainContactList";
    var data = "method=" + method;
    if (accountName == '') {
        accountId = '';
    }
    data += "&opportunityId=" + opportunityId;
    data += "&currentValue=" + currentValue;
    data += "&accountId=" + accountId;
    //data += "&use_adodb5="+"1";
    $.ajax({
        // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
        beforeSend: function(jqXHR, settings) {
            console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-main-contact.js', 'lx.opportunity.getMainContactDropdown()', 'ajax beforeSend');
            console.log("*** start ***");
            console.log("beforeSend callback:", settings.url);
        }, //end beforeSend
        url: 'lxajax.php',
        type: 'GET',
        data: data,
        // success is a function to be called if the request succeeds.
        success: function(data, status, jqXHR) {
            console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-main-contact.js', 'lx.opportunity.getMainContactDropdown()', 'ajax success');
            console.log("success callback:", status);
            console.log("data:", data);
            $('#maincontact_c').fillSelect($.parseJSON(data));
        }, // end success
        // error is a function to be called if the request fails.
        error: function(jqXHR, status, error) {
            console.log("Bussines logic '%s' '%s' '%s' '%s'", 'opportunities', 'lx-main-contact.js', 'lx.opportunity.getMainContactDropdown()', 'ajax error');
            console.log("error callback:", status);
            console.log("Function lx.opportunity.getMainContactDropdown error:", error);
        }, // end error
        // complete is a function to be called when the request finishes (after success and error callbacks are executed).
        // complete: function(jqXHR, status) {
        // },  end complete
        datatype: "text"
    }); // end ajax
    lx.opportunity.getnewMainContactCFields();
}

lx.opportunity.renderMainContactDuplicates = function(duplicates) {
    console.log("duplicados devueltos:", duplicates);
    $('.main_contact_duplicates ul li').remove();
    if (duplicates.data.length) {
        duplicates.data.forEach(function(element) {
            primary_address = {};
            element.emails.forEach(function(element) {
                if (element.primary_address === '1') {
                    primary_address.id = element.id;
                    primary_address.email_address = element.email_address;
                }
            });
            duplicate_detail = '<li id="' + element.id;
            duplicate_detail += '" data-account_id="' + element.accounts[0].id;
            duplicate_detail += '" data-cedula_c="' + element.cedula_c;
            duplicate_detail += '" data-first_name="' + element.first_name;
            duplicate_detail += '" data-last_name="' + element.last_name;
            duplicate_detail += '" data-lastname2_c="' + element.lastname2_c;
            duplicate_detail += '" data-phone_mobile="' + element.phone_mobile;
            duplicate_detail += '" data-phone_work="' + element.phone_work;
            duplicate_detail += '" data-title="' + element.title;
            duplicate_detail += '" data-primary_email_address_id="' + primary_address.id;
            duplicate_detail += '" data-primary_email_address="' + primary_address.email_address;
            duplicate_detail += '">';
            duplicate_detail += element.first_name + ' ' + element.last_name + ' ' + element.lastname2_c + ' ' + element.cedula_c + ' ';
            element.emails.forEach(function(element) {
                duplicate_detail += element.email_address + ' ';
            });
            duplicate_detail += element.phone_mobile + ' ';
            duplicate_detail += element.phone_work;
            duplicate_detail += '</li>';
            $('.main_contact_duplicates ul').append(duplicate_detail);
        });
        newh = duplicates.data.length * 20 + 18; // last 18px bit is for duplicates title
        $('#main_contact_duplicates_' + duplicates.fieldname + ' .yui-ac-content').css("height", newh + "px");
        $('#main_contact_duplicates_' + duplicates.fieldname + ' .yui-ac-content').show(500);
        $('.main_contact_duplicates li').off("mouseover.main_contact_duplicates_list");
        $('.main_contact_duplicates li').on("mouseover.main_contact_duplicates_list", function() {
            $(this).toggleClass('yui-ac-highlight');
            $(this).siblings('li').removeClass('yui-ac-highlight');
        });
        $('.main_contact_duplicates li').off("click.main_contact_duplicates_list");
        $('.main_contact_duplicates li').on("click.main_contact_duplicates_list", function() {
            $('.main_contact_duplicates .yui-ac-content').hide(500);

            console.log('contact_id', $(this).prop('id'));
            console.log('email_id', $(this).data('primary_email_address_id'));
            $('#maincontactduplicateid_c').val($(this).prop('id'));
            $('#account_id').val($(this).data('account_id'));
            $('#maincontactcedula_c').val($(this).data('cedula_c'));
            $('#maincontactfirstname_c').val($(this).data('first_name'));
            $('#maincontactlastname_c').val($(this).data('last_name'));
            $('#maincontactlastname2_c').val($(this).data('lastname2_c'));
            $('#maincontactphonemobile_c').val($(this).data('phone_mobile'));
            $('#maincontactphonework_c').val($(this).data('phone_work'));
            $('#maincontactemailaddress_c').val($(this).data('primary_email_address'));
            $('#maincontacttitle_c').val($(this).data('title'));

            $('#maincontactcedula_c').css('border', '1px solid #0045ff')
            $('#maincontactfirstname_c').css('border', '1px solid #0045ff')
            $('#maincontactlastname_c').css('border', '1px solid #0045ff')
            $('#maincontactlastname2_c').css('border', '1px solid #0045ff')
            $('#maincontactphonemobile_c').css('border', '1px solid #0045ff')
            $('#maincontactphonework_c').css('border', '1px solid #0045ff')
            $('#maincontactemailaddress_c').css('border', '1px solid #0045ff')
            $('#maincontacttitle_c').css('border', '1px solid #0045ff')

            main_contact_clear_button = '<span class="id-ff multiple"> <button type="button" style="margin: 8px" class="button lastChild lx-clear-duplicate" value="Limpiar nuevo contacto" onclick="lx.opportunity.clearMainContactCFields(true)"><img src="themes/SuiteP/images/id-ff-clear.png"></button></span>';

            $('#maincontactcedula_c').after(main_contact_clear_button);
            $('#maincontactfirstname_c').after(main_contact_clear_button);
            $('#maincontactlastname_c').after(main_contact_clear_button);
            $('#maincontactlastname2_c').after(main_contact_clear_button);
            $('#maincontactphonemobile_c').after(main_contact_clear_button);
            $('#maincontactphonework_c').after(main_contact_clear_button);
            $('#maincontactemailaddress_c').after(main_contact_clear_button);
            $('#maincontacttitle_c').after(main_contact_clear_button);
        });
    }
}

//Self-Invoking Anonymous Function Notation
// !function(){}();  easy to read, the result is unimportant.
// (function(){})();  like above but more parens.
// (function(){}());  Douglas Crockford's style when you need function results.
// Further reading: http://javascript.crockford.com/code.html then search for invoked immediately
!function() {
    // create an observer instance
    // https://developer.mozilla.org/en/docs/Web/API/MutationObserver
    var observer = new MutationObserver(function(mutations) {
        if (mutations) {
            var crmEditView = document.forms['EditView'];
            if (crmEditView) {
                if (crmEditView.module.value == 'Opportunities') {
                    if ($("#maincontact_c_lxajaxed").length == 0) {
                        console.log("Bussines logic observer '%s' '%s' '%s' '%s'", 'Opportunities', 'lx-main-contact.js', '!function()', 'initial');
                        opid = document.forms['EditView'].record.value;
                        $("#maincontact_c").append('<div id="maincontact_c_lxajaxed"/>');
                        lx.opportunity.getMainContactDropdown(opid, $("#maincontact_c").val(), $("#account_id").val()); //popoulate dropdown once when editview loads.
                        $('#account_name').on("focusout.account-name", function() {
                            lx.opportunity.getMainContactDropdown(opid, $("#maincontact_c").val(), $("#account_id").val(), $("#account_name").val()); //popoulate dropdown once when editview loads.
                        });
                        $('#maincontact_c').on("change.lx-main-contact-c", function() {
                            lx.opportunity.getnewMainContactCFields();
                        });
                        $('#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c').on("keypress.duplicate_results_list", function() {
                            ffn = $('#maincontactfirstname_c');
                            fln = $('#maincontactlastname_c');
                            fln2 = $('#maincontactlastname2_c');
                            femail = $('#maincontactemailaddress_c');
                            fced = $('#maincontactcedula_c');
                            if ($(ffn).val().length > 2 && ($(fln).val().length > 2 || $(fln2).val().length > 2)) {
                                if ($('#main_contact_duplicates_' + this.id).length == 0) {
                                    $(this).before('<div id="main_contact_duplicates_' + this.id + '" class="main_contact_duplicates yui-ac-container" style="position: relative; left: 200px; top:0px;"><div class="yui-ac-content" style="width: 650px; height: 60px; display: none; "><div class="yui-ac-bd">Posibles duplicados encontrados<ul id="#ul_' + this.id + '"></ul></div></div></div>');
                                }
                                $('#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c').off("focusout.duplicate_results_list");
                                $('#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c').on("focusout.duplicate_results_list", function() {
                                    $('.main_contact_duplicates .yui-ac-content').hide(500);
                                });
                                lx.lionixCRM.getContactDuplicates({
                                    "fieldname": this.id, "first_name": $(ffn).val(), "last_name": $(fln).val(), "lastname2_c": $(fln2).val()
                                    // "cedula_c": $(fced).val(),
                                    // "email_address": $(femail).val()
                                }).then(function(duplicates) {
                                    lx.opportunity.renderMainContactDuplicates(duplicates)
                                });
                            }
                        });
                        $('#maincontactcedula_c').on("keyup.tsecr_search_results", function() {
                            console.log('key up ->', String.fromCharCode(event.which));
                            if ($(this).val().length != 9) {
                                $('#maincontactfirstname_c').val('');
                                $('#maincontactlastname_c').val('');
                                $('#maincontactlastname2_c').val('');
                            } else {
                                typed_cedula = $(this).val();
                                lx.lionixCRM.getTSECRData({
                                    // Infoticos must be on same database server this SuiteCRM instance.
                                    "focusfieldname": this.id,
                                    "cedula_c": typed_cedula
                                }).then(function(tsecrlist) {
                                    console.log('After query to lx.lionixCRM.getTSECRData:', tsecrlist);
                                    $('#maincontactcedula_c').val(tsecrlist.data[0].cedula_c);
                                    $('#maincontactfirstname_c').val(tsecrlist.data[0].first_name);
                                    $('#maincontactlastname_c').val(tsecrlist.data[0].last_name);
                                    $('#maincontactlastname2_c').val(tsecrlist.data[0].lastname2_c);
                                    $('#maincontactfirstname_c').focus();
                                    $('#maincontactfirstname_c').trigger('keypress');
                                });
                            }
                        });
                    }
                    // only comment out during testing please
                    // observer.disconnect();
                }
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
            // characterData: true,
            subtree: true
        };
        // pass in the target node, as well as the observer options
        observer.observe(target, config);
    }
    // end observer
}();
//eof
