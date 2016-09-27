$(document).ready(function(){
	var word_top  = '在此输入您要找的宝贝！';
	var $word_top = $('.search_box .text');
	$word_top.focusClear();

	if(!$word_top.val()){
		$word_top.val(word_top);
	}
	$word_top.focus(function(){
		if( $(this).val()==word_top){
			$(this).val('');
		}
	}).blur(function(){
		if( !$(this).val()){
			$(this).val(word_top);
		}
	});
});

function search_submit(){
	var k = $(".search_box .text").val();
	if(k == '请输入您要找的宝贝！'){
		alert("请输入要搜索的内容！");
		return false;
	}
}

function search_zhekou(){
	var k = $(".search_box .text").val();
	if(k == '请输入您要找的宝贝！'){
		alert("请输入要搜索的内容！");
		return false;
	}
	else{
		window.location.href='/index.php?q='+k;
	}
}

function formatFloat(src, pos){
	return Math.round(src*Math.pow(10, pos))/Math.pow(10, pos);
}

/*收藏*/
function AddFavorite(b) {
	CloseNLRAF(true);
	var c = null;
	if (b == "childreTop") {
		var c = SITEURL;
	} else {
		if (b == "welcomefavorite") {
			var c = SITEURL+"?from=fav"
		} else {
			var c = location.href + (b == true ? "?from=topfavorite": "")
		}
	}
	if ($.browser.msie) {
		try {
			window.external.addFavorite(c, ""+WEBNICK+"-省钱，从"+WEBNICK+"开始。")
		} catch(a) {
			alert("请按键盘 CTRL键 + D 把"+WEBNICK+"放入收藏夹，折扣信息一手掌握！")
		}
	} else {
		if ($.browser.mozilla) {
			try {
				window.sidebar.addPanel(""+WEBNICK+"-网购，从"+WEBNICK+"开始。", c, "")
			} catch(a) {
				alert("请按键盘 CTRL键 + D 把"+WEBNICK+"放入收藏夹，折扣信息一手掌握！")
			}
		} else {
			alert("请按键盘 CTRL键 + D 把"+WEBNICK+"放入收藏夹，折扣信息一手掌握！")
		}
	}
	return false
}

function SetHome(url){
	if (document.all) {
		document.body.style.behavior='url(#default#homepage)';
		document.body.setHomePage(url);
	}else{ 
		alert("您好,您的浏览器不支持自动设置页面为首页功能,请您手动在浏览器里设置该页面为首页!"); 
	} 
}

function CloseNLRAF(a) {
	if (a) {
		$.cookie("NLRAF", "true", {
			path: "/",
			expires: 30
		})
	} else {
		$.cookie("NLRAF", "true", {
			path: "/"
		})
	}
	$("#afp").slideUp()
}

