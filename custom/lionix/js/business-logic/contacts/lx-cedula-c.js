//  This file containts opportunities bussines logic
//  function definitions section
lx.contact.renderContactDuplicates = function(duplicates) {
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
            // Client specific only fields
            // duplicate_detail += ' - ' + element.{fieldname};
            // Client specific only fields
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
            console.log("Redirecting to contacts Editview: - " + window.location.origin + window.location.pathname + "?module=Contacts&return_module=Contacts&action=EditView&record=" + this.id);
            window.location.href = window.location.origin + window.location.pathname + "?module=Contacts&return_module=Contacts&action=EditView&record=" + this.id;
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
                if (crmEditView.module.value == 'Contacts') {
                    if ($("#cedula_c_lxajaxed").length == 0) {
                        console.log("Bussines logic '%s' '%s' '%s' '%s'", 'contacts', 'lx-account-name.js', '!function()', 'initial');
                        console.log("Loading Lionix code on EditView on module:", crmEditView.module.value);
                        $("#cedula_c").append('<div id="cedula_c_lxajaxed"/>');
                        $('#cedula_c').on("keyup.tsecr_search_results", function() {
                            console.log('key up ->', String.fromCharCode(event.which));
                            if ($(this).val().length != 9) {
                                $('#first_name').val('');
                                $('#last_name').val('');
                                $('#lastname2_c').val('');
                            } else {
                                typed_cedula = $(this).val();
                                lx.lionixCRM.getTSECRData({
                                    // Infoticos must be on same database server this SuiteCRM instance.
                                    "focusfieldname": this.id,
                                    "cedula_c": typed_cedula
                                }).then(function(tsecrlist) {
                                    console.log('After query to lx.lionixCRM.getTSECRData:', tsecrlist);
                                    $('#cedula_c').val(tsecrlist.data[0].cedula_c);
                                    $('#first_name').val(tsecrlist.data[0].first_name);
                                    $('#last_name').val(tsecrlist.data[0].last_name);
                                    $('#lastname2_c').val(tsecrlist.data[0].lastname2_c);
                                    $('#first_name').focus();
                                    $('#first_name').trigger('keypress');
                                });
                            }
                        });

                        $('#first_name, #last_name, #lastname2_c').on("keypress.duplicate_results_list", function() {
                            ffn = $('#first_name');
                            fln = $('#last_name');
                            fln2 = $('#lastname2_c');
                            femail = $('#maincontactemailaddress_c');
                            fced = $('#maincontactcedula_c');
                            if ($(ffn).val().length > 2 && ($(fln).val().length > 2 || $(fln2).val().length > 2)) {
                                if ($('#main_contact_duplicates_' + this.id).length == 0) {
                                    // width: 750px can be changed in each Client
                                    width = 750;
                                    $(this).before('<div id="main_contact_duplicates_' + this.id + '" class="main_contact_duplicates yui-ac-container" style="position: relative; left: 200px; top:0px;"><div class="yui-ac-content" style="width: ' + width + 'px; height: 60px; display: none; "><div class="yui-ac-bd">Posibles duplicados encontrados<ul id="#ul_' + this.id + '"></ul></div></div></div>');
                                }
                                $('#first_name, #last_name, #lastname2_c').off("focusout.duplicate_results_list");
                                $('#first_name, #last_name, #lastname2_c').on("focusout.duplicate_results_list", function() {
                                    $('.main_contact_duplicates .yui-ac-content').hide(500);
                                });
                                lx.lionixCRM.getContactDuplicates({
                                    "fieldname": this.id, "first_name": $(ffn).val(), "last_name": $(fln).val(), "lastname2_c": $(fln2).val()
                                    // "cedula_c": $(fced).val(),
                                    // "email_address": $(femail).val()
                                }).then(function(duplicates) {
                                    lx.contact.renderContactDuplicates(duplicates)
                                });
                            }
                        });

                    }
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
