
;(function($){
    $.yangtata.init = function(){
        $.yangtata.ui.init();
        $.yangtata.tool.sendmail();
        //$.yangtata.tool.msgtip();
    }
    $.yangtata.ui = {
        init: function() {
            $.yangtata.ui.input_init();
            $.yangtata.ui.decode_img($(document));
            $.yangtata.ui.captcha();
        },
        //刷新验证码
        captcha: function() {
            $('#J_captcha_img').click(function(){
                var timenow = new Date().getTime(),
                    url = $(this).attr('data-url').replace(/js_rand/g,timenow);
                $(this).attr("src", url);
            });
            $('#J_captcha_change').click(function(){
                $('#J_captcha_img').trigger('click');
            });
        },
        input_init: function() {
            $('input[def-val],textarea[def-val]').live('focus', function(){
                var self = $(this);
                $.trim(self.val()) == $.trim(self.attr('def-val')) && self.val("");
                self.css("color", "#484848")
            });
            $('input[def-val],textarea[def-val]').live('blur', function(){
                var self = $(this);
                $.trim(self.val()) == "" && (self.val(self.attr('def-val')), self.css("color", "#999999"));
            });
        },
        decode_img: function(context) {
            $('.J_decode_img', context).each(function(){
                var uri = $(this).attr('data-uri')||"";
                $(this).attr('src', $.yangtata.util.base64_decode(uri));  
            });
        }
    },
    $.yangtata.tool = {
        //发送邮件
        sendmail: function() {
            return yangtataER.async_sendmail ? ($.get(yangtataER.root + '/?a=send_mail'), !0) : !1;
        },
        //信息提示
        msgtip: function() {
            if(yangtataER.uid){
                var is_update = !1;
                var update = function() {
                    is_update = !0;
                    $.getJSON(yangtataER.root + '/?m=user&a=msgtip', function(result){
                        if(result.status == 1){
                            var fans = parseInt(result.data.fans),
                                atme = parseInt(result.data.atme),
                                msg = parseInt(result.data.msg),
                                system = parseInt(result.data.system),
                                msgtotal = fans + atme + msg + system;
                            fans > 0 && $('#J_fans').html('(' + fans + ')');
                            atme > 0 && $('#J_atme').html('(' + atme + ')');
                            msg > 0 && $('#J_msg').html('(' + msg + ')');
                            system > 0 && $('#J_system').html('(' + system + ')');
                            msgtotal > 0 && $('#J_msgtip').html('(' + msgtotal + ')');
                            is_update = !1;
                            setTimeout(function(){update()}, 5E3);
                        }
                    });
                };
                !is_update && update();
            }
        }
    }
    $.yangtata.init();
})(jQuery);