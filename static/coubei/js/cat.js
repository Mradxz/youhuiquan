$(function(){
	//收藏状态

	//当前加载的页面
	var s_page=1;
	//当前网址
	var url=window.location.href;
	// 获取页面高度
	var winHeight = document.body.clientHeight;
	//导航位置 && 分类位置
	var menu_top=$(".topbar_link");
	if(menu_top.length>0)
	{
		menu_top=menu_top.offset().top;
	}
	else
	{
		menu_top=false;
	}
	if($("#glist").length>0){
		var glist_cat_top=$("#glist").offset().top;
	}
	$(window).scroll(function(){
		var scrollTop=0;
		if (typeof window.pageYOffset != 'undefined') {
			scrollTop = window.pageYOffset; //Netscape
		}
		else if (typeof document.compatMode != 'undefined' &&
		document.compatMode != 'BackCompat') {
			scrollTop = document.documentElement.scrollTop; //Firefox、Chrome
		}
		else if (typeof document.body != 'undefined') {
			scrollTop = document.body.scrollTop; //IE
		}
		//异步加载
		if(scrollTop>=winHeight*1/5 && Math.ceil(scrollTop/winHeight)==s_page){
			s_page++;
			if(url.indexOf('/')>0){
				if(url.indexOf('?')>0){
					if(url.indexOf('s_page=')>0){
						url=url.replace(/s_page=\d/ig,'s_page='+s_page); 
					}else{
						url=url+'&s_page='+s_page+'&inajax=1';
					}
				}else{
					url=url+'?s_page='+s_page+'&inajax=1';
				}
			}else{
				url=url+'/?s_page='+s_page+'&inajax=1';
			}
			//开始加载
			$.getScript(url,function(){
				//处理产品列表
				var goods=goods_list.toString();
				var r=/\/\*([\S\s]*?)\*\//m,
				goods=r.exec(goods.toString());
				$("#ajaxgoods").append(goods[0]);
				$s_page=$(".s_page_"+s_page);
				$s_page.find(" img.lazy").lazyload({ effect : "fadeIn",failurelimit:10});
				//异步加载收藏
				get_fav_status($s_page);
				//异步加载喜欢
				$s_page.find(".my-like").on("click",function(){
					dolike($(this))
				})
				//异步加载按钮效果
				buy_bt();
			})
			return false;
		}
		//顶部导航
		if(menu_top&&scrollTop>=menu_top){
			$('.scroll-nav-main').show(500);
		}
		else
		{
			$('.scroll-nav-main').hide(500);
		}

	})
	//收藏=====================================================

});


