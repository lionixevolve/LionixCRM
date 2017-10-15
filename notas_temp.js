lx.opportunity.getMainContactDuplicates = function(fieldname) {
        // lx.opportunity.getMainContactDuplicates = function(fieldname, firstName, lastName, lastName2) {
        return new Promise(function(resolve, reject) {
            resolve({
                "id": fieldname,
                "data": ['Sakura', 'Naruto', 'Sasuke', 'Kakashi']
            });
        });
    }

    ! function() {
        $('#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c').off("keypress.duplicate_results_list")
        $('#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c').on("keypress.duplicate_results_list", function() {
            ffn = $('#maincontactfirstname_c')
            fln = $('#maincontactlastname_c')
            fln2 = $('#maincontactlastname2_c')
            femail = $('#maincontactemailaddress_c')
            if ($(ffn).val().length > 2 && ($(fln).val().length > 2 || $(fln2).val().length > 2)) {
                if ($('#main_contact_duplicates_' + this.id).length == 0) {
                    $(this).after('<div id="main_contact_duplicates_' + this.id + '" class="main_contact_duplicates yui-ac-container" style="position: relative; left: 0px; top: 2px;">    <div class="yui-ac-content" style="width: 458px; height: 60px; display: none; ">        <div class="yui-ac-bd">            <ul id="#ul_' + this.id + '">                <li style="">Sakura</li>                <li style="">Naruto</li>                <li style="">Sasuke</li>            </ul>        </div>    </div></div>');
                }
                lx.opportunity.getMainContactDuplicates(this.id).then(function(data) {
                    console.log("array devulto:", data);
                    $('.main_contact_duplicates ul li').remove();
                    data.data.forEach(function(element) {
                        $('.main_contact_duplicates ul').append('<li>' + element + '</li>');
                    });
                    newh = data.data.length * 20;
                    $('#main_contact_duplicates_' + data.id + ' .yui-ac-content').css("height", newh + "px");
                    $('#main_contact_duplicates_' + data.id + ' .yui-ac-content').show(500);
                    $('.main_contact_duplicates li').off("mouseover.main_contact_duplicates_list");
                    $('.main_contact_duplicates li').on("mouseover.main_contact_duplicates_list", function() {
                        $(this).toggleClass('yui-ac-highlight');
                        $(this).siblings('li').removeClass('yui-ac-highlight');
                    });
                    $('.main_contact_duplicates li').off("click.main_contact_duplicates_list");
                    $('.main_contact_duplicates li').on("click.main_contact_duplicates_list", function() {
                        $('.main_contact_duplicates .yui-ac-content').hide(500);
                    });
                });
            }
            // lo comentado abajo funciona, solo lo comento para que no desaparezca mientras hago mas pruebas
            $('#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c').off("focusout.duplicate_results_list");
            $('#maincontactfirstname_c, #maincontactlastname_c, #maincontactlastname2_c').on("focusout.duplicate_results_list", function() {
                $('.main_contact_duplicates .yui-ac-content').hide(500);
            });
        });
    }()
