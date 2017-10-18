lx.opportunity.getMainContactDuplicates = function(searchObject) {
        so = searchObject;
        return new Promise(function(resolve, reject) {
            var option = "nada";
            var method = "getContactDuplicates";
            var data = {
                "method": method,
                "first_name": so.first_name,
                "last_name": so.last_name,
                "lastname2_c": so.lastname2_c,
                "email_address": so.email_address,
                "cedula_c": so.cedula_c
            };
            $.ajax({
                // beforeSend is a pre-request callback function that can be used to modify the jqXHR.
                beforeSend: function(jqXHR, settings) {
                    console.log("Bussines logic '%s' '%s' '%s' '%s' '%s'", 'all modules', 'lx-lionixcrm-get-contact-duplicates.js', 'lx.opportunity.getMainContactDuplicates', 'ajax beforeSend');
                    console.log("*** start ***");
                    console.log("beforeSend callback:", settings.url);
                }, //end beforeSend
                url: 'lxajax.php',
                type: 'POST',
                data: data,
                // success is a function to be called if the request succeeds.
                success: function(data, status, jqXHR) {
                    console.log("Bussines logic '%s' '%s' '%s' '%s' '%s'", 'all modules', 'lx-lionixcrm-get-contact-duplicates.js', 'lx.opportunity.getMainContactDuplicates', 'ajax success');
                    console.log("success callback:", status);
                    console.log("data:", data);
                    if (data == '') {
                        console.log("Not duplicates found for " + option + " not found.")
                    } else {
                        data = JSON.parse(data);
                        console.log("LionixCRM option (" + option + ") updated.")
                    }
                    resolve({
                        "fieldname": so.fieldname,
                        "data": data
                    });
                }, // end success
                // error is a function to be called if the request fails.
                error: function(jqXHR, status, error) {
                    console.log("Bussines logic '%s' '%s' '%s' '%s' '%s'", 'all modules', 'lx-lionixcrm-get-contact-duplicates.js', 'lx.opportunity.getMainContactDuplicates', 'ajax error');
                    console.log("error callback:", status);
                    console.log("Function lx.lionixCRM.getConfigOption error:", error);
                    reject(error);
                }, // end error
                // complete is a function to be called when the request finishes (after success and error callbacks are executed).
                // complete: function(jqXHR, status) {
                // }, // end complete
                datatype: "text"
            }); // end ajax
        });
    } // end function

    ! function() {
        $('#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c').off("keypress.duplicate_results_list")
        $('#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c').on("keypress.duplicate_results_list", function() {
            ffn = $('#maincontactfirstname_c');
            fln = $('#maincontactlastname_c');
            fln2 = $('#maincontactlastname2_c');
            femail = $('#maincontactemailaddress_c');
            fced = $('#maincontactcedula_c');
            if ($(ffn).val().length > 2 && ($(fln).val().length > 2 || $(fln2).val().length > 2)) {
                if ($('#main_contact_duplicates_' + this.id).length == 0) {
                    $(this).after('<div id="main_contact_duplicates_' + this.id + '" class="main_contact_duplicates yui-ac-container" style="position: relative; left: 0px; top: 52px;"><div class="yui-ac-content" style="width: 650px; height: 60px; display: none; "><div class="yui-ac-bd"><ul id="#ul_' + this.id + '"></ul></div></div></div>');
                }
                lx.opportunity.getMainContactDuplicates({
                    "fieldname": this.id,
                    "first_name": $(ffn).val(),
                    "last_name": $(fln).val(),
                    "lastname2_c": $(fln2).val()
                    // "cedula_c": $(fced).val(),
                    // "email_address": $(femail).val()
                }).then(function(duplicates) {
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
                        newh = duplicates.data.length * 20;
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
                });
            }
            $('#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c').off("focusout.duplicate_results_list");
            $('#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c').on("focusout.duplicate_results_list", function() {
                $('.main_contact_duplicates .yui-ac-content').hide(500);
            });
        });
    }()
