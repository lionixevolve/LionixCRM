// LionixCRM lx javascript object definition
var lx = {
    "field": {},
    "upload": {},
    "lionixCRM": {
        "config": {}
    },
    "account": {},
    "contact": {},
    "opportunity": {}
};
// LionixCRM required javascript files array list
lx.lionixCRM.scripts = new Array();
//SuiteCRM has jQuery preloaded don't include it.
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/ConsoleDummy.min.js"); //This script allows to leave console.log and friends on production enviroments
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/momentjs/moment.min.js");
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/momentjs/moment-timezone-with-data.min.js");
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/momentjs/locales/x-pseudo.js"); //momentjs x-pseudo usefult for testing
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/momentjs/locales/es-do.js"); //momentjs Spanish for Dominican Republic
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/momentjs/locales/pt-br.js"); //momentjs Portuguese for Brazil
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/momentjs/locales/fr-ca.js"); //momentjs French for Canada
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/momentjs/locales/es-cr.js"); //LionixCRM Spanish for Costa Rica is based on momentjs es-do locale
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/lx-hide-global-search-while-not-working.js");
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/lionixcrm/lx-get-config-option.js");
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/lionixcrm/lx-load-all-config-options.js");
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/field/lx-validate-crm-field.js");
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/field/lx-show-crm-field.js");
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/field/lx-change-visibility-crm-field.js");
lx.lionixCRM.scripts.push("custom/lionix/js/jquery-plugins/fn.clearSelect.js");
lx.lionixCRM.scripts.push("custom/lionix/js/jquery-plugins/fn.fillSelect.js");
lx.lionixCRM.scripts.push("custom/lionix/js/jquery-plugins/fn.lxtest.js");
lx.lionixCRM.scripts.push("custom/lionix/js/jquery-plugins/jquery.form.js");
lx.lionixCRM.scripts.push("custom/lionix/js/jquery-plugins/toastr.js");
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/lionixcrm/lx-check-crm-environment.js");
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/upload/lx-upload-files-in-editview.js");
lx.lionixCRM.scripts.push("custom/lionix/js/business-logic/notes/lx-remove-contacts-from-related-to.js");
lx.lionixCRM.scripts.push("custom/lionix/js/business-logic/opportunities/lx-main-contact.js");
lx.lionixCRM.scripts.push("custom/lionix/js/business-logic/opportunities/lx-account-name.js");
lx.lionixCRM.scripts.push("custom/lionix/js/jquery-plugins/select2.min.js");
lx.lionixCRM.scripts.push("custom/lionix/js/jquery-plugins/select2-lionixcrm.js");
lx.lionixCRM.scripts.push("custom/lionix/js/miscellaneous/lx-chat.js");
// when developing use the "activate" cache mode, like this:
// lx.lionixCRM.scripts.push(["custom/lionix/js/{your-developing-script}",true]);
// preload al LionixCRM required javascript files array list for blazing speed
lx.lionixCRM.scripts.forEach(function(element) {
    if (Array.isArray(element)) {
        current_script = element[0];
        cache = element[1];
    } else {
        current_script = element;
        cache = false;
    }
    var preloadLink = document.createElement("link");
    preloadLink.href = current_script //"custom/lionix/js/miscellaneous/lionixcrm/loadScript.js";
    preloadLink.rel = "preload";
    preloadLink.as = "script";
    document.head.appendChild(preloadLink);
});
//load js on cascade
var script = document.createElement("script");
script.type = "text/javascript";
script.src = "custom/lionix/js/miscellaneous/lionixcrm/loadScript.js"; //+ "?t=" + new Date().getTime(); //prevent caching;;
document.head.appendChild(script);
script.onload = function() {
    scripts = lx.lionixCRM.scripts;
    function load(i) {
        if (i < scripts.length) {
            if (Array.isArray(scripts[i])) {
                current_script = scripts[i][0];
                cache = scripts[i][1];
            } else {
                current_script = scripts[i];
                cache = false;
            }
            lx.lionixCRM.loadScript(current_script, cache, function() {
                load(++i);
            });
        } else {
            $('head').append('<link rel="stylesheet" type="text/css" href="custom/lionix/css/spanish-acl-roles.css" />');
            $('head').append('<link rel="stylesheet" type="text/css" href="custom/lionix/css/toastr.css" />');
            $('head').append('<link rel="stylesheet" type="text/css" href="custom/lionix/css/select2.min.css" />');
            $('head').append('<link rel="stylesheet" type="text/css" href="custom/lionix/css/select2-bootstrap.min.css" />');
            $('head').append('<style type="text/css">select[multiple] ~ .select2-container .select2-results__option[aria-selected=true] {display: none;}</style>');
            $('head').append('<link rel="stylesheet" type="text/css" href="custom/lionix/css/floating-div-for-excel-file.css" />');
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
