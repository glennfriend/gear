/**
 *  aid - 幫助的工具集合
 *  
 *  @see jquery 2.0.3+
 */
var aid = aid || {};


/**
 *  aid event
 *  經常會用使用到的事件 library
 *
 *
 */
aid.event = aid.event || {};


/**
 *  全選/全不選 dom 之下所有的 checkbox
 *  
 *  sample:
 *  
 *      aid.event.checkboxAll('#clickCheckboxId', 'form-index input[type=checkbox]', 'click');
 *      aid.event.checkboxAll('#chooseItemsAll',  'form-index input[type=checkbox]' );
 *  
 *  
 *  @param fire,    checkbox selector, 必需是 checkbox, 因為要檢查裡面的值為 true or false
 *  @param control, checkbox selector, 受影響之標的
 *  @param event,   event, 預設值為 "click" 
 */
aid.event.checkboxAll = function( fire, control, event ) 
{
    var control = control || 'input[type=checkbox]';
    var event   = event   || 'click';

    $(fire).on(event,function(){
        var isChecked = $(this).prop('checked');
        if( isChecked ) {
            $(control).prop('checked', true);
        }
        else {
            $(control).prop('checked', false);
        }
    });
};

