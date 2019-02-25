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
                if (crmEditView.module.value == 'AOS_Quotes') {
                    // input field size
                    $("input[name^='product_name']").css("width", "400px");
                    // search bar below input field
                    $(".yui-ac-content").css("width", "600px");
                    console.log("Bussines logic observer, Inputs product_name{#} size set to 400px. '%s' '%s' '%s'", 'aos_quotes', 'lx-product-name-size.js', '!function()');
                    // if needed only once, you can stop observing with observer.disconnect();
                    // observer.disconnect();
                    // if needed more, add some element and check its existence.
                    // $("#some-id").append('<div id="some-id-lxajaxed"/>');
                }
            }
        }
    });
    // Observer target
    var target = document.querySelector('#content'); //uncomment to run
    if (target == null) {
        //This part if for the Despacho app iFrame
        var target = document.querySelector('#EditView'); //uncomment to run
    }
    // configuration of the observer:
    // NOTE: At the very least, childList, attributes, or characterData must be set to true. Otherwise, "An invalid or illegal string was specified" error is thrown.
    var config = {
        attributes: true,
        childList: true,
        characterData: true,
        subtree: true
    };
    // pass in the target node, as well as the observer options
    observer.observe(target, config); // uncomment to run
    // end observer

}();
