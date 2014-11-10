/**
 *  aid - 幫助的工具集合
 *  
 *  @see jquery 2.0.3+
 */
var aid = aid || {};


/**
 *  aid effect
 *  常用的效果
 *
 *
 */
aid.effect = aid.effect || {};


/**
 *  按下 button 之後會經過一些時間
 *  在時間之內的過場效果
 *  
 *  @param selector, jquery selector string
 *
 */
aid.effect.buttonStart = function( selector )
{
    $(selector).attr('aiddatabuffer', $(selector).val() );
    $(selector).val('Processing ...');
    $(selector).prop("disabled", true );
}

/**
 *  過場效果結束
 *  
 *  @param selector, jquery selector string
 *
 */
aid.effect.buttonEnd = function( selector )
{
    var tmp = $(selector).attr('aiddatabuffer');
    $(selector).val( tmp );
    $(selector).prop("disabled", false );
}

