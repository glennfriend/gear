<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        body {
            line-height:24px;
        }
    </style>
    <script type="text/javascript" charset="utf-8" src="dist/jquery/jquery-1.11.1.js"></script>
    <script type="text/javascript" charset="utf-8" src="dist/jquery/jquery.cookie.js"></script>
</head>
<body>

    <div id="master">cookies list</div>
    <div id="other">cookies list</div>

    <script type="text/javascript" charset="utf-8">
        "use strict";

        /**
         *  1 and 0 切換
         */
        function changeValue( obj, itemString )
        {
            var option = {
                'path':'/'
            };
            var itemString = itemString || '';
            var items = itemString.split(',');
            var value = $.cookie(obj.value);

            //
            var nextValue = 0;
            for ( var index in items ) {
                index = parseInt(index);
                items[index] = items[index].trim();

                if ( value===items[index] ) {

                    if( typeof items[index+1] !== "undefined" ) {
                        nextValue = items[index+1];
                        break;
                    }
                    else {
                        nextValue = items[0];
                        break;
                    }
                }
            }

            if ( nextValue !== 0 ) {
                $.cookie(obj.value, nextValue);
            }
            else {
                $.cookie(obj.value, items[0] );
            }

            if( $.cookie(obj.value)==0 ) {
                $.removeCookie(obj.value);
            }

            reflashMasterCookies();
            reflashOtherCookies();
        }

        /**
         *  reflash Cookies
         */
        function reflashMasterCookies()
        {
            var data = <?php echo json_encode(getList()); ?>;
            var whitelist = <?php echo json_encode(getKeywordList()); ?>;
            
            var html = '';
            for( var i=0; i<whitelist.length; i++ ) {
                var key = whitelist[i].trim();
                var value = $.cookie(key);
                var param = data[key].join(',');
                var show = '<input type="button" value="'+ key +'" onclick="changeValue(this,\''+ param +'\');" />';
                if ( typeof value === 'undefined' ) {
                    value = '<span style="color:red">'+ value +'</span>';
                }
                html += '<li>' + show + ' = ' + value + '</li>';
            }

            var display = document.getElementById('master');
            display.innerHTML = '<ul>'+ html +'</ul>';
        }

        /**
         *  reflash Cookies
         */
        function reflashOtherCookies()
        {
            var whitelist = <?php echo json_encode(getKeywordList()); ?>;

            var list = document.cookie.split(";");
            var html = '';
            for( var i=0; i<list.length; i++ ) {
                var cookies = list[i].split('=');
                var key     = cookies[0].trim();
                var value   = cookies[1].trim();
                if ( in_array( key, whitelist ) ) {
                    continue;
                }
                html += '<li>' + key + ' = ' + value + '</li>';
            }

            var display2 = document.getElementById('other');
            display2.innerHTML = '<ul>'+ html +'</ul>';
        }

        function in_array (needle, haystack, argStrict) {
            // Checks if the given value exists in the array  
            // 
            // version: 1109.2015
            // discuss at: http://phpjs.org/functions/in_array
            // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
            // +   improved by: vlado houba
            // +   input by: Billy
            // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
            // *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
            // *     returns 1: true
            // *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
            // *     returns 2: false
            // *     example 3: in_array(1, ['1', '2', '3']);
            // *     returns 3: true
            // *     example 3: in_array(1, ['1', '2', '3'], false);
            // *     returns 3: true
            // *     example 4: in_array(1, ['1', '2', '3'], true);
            // *     returns 4: false
            var key = '',
                strict = !! argStrict;

            if (strict) {
                for (key in haystack) {
                    if (haystack[key] === needle) {
                        return true;
                    }
                }
            } else {
                for (key in haystack) {
                    if (haystack[key] == needle) {
                        return true;
                    }
                }
            }

            return false;
        }

        /**
         *  init
         */
        $(function() {
            reflashMasterCookies();
            reflashOtherCookies();
        });

    </script>

</body>
</html>
<?php

    function getList()
    {
        return array(
            'isSwitch1' => array(1,0),
            'isSwitch2' => array(1,0),
            'isSwitch3' => array(1,0),
            'atDebug'   => array('all','important',0),
            'atColor'   => array('red','green','blue',0),
        );
    }

    function getKeywordList()
    {
        $list = getList();
        $results = array();
        foreach ( $list as $keyword => $items ) {
            $results[] = $keyword;
        }
        return $results;
    }

?>