$(function(){

	//顶部弹出收藏
	if ($.cookie("NLRAF") == null && !/favorite|desk|zt11/.test(location.search)) {
		if (!$("#afp").length) {
			$("body").prepend('<div id="afp" class="totop-tips" style="display:none;"><p>按 <strong>Ctrl+D</strong> 把'+WEBNICK+'放入收藏夹，折扣信息一手掌握！<label id="nlraf" onclick="CloseNLRAF(true)" for="check_nlraf"><input style="display:none;" type="checkbox" id="check_nlraf" /><a href="javascript:void(0)">不再提醒</a></label><a id="cafp" href="javascript:void(0)" onclick="CloseNLRAF(false)"></a><a id="cafp" href="javascript:void(0)" onclick="CloseNLRAF(false)"><span class="closet"><em>x</em>关闭</span></a></p></div>')
		}
		$("#afp").slideDown("slow")
	}

	//跟随滚动
    var ele_fix = $("#lr_float");
    var _main = $(".main");
    if (ele_fix.length > 0) {
        var ele_offset_top = ele_fix.offset().top;
        $(window).scroll(function() {
            var scro_top = $(this).scrollTop();
            var test = ele_offset_top + scro_top;
            var fix_foot_pos = parseInt(ele_fix.height()) + parseInt(scro_top);
            var mainpos = parseInt(_main.offset().top) + parseInt(_main.height());
            if (scro_top <= ele_offset_top && fix_foot_pos < mainpos) {
                ele_fix.css({
                    position: "static",
                    top: "0"
                });
            } else if (scro_top > ele_offset_top && fix_foot_pos < mainpos) {
                $("#lr_float").css({
                    "position": "fixed",
                    "top": "0"
                });
            } else if (scro_top > ele_offset_top && fix_foot_pos > mainpos) {
                var posi = mainpos - fix_foot_pos;
                ele_fix.css({
                    position: "fixed",
                    top: posi
                });
            }
        });
    }
	
	/*底部滚动*/
	var F_scroll_pics = function(){
    	var sWidth = $(".slide-img").width();
    	var len = $("#ft-box li").length;
    	var index = 0;
    	var picsScrollTimer;

    	var leftBtnClickEvt = function(){
        	$(".left-cur").on("click" , function () {
            	index -= 1;
            	if (index == -1) { index = len - 1; }
            	showPics(index);
            	addOrRemoveBtnsClass();
        	});
    	}
    
    	var rightBtnClickEvt = function(){
        	$(".right-cur").on("click" , function () {
            	index += 1;
            	if (index == len) { index = 0; }
            	showPics(index);
            	addOrRemoveBtnsClass();
        	});
    	}
   
    	$("#ft-box").css("width", sWidth * (len));
    
    	$(".wechat").hover(function () {
        	clearInterval(picsScrollTimer);
    	}, function () {
        	picsScrollTimer = setInterval(function () {
            	showPics(index);
            	addOrRemoveBtnsClass();
            	index++;
            	if (index == len) { index = 0; }
        	}, 3000);
    	}).trigger("mouseleave");
    

    	var initBtnClickEvt = function(){
        	leftBtnClickEvt();
        	rightBtnClickEvt();
        	addRightBtnClass();
    	}

    	function addRightBtnClass() {
        	$(".right-cur").addClass("right-unactive");
        	$(".right-unactive").unbind("click");
    	}

    	function addLeftBtnClass(){
        	$(".left-cur").addClass("left-unactive");
        	$(".left-unactive").unbind("click");
    	}

    	function removeRightBtnClass(){
        	$(".right-cur").removeClass("right-unactive");
        	$(".right-cur").on("click" , rightBtnClickEvt());
    	}

    	function removeLeftBtnClass(){
        	$(".left-cur").removeClass("left-unactive");
        	$(".left-cur").on("click" , leftBtnClickEvt());
    	}
    
    	function addOrRemoveBtnsClass(){
        	if(index == 0){
             	addRightBtnClass();
             	removeLeftBtnClass();
        	}else{  
            	removeRightBtnClass();           
            	addLeftBtnClass();        
        	}
    	}
    
    	function showPics(index) {
        	var nowLeft = -index * sWidth;
        	$("#ft-box").stop(true, false).animate({ "left": nowLeft }, 300);
    	}
    	initBtnClickEvt();
    }

	F_scroll_pics();

	var F_hidden_zhe = function(){
		$("li").each(function(){
			if($(this).find('span.price-old').width() > 78){
				$(this).find('u').hide();
			}
		})
	}
	F_hidden_zhe();

	$(".xb_icon a").hover(function(){
        $(".xb_js .steup").hide().eq($(this).index()).show();
    });




	 /**
     * 首页幻灯片
     * @param {}
     * @time 2015-01-13
     */

    var carousel_index = function(){
        if($(".banner li").size() == 1) $(".banner li").eq(0).css("opacity", "1");
        if($(".banner li").size() <= 1) return;
        var i = 0,max = $(".banner li").size()- 1,playTimer;
        $(".banner li").each(function(){
            $(".adType").append('<a></a>');
        });
    //初始化
        $(".adType a").eq(0).addClass("current");
    $(".banner li").eq(0).css({"z-index":"2","opacity":"1"});
    var playshow=function(){
        $(".adType a").removeClass("current").eq(i).addClass("current");
        $(".top_bar .banner li").eq(i).css("z-index", "2").fadeTo(500, 1, function(){
        $(".top_bar .banner li").eq(i).siblings("li").css({
                  "z-index": 0,
                  opacity: 0
        }).end().css("z-index", 1);
        });
    }
    var next = function(){
      i = i>=max?0:i+1;
      playshow()
    }
    var prev = function(){
      i = i<=0?max:i-1;
      playshow()
    }
        var play = setInterval(next,3000);
        $(".banner li a,.banner_arrow").hover(function(){
            clearInterval(play);
            $(".banner_arrow").css("display","block");
        },function(){
            clearInterval(play);
            play = setInterval(next,3000);
            $(".banner_arrow").css("display","none");
        });
        $(".banner_arrow .arrow_prev").on('click',prev);
        $(".banner_arrow .arrow_next").on('click',next);
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


	allMenu_show=function(){
		
		$(".nav ul li:first .current").addClass("open");
        var timer=null;
        $(".nav ul li:first").hover(
            function(){
                var mu=$(this);
                timer=setTimeout(function(){
                    mu.addClass("open");
                },100);
            },
            function(){
                clearTimeout(timer);
                $(this).removeClass("open");
            }
        );
    }
    allMenu_show();


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

});