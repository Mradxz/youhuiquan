/**
 * @name 礼物中心
 * @author yangtata
 */
;(function($){
    $.yangtata.gift = {
        settings: {
            gift_btn: '.J_gift_btn'
        },
        init: function(options){
            options && $.extend($.yangtata.gift.settings, options);
            //详细信息切换
            $('ul.J_desc_tab').tabs('div.J_desc_panes > div');
            $.yangtata.gift.ec();
        },
        ec: function(){
            var s = $.yangtata.gift.settings;
            $(s.gift_btn).live('click', function(){
                if(!$.yangtata.dialog.islogin()) return !1;
                var id = $(this).attr('data-id'),
                    num_input = $(this).attr('data-num'),
                    num = $(num_input).val();
                $.getJSON(yangtataER.root + '/?m=gift&a=ec', {id:id, num:num}, function(result){
                    if(result.status == 1){
                        $.yangtata.tip({content:result.msg});
                    }else if(result.status == 2){
                        $.dialog({id:'gift_daifu', title:result.msg, content:result.data, width:350, padding:'', fixed:true, lock:true});
                        $.yangtata.gift.daifu_form($('#J_daifu_form'));
                    }else{
                        $.yangtata.tip({content:result.msg, icon:'error'});
                    }
                });
            });
        },
        //代付表单
        daifu_form: function(form){
            form.ajaxForm({
                success: function(result){
                    if(result.status == 1){
                        $.dialog.get('gift_daifu').close();
                        $.yangtata.tip({content:result.msg});
                        window.location.reload();
                    } else {
                        $.yangtata.tip({content:result.msg, icon:'error'});
                    }
                },
                dataType: 'json'
            });
        }
    };
    $.yangtata.gift.init();
})(jQuery);