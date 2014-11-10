"use strict";

/**
 *  PHP JavaScript
 *  
 */
(function() {

    var phpjs = {};

    // --------------------------------------------------------------------------------
    //  is Functions
    // --------------------------------------------------------------------------------

    // @see http://phpjs.org/functions/empty/
    // 2013.10
    phpjs.is_empty = function(mixed_var) {
        var undef, key, i, len;
        var emptyValues = [undef, null, false, 0, "", "0"];
        for (i = 0, len = emptyValues.length; i < len; i++) {
            if (mixed_var === emptyValues[i]) {
                return true;
            }
        }
        if (typeof mixed_var === "object") {
            for (key in mixed_var) {
                return false;
            }
            return true;
        }
        return false;
    };

    // 
    phpjs.is_element = function(obj) {
        return !!(obj && obj.nodeType == 1);
    };

    //
    phpjs.is_array = Array.isArray || function(obj) {
        return Object.prototype.toString.call(obj) == '[object Array]';
    };

    // 
    phpjs.is_object = function(obj) {
        return obj === Object(obj);
    };

    // 
    phpjs.is_function = function(obj) {
        return Object.prototype.toString.call(obj) == '[object Function]';
    };

    // 
    phpjs.is_string = function(obj) {
        return Object.prototype.toString.call(obj) == '[object String]';
    };

    // 
    phpjs.is_number = function(obj) {
        return Object.prototype.toString.call(obj) == '[object Number]';
    };

    // 
    phpjs.is_nan = function(obj) {
        // `NaN` is the only value for which `===` is not reflexive.
        return obj !== obj;
    };

    // 
    phpjs.is_boolean = function(obj) {
        return obj === true || obj === false || Object.prototype.toString.call(obj) == '[object Boolean]';
    };

    // 
    phpjs.is_null = function(obj) {
        return obj === null;
    };

    // 
    phpjs.is_undefined = function(obj) {
        return obj === void 0;
    };

    // --------------------------------------------------------------------------------
    //  base Functions
    // --------------------------------------------------------------------------------

    // php style foreach, modify by jQuery
    //
    //  phpjs.foreach( object_or_array, function( key, value ) {
    //      console.log( key );
    //      console.log( value );
    //      console.log( '----' );
    //  });
    //
    phpjs.foreach = function( obj, callback ) {
        var value,
            i = 0,
            length = obj.length,
            callbackLength = callback.length,
            isArray = phpjs.is_array( obj );

        if ( isArray && callbackLength == 1 ) {
            // get value only
            for ( ; i < length; i++ ) {
                value = callback.call( obj[ i ], obj[ i ] );
                if ( value === false ) { break; }
            }
        }
        // 不使用, 因為在 array['aaa']=10 這種方式的時候, 取得資料不正確, length 會是 0
        // 請參考 jQuery 再做處理
        // 請再做測試!!
        // else if ( isArray && callbackLength == 2 ) {
        //     // get index and value
        //     for ( ; i < length; i++ ) {
        //         value = callback.call( obj[ i ], i, obj[ i ] );
        //         if ( value === false ) { break; }
        //     }
        // }
        else {
            for ( i in obj ) {
                value = callback.call( obj[ i ], i, obj[ i ] );
                if ( value === false ) { break; }
            }
        }
        return obj;
    };

    // --------------------------------------------------------------------------------
    //  string Functions
    // --------------------------------------------------------------------------------

    // js trim
    phpjs.trim = function(str, charlist) {
        if ( phpjs.is_empty(str) ) { return '' }
        return (str += '').trim();
    };

    // js toLowerCase
    phpjs.strtolower = function(str) {
        if ( phpjs.is_empty(str) ) { return '' }
        return str.toLowerCase();
    };

    // php ucwords
    phpjs.ucwords = function( str ) {
        if ( phpjs.is_empty(str) ) { return '' }
        return (str + '').replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) {
            return $1.toUpperCase();
        });
    };

    // --------------------------------------------------------------------------------
    //  url Functions
    // --------------------------------------------------------------------------------

    // php parse_url
    phpjs.parse_url = function (str, component) {
        var key = ['source', 'scheme', 'authority', 'userInfo', 'user', 'pass', 'host', 'port', 
                            'relative', 'path', 'directory', 'file', 'query', 'fragment'],
            ini = (this.php_js && this.php_js.ini) || {},
            mode = (ini['phpjs.parse_url.mode'] && 
                ini['phpjs.parse_url.mode'].local_value) || 'php',
            parser = {
                php: /^(?:([^:\/?#]+):)?(?:\/\/()(?:(?:()(?:([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?()(?:(()(?:(?:[^?#\/]*\/)*)()(?:[^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
                strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
                loose: /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/\/?)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ // Added one optional slash to post-scheme to catch file:/// (should restrict this)
            };

        var m = parser[mode].exec(str),
            uri = {},
            i = 14;
        while (i--) {
            if (m[i]) {
              uri[key[i]] = m[i];  
            }
        }

        if (component) {
            return uri[component.replace('PHP_URL_', '').toLowerCase()];
        }
        if (mode !== 'php') {
            var name = (ini['phpjs.parse_url.queryKey'] && 
                    ini['phpjs.parse_url.queryKey'].local_value) || 'queryKey';
            parser = /(?:^|&)([^&=]*)=?([^&]*)/g;
            uri[name] = {};
            uri[key[12]].replace(parser, function ($0, $1, $2) {
                if ($1) {uri[name][$1] = $2;}
            });
        }
        delete uri.source;
        return uri;
    }

    // --------------------------------------------------------------------------------
    //  array Functions
    // --------------------------------------------------------------------------------

    // js max
    phpjs.max = function(arr) {
        if (!phpjs.is_array(arr)) { return ''; }
        return Math.max.apply(Math, arr);
    };

    // js min
    phpjs.min = function(arr) {
        if (!phpjs.is_array(arr)) { return ''; }
        return Math.min.apply(Math, arr);
    };

    // --------------------------------------------------------------------------------
    //  phpjs setup
    // --------------------------------------------------------------------------------

    // Establish the root object, `window` in the browser, or `global` on the server.
    var root = this;

    // add to the global object.
    root['phpjs'] = phpjs;

}).call(this);
