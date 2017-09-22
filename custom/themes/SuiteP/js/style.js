// LionixCRM required javascript files array list
var lxscripts = new Array();
//SuiteCRM has jQuery preloaded don't include it.
lxscripts.push("custom/lionix/js/miscellaneous/ConsoleDummy.min.js"); //This script allows to leave console.log and friends on production enviroments
lxscripts.push("custom/lionix/js/miscellaneous/momentjs/moment.min.js");
lxscripts.push("custom/lionix/js/miscellaneous/momentjs/moment-timezone-with-data.min.js");
lxscripts.push("custom/lionix/js/miscellaneous/momentjs/locales/x-pseudo.js"); //momentjs x-pseudo usefult for testing
lxscripts.push("custom/lionix/js/miscellaneous/momentjs/locales/es-do.js"); //momentjs Spanish for Dominican Republic
lxscripts.push("custom/lionix/js/miscellaneous/momentjs/locales/pt-br.js"); //momentjs Portuguese for Brazil
lxscripts.push("custom/lionix/js/miscellaneous/momentjs/locales/fr-ca.js"); //momentjs French for Canada
lxscripts.push("custom/lionix/js/miscellaneous/momentjs/locales/es-cr.js"); //LionixCRM Spanish for Costa Rica is based on momentjs es-do locale
lxscripts.push("custom/lionix/js/miscellaneous/lx-hide-global-search-while-not-working.js");
lxscripts.push("custom/lionix/js/miscellaneous/lx-check-crm-environment.js");
lxscripts.push("custom/lionix/js/miscellaneous/lx-validate-crm-field.js");
lxscripts.push("custom/lionix/js/miscellaneous/lx-show-crm-field.js");
lxscripts.push("custom/lionix/js/miscellaneous/lx-change-visibility-crm-field.js");
lxscripts.push("custom/lionix/js/miscellaneous/lx-chat.js");
lxscripts.push("custom/lionix/js/jquery-plugins/fn.clearSelect.js");
lxscripts.push("custom/lionix/js/jquery-plugins/fn.fillSelect.js");
lxscripts.push("custom/lionix/js/jquery-plugins/fn.lxtest.js");
lxscripts.push("custom/lionix/js/jquery-plugins/toastr.js");
lxscripts.push("custom/lionix/js/business-logic/opportunities/lx-main-contact.js");
lxscripts.push("custom/lionix/js/business-logic/opportunities/lx-account-name.js");
lxscripts.push("custom/lionix/js/jquery-plugins/select2.min.js");
lxscripts.push("custom/lionix/js/jquery-plugins/select2-lionixcrm.js");
// when developing use the "activate" cache mode, like this:
// lxscripts.push(["custom/lionix/js/{your-developing-script}",true]);
// preload al LionixCRM required javascript files array list for blazing speed
lxscripts.forEach(function(element) {
    if (Array.isArray(element)) {
        current_script = element[0];
        cache = element[1];
    } else {
        current_script = element;
        cache = false;
    }
    var preloadLink = document.createElement("link");
    preloadLink.href = current_script //"custom/lionix/js/miscellaneous/loadScript.js";
    preloadLink.rel = "preload";
    preloadLink.as = "script";
    document.head.appendChild(preloadLink);
});
//load js on cascade
var script = document.createElement("script");
script.type = "text/javascript";
script.src = "custom/lionix/js/miscellaneous/loadScript.js"; //+ "?t=" + new Date().getTime(); //prevent caching;;
document.head.appendChild(script);
script.onload = function() {
        function load(i) {
            if (i < scripts.length) {
                loadScript(scripts[i], function() {
                    load(++i);
                });
    scripts = lxscripts;
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
