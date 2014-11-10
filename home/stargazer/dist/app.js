"use strict";

/**
 *  app
 *  整理 backend 專案都會使用到的行為模式
 */
var app = app || {};

/**
 *  關閉系統標準的 iframe window 
 */
/*
app.closeIframeWindow = function()
{
    // popup iframe window use by jquery colrbox 
    parent.$.fn.colorbox.close();
}
*/

app.init = {};

/**
 *  qtip basic setting
 *
 *  sample:
 *      置上    'position': {'my': 'bottom center', 'at': 'top center'}
 *      置右    'position': {'my': 'left center',   'at': 'right center'}
 *
 *
 */
app.init.qtip = function( customSetting, selector )
{
    selector = selector || 'span[title]';
    var setting = {
        'content':  {attr: 'title'},
        'style':    {classes: 'qtip-shadow qtip-dark'}
    };


    if ( customSetting != null && typeof customSetting === 'object' ) {
        for( var name in customSetting ) {
            if (customSetting.hasOwnProperty(name)) {
                setting[name] = customSetting[name];
            }
        }
    }

    $(selector).qtip(setting);
}
