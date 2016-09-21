<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><link href="__STATIC__/css/admin/style.css" rel="stylesheet"/><title><?php echo L('website_manage');?></title><script>	var URL = '__URL__';
	var SELF = '__SELF__';
	var ROOT_PATH = '__ROOT__';
	var APP	 =	 '__APP__';
	//语言项目
	var lang = new Object();
	<?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?></script></head><body><div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div><?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content_menu ib_a blue line_x"><?php if(!empty($big_menu)): ?><a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?><a href="<?php echo U($val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo ($val['name']); ?></em></a><?php endforeach; endif; else: echo "" ;endif; endif; ?></div></div><?php endif; ?><div class="subnav"><h1 class="title_2 line_x">获取阿里妈妈后台选品库并采集</h1></div><style>.add{background: url(/static/css/admin/bgimg/btn_total.gif) no-repeat;color: #FFF;cursor: pointer;display: block;float:right;font-size: 14px;font-weight: bold;height: 30px;line-height: 28px;line-height: 32px 9;padding-bottom: 2px;margin-top:-1px;text-align: center;width: 104px;border: none medium;}
.add:hover{background-position: 0 -34px;text-decoration: none;}
</style><form name="searchform" method="get" ><table width="100%" cellspacing="0" class="table_form"><tbody><tr><td><div class="explain_col"><input type="hidden" name="g" value="admin" /><input type="hidden" name="m" value="quelist" /><input type="hidden" name="a" value="index" /><input type="hidden" name="menuid" value="<?php echo ($menuid); ?>" />		  &nbsp;&nbsp;排序:
		  <select name="type"><option value="-1" <?php if($so["type"] == '-1'): ?>selected="selected"<?php endif; ?>>全部</option><option value="1" <?php if($so["type"] == '1'): ?>selected="selected"<?php endif; ?>>普通</option><option value="2" <?php if($so["type"] == '2'): ?>selected="selected"<?php endif; ?>>高佣</option></select><input type="submit" value="获取列表" class="add fb"></div></td></tr></tbody></table></form><div class="pad_lr_10" ><div class="J_tablelist table_list"><table width="100%" cellspacing="0"><thead><tr><th width="20">ID</th><th width="100">选品库标题</th><th width="70">选品库ID</th><th width="70">选品库类型</th><th width="80"><?php echo L('operations_manage');?></th></tr></thead><tbody><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 4 );++$i;?><tr><td align="center"><?php echo ($val["id"]); ?></td><td align="center"><?php echo ($val["title"]); ?></td><td align="center"><?php echo ($val["eventId"]); ?></td><td align="center"><?php echo ($val["type"]); ?></td><td align="center"><a class="showdialog" href="javascript:getck(<?php echo ($val["eventId"]); ?>);" data-uri="<?php echo U('quelist/caiji',array('eventId'=>$val['eventId']));?>" data-title="采集" data-id="caiji" data-width="500" data-height="180">采集</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></tbody></table></div><div class="btn_wrap_fixed"><div id="pages"><?php echo ($page); ?></div></div></div><script src="__STATIC__/js/jquery/jquery.js"></script><script src="__STATIC__/js/jquery/plugins/jquery.tools.min.js"></script><script src="__STATIC__/js/jquery/plugins/formvalidator.js"></script><script src="__STATIC__/js/yangtata.js"></script><script src="__STATIC__/js/admin.js"></script><script src="__STATIC__/js/dialog.js"></script><script>//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script><?php if(isset($list_table)): ?><script src="__STATIC__/js/jquery/plugins/listTable.js"></script><script>$(function(){
	$('.J_tablelist').listTable();
});
</script><?php endif; ?><script>var get_url = "<?php echo U('quelist/caiji');?>";
function getck(id){
		$.getJSON(get_url, {id:id}, function(result){
            if(result.status == 1){
                $.dialog({id:'caiji', title:result.msg, content:result.data, padding:'', lock:true});
            }else{
                $.yangtata.tip({content:result.msg, icon:'alert'});
            }
        });
    }
</script></body></html>