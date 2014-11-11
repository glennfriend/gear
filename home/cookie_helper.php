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
    <!--
        http://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js
        http://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js
    -->
</head>
<body>

    <div id="master">cookies list</div>
    <div id="other">cookies list</div>

    <script type="text/javascript" charset="utf-8">
        "use strict";

        /**
         *  chagen value to next
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
            var data      = <?php echo json_encode(getList()); ?>;
            var whitelist = <?php echo json_encode(getKeywordList()); ?>;
            
            var html = '';
            for( var i=0; i<whitelist.length; i++ ) {
                var key = whitelist[i].trim();
                var value = $.cookie(key);
                var param = data[key].value.join(',');
                var title = data[key].title;
                var show = '<input type="button" title="'+ title +'" value="'+ key +'" onclick="changeValue(this,\''+ param +'\');" />';
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

        /**
         *  in_array by phpjs
         */
        function in_array (needle, haystack, argStrict) {
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
            'is_sb_cs' => array(
                'value' => array(1,0),
                'title' => 'can add to cart & buy'
            ),
            'cookie_phone_num' => array(
                'value' => array('2223334444','1234567890',0),
                'title' => 'show phone number about showroom'
            ),
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