//load js on cascade
var script = document.createElement("script");
script.type = "text/javascript";
script.src = "custom/lionix/js/miscellaneous/loadScript.js"; //+ "?t=" + new Date().getTime(); //prevent caching;;
document.head.appendChild(script);
script.onload = function() {
        var scripts = new Array();
        //SuiteCRM has jQuery preloaded don't include it.
        scripts.push("custom/lionix/js/miscellaneous/ConsoleDummy.min.js"); //This script allows to leave console.log and friends on production enviroments
        scripts.push("custom/lionix/js/miscellaneous/lx-chat.js");
        scripts.push("custom/lionix/js/miscellaneous/lx-hide-global-search-while-not-working.js");
        scripts.push("custom/lionix/js/miscellaneous/lx-check-crm-environment.js");
        scripts.push("custom/lionix/js/miscellaneous/lx-validate-crm-field.js");
        scripts.push("custom/lionix/js/miscellaneous/lx-show-crm-field.js");
        scripts.push("custom/lionix/js/miscellaneous/lx-change-visibility-crm-field.js");
        scripts.push("custom/lionix/js/jquery-plugins/fn.clearSelect.js");
        scripts.push("custom/lionix/js/jquery-plugins/fn.fillSelect.js");
        scripts.push("custom/lionix/js/jquery-plugins/fn.lxtest.js");
        scripts.push("custom/lionix/js/jquery-plugins/toastr.js");
        scripts.push("custom/lionix/js/business-logic/opportunities/lx-main-contact.js");
        scripts.push("custom/lionix/js/business-logic/opportunities/lx-account-name.js");
        scripts.push("custom/lionix/js/jquery-plugins/select2.min.js");
        scripts.push("custom/lionix/js/jquery-plugins/select2-lionixcrm.js");

        function load(i) {
            if (i < scripts.length) {
                loadScript(scripts[i], function() {
                    load(++i);
                });
            } else {
                $('head').append('<link rel="stylesheet" type="text/css" href="custom/lionix/css/spanish-acl-roles.css" />');
                $('head').append('<link rel="stylesheet" type="text/css" href="custom/lionix/css/toastr.css" />');
                $('head').append('<link rel="stylesheet" type="text/css" href="custom/lionix/css/select2.min.css" />');
                $('head').append('<link rel="stylesheet" type="text/css" href="custom/lionix/css/select2-bootstrap.min.css" />');
                $('head').append('<style type="text/css">select[multiple] ~ .select2-container .select2-results__option[aria-selected=true] {display: none;}</style>');
                $('head').append('<link rel="stylesheet" type="text/css" href="custom/lionix/css/progress-bar.css" />');
                $(document).ready(function() { //Primer document ready
                    console.log("You are running jQuery version:", $.fn.jquery);
                    // Add "Metas" option on main nav menu
                    //Any code you may need please added on another scripts.push(file) on the beginning
                }); //final primer document ready
            } //fin else *all your scripts have loaded, so go ahead and do what you need to do*
        } //fin load(i)
        load(0);
    } //fin custom/lionix/js/loadScript.js
