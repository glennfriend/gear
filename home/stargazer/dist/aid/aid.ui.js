/**
 *  aid - 幫助的工具集合
 *  
 *  @see jquery 2.0.3+
 */
var aid = aid || {};


/**
 *  aid ui
 *  經常會用使用到的界面 library
 *
 *
 */
aid.ui = aid.ui || {};


/**
 *  檢查某一段 HTML 並轉換裡面的內容, 使其看起來有排序的功能
 *  但其實並沒有實際的排序功能
 *
 *  根據參數 sortField, sortBy 來設定某個欄位的排序狀態
 *  sortField 欄位名稱例如 id, userEmailAddress
 *  sortBy    共有兩種 asc, desc
 *  
 *  檢查符合 js_field_* 的 class, 加入排序連結與顏色 ( 例如 "js_field_userEmailAddress" )
 *  
 *  @param selector,    jquery selector string
 *  @param baseUrl
 *  @param sortField,   default sort field (EX. userEmailAddress )
 *  @param sortBy,      asc, desc
 *
 */
aid.ui.fieldSort = function( selector, baseUrl )
{
    //
    //  重新整理網址, 濾除重覆的參數
    //
    //  EX.
    //      from: a=1&b=2&a=3 
    //      to:   a=3&b=2
    //
    //  @see phpjs.parse_url
    //  @param uri string
    //  @return uri string
    //
    var filterUriParams = function( uri )
    {
        var urlInfo = phpjs.parse_url(uri);
        
        var urls = urlInfo.query.split('&');
        var urlArray = [];
        for ( var j = 0; j < urls.length; j++ ) {
            var tmp = urls[j].split('=');
            urlArray[tmp[0]] = tmp[1];
        }
        
        uri = urlInfo.path;
        var urlExtend = '';
        phpjs.foreach( urlArray, function( key, value ){
            urlExtend += '&' + key + '=' + value;
        });
        if ( urlExtend ) {
            uri += '?' + urlExtend;
        }
        return uri;
    }

    //
    // 找出符合排序規則的 tag;
    //
    var firePrefix    = 'js_field_';
    var firePrefixLen = firePrefix.length;
    var findTags = [];
    var classes = $(selector);
    for (var i = 0; i < classes.length; i++) {
        var tagClass = $(classes[i]).attr('class');
        if (!tagClass) {
            continue;
        }
        var tagClasses = tagClass.split(' ');
        for (var j = 0; j < tagClasses.length; j++) {
            var className = tagClasses[j];
            if ( className.substr(0,firePrefixLen) === firePrefix ) {
                findTags[ findTags.length ] = {
                    'element': $(classes[i]),
                    'name':    className.substr(firePrefixLen),
                }
            }
        }
    }


    //
    // 將符合排序規則的 tag class 加入連結
    // 找出符合現在已排序規則的 tag, 並且加上圖示符號
    //
    var baseUrl     = baseUrl || '';
    var sortField   = $('[name="sortField"]').val();
    var sortBy      = $('[name="sortBy"]').val();

    for( var i = 0; i < findTags.length; i++ ) {
        var $elm = findTags[i].element;
        var name = findTags[i].name;
        var url = baseUrl + '&sortField=' + name;

        // 加上圖示符號, 加上 sortBy
        if ( name === sortField && sortBy === 'asc' ) {
            $elm.append('<div style="float:right"><i class="fam-arrow-up"></i>');
            url += '&sortBy=desc';
        }
        else if ( name === sortField && sortBy === 'desc') {
            $elm.append('<div style="float:right"><i class="fam-arrow-down"></i>');
            url += '&sortBy=asc';
        }
        else {
            url += '&sortBy=asc';
        }

        // 把重覆的參數濾除
        url = filterUriParams(url);

        $elm.html(
            $('<a></a>')
                .attr( 'href', url )
                .html( $elm.html() )
                .css(' color',' blue' )
       );
    }

}


/**
 *  將自訂搜尋的資料代入欄位中
 *  會自動做 selected 的 match
 *  
 *  @param findValues, json, 傳入值 or focus option value
 *  @param findOptions, json, select option values
 */
aid.ui.customFind = function( findValues, findOptions )
{

    // 將 select option 建入選項資料
    for( var name in findOptions ) {
        if ( !findOptions.hasOwnProperty(name) ) {
            continue;
        }

        var value = findOptions[name];
        var $obj = $('[name="findKeys['+ name +']"]');

        // 空白值表示沒有任何 filter
        $obj.append(new Option('',''));

        // append option
        var fieldOptions = findOptions[name];
        for( var key in fieldOptions ) {
            $obj.append(new Option(key, fieldOptions[key]));
        }
    }

    // 將有值的欄位都設定完成, 包含 input and select
    for( var name in findValues ) {
        if ( !findValues.hasOwnProperty(name) ) {
            continue;
        }
        var value = findValues[name];
        var $obj = $('[name="findKeys['+ name +']"]');
        $obj.val( value );
    }

}


