<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><link href="__STATIC__/css/admin/style.css" rel="stylesheet"/><title><?php echo L('website_manage');?></title><script>	var URL = '__URL__';
	var SELF = '__SELF__';
	var ROOT_PATH = '__ROOT__';
	var APP	 =	 '__APP__';
	//语言项目
	var lang = new Object();
	<?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?></script></head><body><div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div><?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content_menu ib_a blue line_x"><?php if(!empty($big_menu)): ?><a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?><a href="<?php echo U($val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo ($val['name']); ?></em></a><?php endforeach; endif; else: echo "" ;endif; endif; ?></div></div><?php endif; ?><div class="subnav"><h1 class="title_2 line_x">大淘客API采集优惠券</h1></div><div class="pad_lr_10"><form id="J_info_form" action="<?php echo U('dtk/setting');?>" method="post"><table width="100%" cellspacing="0" class="table_form"><tr><th>起始位置 :</th><td><input name="page" id="page" type="text" class="input-text" size="10" value="1"/>采集过程中长时间卡停无反应，可记住停顿位置，直接输入上次位置接着采集。
		   </td></tr><tr><th>第一步：</th><td><input type="btn" value="加入推广" class="smt mr10" id="getok"><span style="margin-top:10px;float: left;">注意批量加入推广完成之后使用大淘客转链工具生成推广链接再获取数据并采集。</span></td></tr><tr><th>第二步：</th><td><input type="btn" value="获取数据" class="smt mr10" id="getitem"></td></tr><tr><th>第三步：</th><td><input type="submit" value="开始采集" name="dosubmit" class="smt mr10"></td></tr></table></form></div><script src="__STATIC__/js/jquery/jquery.js"></script><script src="__STATIC__/js/jquery/plugins/jquery.tools.min.js"></script><script src="__STATIC__/js/jquery/plugins/formvalidator.js"></script><script src="__STATIC__/js/yangtata.js"></script><script src="__STATIC__/js/admin.js"></script><script src="__STATIC__/js/dialog.js"></script><script>//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script><?php if(isset($list_table)): ?><script src="__STATIC__/js/jquery/plugins/listTable.js"></script><script>$(function(){
	$('.J_tablelist').listTable();
});
</script><?php endif; ?><script>$(function(){
    var collect_url = "<?php echo U('dtk/collect');?>";
    $('#J_info_form').ajaxForm({success:complete, dataType:'json'});
    var p = document.getElementById("page").value;;
    function complete(result){
        if(result.status == 1){
            $.dialog({id:'dtk', title:result.msg, content:result.data, padding:'', lock:true});
            p = document.getElementById("page").value;;
            collect_page();
        } else {
            $.yangtata.tip({content:result.msg, icon:'alert'});
        }
    }
    function collect_page(){
        $.getJSON(collect_url, {p:p}, function(result){
            if(result.status == 1){
                $.dialog.get('dtk').content(result.data);
                p++;
                collect_page(p);
            }else{
                $.dialog.get('dtk').close();
                $.yangtata.tip({content:result.msg});
            }
        });
    }
});
</script><script type="text/javascript">	$(function() {
		$("#getitem").click(function() {
			$.getJSON('/index.php?g=admin&m=dtk&a=getitem', {},
			function(data) {
				alert(data.msg);
			})
		})
	})
</script><script>var getokurl = "<?php echo U('dtk/getok');?>";
$("#getok").click(function() {
    $.getJSON(getokurl, function(result) {
        if (result.status == 1) {
            $.dialog({
                id: 'getok',
                title: result.msg,
                content: result.data,
                padding: '',
                lock: true
            });
        } else {
            $.yangtata.tip({
                content: result.msg,
                icon: 'alert'
            });
        }
    });
}
)
</script></body></html>