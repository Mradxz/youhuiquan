<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><link href="__STATIC__/css/admin/style.css" rel="stylesheet"/><title><?php echo L('website_manage');?></title><script>	var URL = '__URL__';
	var SELF = '__SELF__';
	var ROOT_PATH = '__ROOT__';
	var APP	 =	 '__APP__';
	//语言项目
	var lang = new Object();
	<?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?></script></head><body><div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div><?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content_menu ib_a blue line_x"><?php if(!empty($big_menu)): ?><a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?><a href="<?php echo U($val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo ($val['name']); ?></em></a><?php endforeach; endif; else: echo "" ;endif; endif; ?></div></div><?php endif; ?><!--采集 设置--><div class="subnav"><div class="content_menu ib_a blue line_x"><a  class="on"><em>采集设置</em></a></div></div><div class="pad_lr_10"><form id="info_form" action="<?php echo u('setting/edit');?>" method="post"><table width="100%" class="table_form contentWrap"><tr><th width="150">采集默认时间 :</th><td><input type="text" name="setting[coupon_add_time]" size="10" class="input-text" value="<?php echo C('ftx_coupon_add_time');?>" />&nbsp;&nbsp;小时&nbsp;&nbsp;<span class="gray">一般72小时，三天过期（采集的时候自动设置）</span></td></tr><tr><th width="150">设置阿里妈妈cookie :</th><td><textarea rows="10" cols="100" name="setting[cookie]"><?php echo C('ftx_cookie');?></textarea>&nbsp;&nbsp;&nbsp;
				</td></tr><tr><th width="150">鹊桥PID :</th><td><input type="text" name="setting[quepid]" value="<?php echo C('ftx_quepid');?>" class="input-text" size="40"/>		  &nbsp;&nbsp;&nbsp;
          </td></tr><tr><th width="150">高佣PID :</th><td><input type="text" name="setting[youpid]" value="<?php echo C('ftx_youpid');?>" class="input-text" size="40"/></td></tr><tr><tr><th width="150">大淘客cookies :</th><td><textarea rows="3" cols="100" name="setting[dtkcookies]" ><?php echo C('ftx_dtkcookies');?></textarea> &nbsp;&nbsp;
				</td></tr><th width="150">大淘客优惠券APPKEY :</th><td><input type="text" name="setting[dtk]" size="20" class="input-text" value="<?php echo C('ftx_dtk');?>" />&nbsp;&nbsp;
					<span class="gray"><a href="http://www.dataoke.com/ucenter/appkey_apply.asp" target="_blank">点此申请</a></span></td></tr><tr><th>大淘客分类绑定 :</th><td>					服装 <input type="text" name="setting[fz]"  class="input-text" size="5" value="<?php echo C('ftx_fz');?>"> 母婴 <input type="text" name="setting[my]" class="input-text" size="5" value="<?php echo C('ftx_my');?>"> 化妆品 <input type="text" name="setting[hzp]" class="input-text" size="5" value="<?php echo C('ftx_hzp');?>"> 居家 <input type="text" name="setting[jj]" class="input-text" size="5" value="<?php echo C('ftx_jj');?>"> 鞋包配饰 <input type="text" name="setting[xbps]" class="input-text" size="5" value="<?php echo C('ftx_xbps');?>"> 美食 <input type="text" name="setting[ms]" class="input-text" size="5" value="<?php echo C('ftx_ms');?>"> 文体车品 <input type="text" name="setting[wtcp]" class="input-text" size="5" value="<?php echo C('ftx_wtcp');?>"> 数码家电 <input type="text" name="setting[smjd]" class="input-text" size="5" value="<?php echo C('ftx_smjd');?>">&nbsp;&nbsp;<span class="red">请填写本站对应的分类ID与大淘客分类进行绑定</span></td></tr><tr><th></th><td><input type="hidden" name="menuid"  value="<?php echo ($menuid); ?>"/><input type="submit" class="smt mr10" name="do" value="<?php echo L('submit');?>"/></td></tr></table></form><br><br><br><br><br><br></div><script src="__STATIC__/js/jquery/jquery.js"></script><script src="__STATIC__/js/jquery/plugins/jquery.tools.min.js"></script><script src="__STATIC__/js/jquery/plugins/formvalidator.js"></script><script src="__STATIC__/js/yangtata.js"></script><script src="__STATIC__/js/admin.js"></script><script src="__STATIC__/js/dialog.js"></script><script>//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script><?php if(isset($list_table)): ?><script src="__STATIC__/js/jquery/plugins/listTable.js"></script><script>$(function(){
	$('.J_tablelist').listTable();
});
</script><?php endif; ?></body></html>