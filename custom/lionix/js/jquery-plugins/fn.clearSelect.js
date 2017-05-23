console.log("jQuery plugin $.fn.clearSelect added.");
(function ( $ ) {
    $.fn.clearSelect = function() {
        return this.each(function() {
            if (this.tagName == 'SELECT')
                this.options.length = 0;
        });
    };
}( jQuery ));

// In file references:
//  Chaining
// This works, but there are a couple of things we need to do for our plugin to survive in the real world. One of jQuery's features is chaining, when you link five or six actions onto one selector.
// This is accomplished by having all jQuery object methods return the original jQuery object again (there are a few exceptions: .width() called without parameters returns the width of the selected element, and is not chainable).
// Making our plugin method chainable takes one line of code:
// return this;

// Protecting the $ Alias and Adding Scope
// The $ variable is very popular among JavaScript libraries, and if you're using another library with jQuery, you will have to make jQuery not use the $ with jQuery.noConflict().
// However, this will break our plugin since it is written with the assumption that $ is an alias to the jQuery function.
// To work well with other plugins, and still use the jQuery $ alias, we need to put all of our code inside of an Immediately Invoked Function Expression, and then pass the function jQuery, and name the parameter $

// Minimizing Plugin Footprint
// It's good practice when writing plugins to only take up one slot within $.fn. This reduces both the chance that your plugin will be overridden, and the chance that your plugin will override other plugins.

// Using the each() Method
// Your typical jQuery object will contain references to any number of DOM elements, and that's why jQuery objects are often referred to as collections.
// If you want to do any manipulating with specific elements (e.g. getting a data attribute, calculating specific positions) then you need to use .each() to loop through the elements.

// Accepting Options
// As your plugins get more and more complex, it's a good idea to make your plugin customizable by accepting options. The easiest way to do this, especially if there are lots of options, is with an object literal.

// Further readings:
// https://learn.jquery.com/plugins/basic-plugin-creation/
// http://benalman.com/news/2010/11/immediately-invoked-function-expression/
// http://stevemichelotti.com/mvc-json-jsonresult-and-jquery/
// eof