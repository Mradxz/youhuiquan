(function($){
    $.fn.slowShow = function(ele,time){
        time = time == undefined?100:time;
        var timer=null;
        clearInterval(timer);
        this.hover(function(){
                clearTimeout(timer);
                timer=setTimeout(function(){
                    ele.show();
                },time);
            },
            function(){
                clearTimeout(timer);
                timer=setTimeout(function(){
                    ele.hide();
                },time);
            }
        )
    }



})(jQuery);


(function($){
    $("img.lazy").lazyload({threshold:200,failure_limit:30});

   
    /**
     * 右侧返回顶部
     * @author xueli@juanpi.com
     * @date   2014-10-14
     * @return {[type]}   [description]
     */
    var $navFun_2 = function() {
        var st = $(document).scrollTop(),
            winh = $(window).height(),
            doch = $(document).height(),
            headh = $("#toolbar").height(),
            header = $(".header").height(),
            $nav_classify = $("div.side_right");

        if(st > headh + header){
            $nav_classify.show()
            $nav_classify.addClass('fix')
        } else {

            $nav_classify.hide()
            $nav_classify.removeClass('fix')
        }
    };

    var $navFun = function(){       
        $navFun_2();
    }

    /**
     * fangfang，绑定滚动函数
     * @param {}
     * @time 2014-02-12
     */
    var F_nav_scroll = function () {
        if(navigator.userAgent.indexOf('iPad') > -1){
            return false;
        }
        if (!window.XMLHttpRequest) {
           return;
        }else{
            $(".side_right").css("bottom",($(window).height()-$(".side_right").height())/2-300);
            //默认执行一次
            $navFun();
            $(window).bind("scroll", $navFun);
        }
        $(window).resize(function(){
            $(".side_right").css("bottom",($(window).height()-$(".side_right").height())/2-300);
        })
    }
    F_nav_scroll();

    $('a.go-top').click(function(){
        $('body,html').animate({scrollTop:0},500);
    });




    var carousel_index = function(){
        if($(".banner li").size() == 1) $(".banner li").eq(0).show();
        if($(".banner li").size() <= 1) return;
        var i = 0,max = $(".banner li").size()- 1,playTimer;
        $(".banner li").each(function(){
            $(".adType").append('<a></a>');
        });
        $(".adType a").eq(0).addClass("current");
        $(".banner li").eq(0).show();
        var next = function(){
            i = i>=max?0:i+1;
            $(".top_bar .banner li").fadeOut().eq(i).fadeIn();
            $(".adType a").removeClass("current").eq(i).addClass("current");
        }
        var play = setInterval(next,3000);
        $(".banner").hover(function(){
            clearInterval(play);
        },function(){
            clearInterval(play);
            play = setInterval(next,3000);
        });
        $(".adType a").mouseover(function(){
            if($(this).hasClass("current")) return;
            var index = $(this).index()-1;
            var playTimer = setTimeout(function(){
                clearInterval(play);
                i = index;
                next();
            },500)
        }).mouseout(function(){
                clearTimeout(playTimer);
            });
    }
    carousel_index();

    /**
     * 将文字信息上下滚动
     * Author: zhuwenfang
     * Date: 14-01-10
     * Time: PM 16:55
     * Function: scrolling the dom 'li' up&down
     **/
    var liAutoScroll = function(){
        var liScrollTimer;
        var $obj = $('.links_list_box');
        $obj.hover(function(){
            clearInterval(liScrollTimer);
        }, function(){
            liScrollTimer = setInterval(function(){
                $obj.find(".links_list").animate({
                    marginTop : -20 + 'px'
                }, 500, function(){
                    $(this).css({ marginTop : "0px"}).find("li:first").appendTo(this);
                });

            }, 3000);
        }).trigger("mouseleave");

    }
    liAutoScroll();
})(jQuery);
