"use strict";

if( typeof(phpjs) == "undefined" ) {
    console.log('phpc.js error - please load phpjs first');
}

/**
 *  PHP javaScript chain
 *
 *  通識
 *      - 在輸入的型別錯誤時, 值將會修改為 null
 *
 *
 *
 */
(function() {

    // --------------------------------------------------------------------------------
    //  extend setup
    // --------------------------------------------------------------------------------

    var extend = {};

    // phpc version.
    extend.VERSION = '1.0.1';

    // --------------------------------------------------------------------------------
    //  Output Functions
    // --------------------------------------------------------------------------------

    // 以輸出為目的回傳值
    // 當值為 null 時, 可以選擇性回傳 defaultValue 
    // boolean 會回傳 1 or 0
    // 
    extend.put = function( defaultValue ) {
        var v = this.value;
        if ( v === true ) {
            return 1;
        }
        else if ( v === false ) {
            return 0;
        }

        if ( phpjs.is_nan(v) || phpjs.is_undefined(v) || phpjs.is_empty(v) ) {
            v = null;
        }

        if ( v === null && !phpjs.is_empty(defaultValue) ) {
            return defaultValue;
        }
        return v;
    };


    // --------------------------------------------------------------------------------
    //  String Collection Functions
    // --------------------------------------------------------------------------------

    // js trim
    extend.trim = function() {
        this.value = ( phpjs.trim(this.value) );
        return this;
    };

    var list = ['strtolower','ucwords'];
    phpjs.foreach( list, function(method) {
        extend[method] = function()
        {
            this.value = phpjs[method]( this.value );
            return this;
        }
    });


    // --------------------------------------------------------------------------------
    //  Array Collection Functions
    // --------------------------------------------------------------------------------

    var list = ['min','max'];
    phpjs.foreach( list, function(method) {
        extend[method] = function()
        {
            this.value = phpjs[method]( this.value );
            return this;
        }
    });


    // --------------------------------------------------------------------------------
    //  phpc setup
    // --------------------------------------------------------------------------------

    // Establish the root object, `window` in the browser, or `global` on the server.
    var root = this;

    var phpc = function(obj) { 
        extend.value = obj;
        return extend;
    };

    // add to the global object.
    root['phpc'] = phpc;

}).call(this);




/*
    sample:

        phpc(true).value;    // true
        phpc(false).value;   // false

        phpc(true).put();    // 1
        phpc(false).put();   // 0

        phpc([3,1,2]).min().put();

        phpc(" HELLO WORLD ").trim().strtolower().ucwords().put();

*/
