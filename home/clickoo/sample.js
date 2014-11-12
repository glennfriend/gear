/*
※該 script 必須放置在網頁最下方

使用方式

1. 記錄全部 click event, 暫時不推薦該方式, 因為能 click 的地方太多, 資訊過於雜亂)

    (function(v) {
        var w=window, d=document, o='script';
        w['ClickooObject']='clickoo';
        w['ClickooValues']=v;
        var f = '/gear/clickoo/clickoo.js';
        var ga = d.createElement(o); ga.type = 'text/javascript'; ga.async = 1; ga.src = f;
        var s = d.getElementsByTagName(o)[0]; s.parentNode.insertBefore(ga, s);
    })();

2. 只記錄 id=submitButton 跟 class=selectFaceBox 兩種 click event

    (function(v) {
        var w=window, d=document, o='script';
        w['ClickooObject']='clickoo';
        w['ClickooValues']=v;
        var f = '/gear/clickoo/clickoo.js';
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = 1; ga.src = f;
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })({
        'name': 'test-page-a',
        'filter': ['#submitButton','.selectFaceBox'],
        'debug': true
    });

*/