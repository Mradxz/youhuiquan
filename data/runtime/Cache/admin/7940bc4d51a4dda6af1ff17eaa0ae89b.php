<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><link href="__STATIC__/css/admin/style.css" rel="stylesheet"/><title><?php echo L('website_manage');?></title><script>	var URL = '__URL__';
	var SELF = '__SELF__';
	var ROOT_PATH = '__ROOT__';
	var APP	 =	 '__APP__';
	//语言项目
	var lang = new Object();
	<?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?></script></head><body><div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div><?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content_menu ib_a blue line_x"><?php if(!empty($big_menu)): ?><a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?><a href="<?php echo U($val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo ($val['name']); ?></em></a><?php endforeach; endif; else: echo "" ;endif; endif; ?></div></div><?php endif; ?><div class="subnav"><h1 class="title_2 line_x">大淘客全站优惠券API采集优惠券</h1></div><div class="pad_lr_10"><form id="J_info_form" action="<?php echo U('dataoke/setting');?>" method="post"><table width="100%" cellspacing="0" class="table_form"><tr><th>起止页 :</th><td><input name="start_p" id="start_p" type="text" class="input-text" size="10" value="1"/> - <input name="end_p" id="end_p" type="text" class="input-text" size="10" value=""/>&nbsp;&nbsp;&nbsp;&nbsp;注意：一定要全部页面获取完成，提示获取完成后，数据才真正的合并完成。</td></tr><tr><th>第一步：</th><td><input type="btn" value="合并数据" class="smt mr10" id="getitem"></td></tr><tr><th>起始位置 :</th><td><input name="page" id="page" type="text" class="input-text" size="10" value="1"/>&nbsp;&nbsp;采集过程中长时间卡停无反应，可记住停顿位置，直接输入上次位置接着采集。
		   </td></tr><tr><th>第二步：</th><td><input type="submit" value="采集商品" name="dosubmit" class="smt mr10"></td></tr></table></form></div><script src="__STATIC__/js/jquery/jquery.js"></script><script src="__STATIC__/js/jquery/plugins/jquery.tools.min.js"></script><script src="__STATIC__/js/jquery/plugins/formvalidator.js"></script><script src="__STATIC__/js/yangtata.js"></script><script src="__STATIC__/js/admin.js"></script><script src="__STATIC__/js/dialog.js"></script><script>//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script><?php if(isset($list_table)): ?><script src="__STATIC__/js/jquery/plugins/listTable.js"></script><script>$(function(){
	$('.J_tablelist').listTable();
});
</script><?php endif; ?><script>$(function(){
    var collect_url = "<?php echo U('dataoke/collect');?>";
    $('#J_info_form').ajaxForm({success:complete, dataType:'json'});
    var p = document.getElementById("page").value;
    function complete(result){
        if(result.status == 1){
            $.dialog({id:'dataoke', title:result.msg, content:result.data, padding:'', lock:true});
            p = document.getElementById("page").value;
            collect_page();
        } else {
            $.yangtata.tip({content:result.msg, icon:'alert'});
        }
    }
    function collect_page(){
        $.getJSON(collect_url, {p:p}, function(result){
            if(result.status == 1){
                $.dialog.get('dataoke').content(result.data);
                p++;
                collect_page(p);
            }else{
                $.dialog.get('dataoke').close();
                $.yangtata.tip({content:result.msg});
            }
        });
    }
});
</script><script type="text/javascript">	$(function() {
		$("#getitem").live('click', function(){        		
		    var appkey = "<?php echo C('ftx_dtk');?>";
			var p = document.getElementById("start_p").value;
			var edp = document.getElementById("end_p").value;
			$.ajax({
			url: '/index.php?g=admin&m=getdtk&a=index',
				type: 'POST',
				data: {
				appkey:appkey,
				p:p,
				edp:edp
			},
			dataType: 'json',
			success: function(result){
				if(result.status == 1){
					$.dialog({id:'getdtk', title:result.msg, content:result.data, padding:'', lock:true}); 		            
					setTimeout(collect_p,1000);
				}else{
					$.yangtata.tip({content:result.msg, icon:'alert'});
				}
			}
			
		});
		function collect_p(){		    
            $.getJSON('/index.php?g=admin&m=getdtk&a=index', {appkey:appkey,p:p,edp:edp}, function(result){
            if(result.status == 1){
                $.dialog.get('getdtk').content(result.data);				
                p++;
				var s = 5;				
				function showtime(){					
				s--;
				if(s==0){
				collect_p(p);
				}
				}
				clearInterval(settime); 
				var settime = setInterval(function(){
				showtime();
				},1000);								
            }else{			    
                $.dialog.get('getdtk').close();
                $.yangtata.tip({content:result.msg});                			
            }
        });
        }
		})
	})
</script></body></html>