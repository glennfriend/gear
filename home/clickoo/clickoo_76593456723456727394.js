
//
var _clickoo_trace = function(msg, name)
{
    if (!msg) {
        return;
    }

    var info = '?n=' + name + '&m=' + encodeURIComponent(msg);

    //
    var element = document.createElement("script");
    element.type = 'text/javascript';
    element.src = '/gear/clickoo/' + info;
    document.getElementsByTagName("body")[0].appendChild(element);

    /*
    var img = document.createElement("img");
    img.style.display = 'none';
    img.src = '/gear/clickoo/' + info;
    document.getElementsByTagName("body")[0].appendChild(img);
    */
};


//
var _clickoo_listen = function(e)
{
    var obj = window['ClickooValues'] || {};
    if ( Object.prototype.toString.call( obj ) !== '[object Object]' ) {
        return;
    }

    // init
    if ( !obj.name ) {
        obj.name = 'default';
    }
    if ( !obj.filter ) {
        obj.filter = [];
    }
    if ( true !== obj.debug && false !== obj.debug ) {
        obj.debug = false;
    }

    var msg=[];

    try {
        var tmp = e.target.id ? '#'+e.target.id : '';
        if ( tmp ) {
            msg.push(tmp);
        }
    } catch(e) {}

    try {
        if ( e.target.className ) {
            msg.push('.' + e.target.className.trim().split(' ').join(' .'));
        }
    } catch(e) {}

    try {
        if ( e.target.href && 'javascript:void(0);' != e.target.href) {
            msg.push('href=' + e.target.href);
        }
    } catch(e) {}

    try {
        if ( e.target.src ) {
            msg.push('src=' + e.target.src);
        }
    } catch(e) {}

    var str = msg.join(' ').trim();
    if ( !str ) {
        // 如果沒有資料, 就不需要送出訊息
        return;
    }

    // filter
    var isSendMessage = false;
    if ( 0 == obj.filter.length ) {
        isSendMessage = true;
    }
    else {
        for( filter in obj.filter ) {
            if ( -1 != str.indexOf(filter) ) {
                isSendMessage = true;
                break;
            }
        }
    }

    if ( obj.debug === true ) {
        console.log(str);
    }
    if ( isSendMessage ) {
        _clickoo_trace( str, obj.name.trim() );
    }
    
};

//
document.addEventListener('click', _clickoo_listen );
