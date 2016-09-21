<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><link href="__STATIC__/css/admin/style.css" rel="stylesheet"/><title><?php echo L('website_manage');?></title><script>	var URL = '__URL__';
	var SELF = '__SELF__';
	var ROOT_PATH = '__ROOT__';
	var APP	 =	 '__APP__';
	//语言项目
	var lang = new Object();
	<?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?></script></head><body><div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div><?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content_menu ib_a blue line_x"><?php if(!empty($big_menu)): ?><a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?><a href="<?php echo U($val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo ($val['name']); ?></em></a><?php endforeach; endif; else: echo "" ;endif; endif; ?></div></div><?php endif; ?><div class="subnav"><h1 class="title_2 line_x">wap相关设置</h1></div><div class="pad_lr_10"><form id="info_form" action="<?php echo u('setting/edit');?>" method="post" enctype="multipart/form-data"><table width="100%" class="table_form"><tr><th width="150">手机wap版地址 :</th><td><input type="text" name="setting[header_html]" class="input-text" size="80" value="<?php echo C('ftx_header_html');?>"><span class="red ml10">网站地址必须以http:// 开头 / 斜杠结尾;开启手机wap访问教程：<a href="http://bbs.yangtata.com/thread-1700-1-1.html" target="_blank">查看教程</a></span></td></tr><tr><th width="150">手机版LOGO :</th><td><img src="<?php echo C('ftx_wap_logo');?>" id="show_waplogo_J_img" style="height:45px;background:#f8285c"/></br><input type="text" name="setting[wap_logo]" id="J_waplogo_img" class="input-text fl mr10" size="50" value="<?php echo C('ftx_wap_logo');?>"><div id="J_waplogo_upload_img" class="upload_btn"><span><?php echo L('upload');?></span></div><span class="attachment_icon J_attachment_icon" file-type="image" ></span></td></tr><tr><th width="150">手机版SEO标题 :</th><td><input type="text" name="setting[wap_title]" class="input-text" size="80" value="<?php echo C('ftx_wap_title');?>"></td></tr><tr><th width="150">手机版SEO关键词 :</th><td><input type="text" name="setting[wap_keywords]" class="input-text" size="80" value="<?php echo C('ftx_wap_keywords');?>"></td></tr><tr><th width="150">手机版SEO描述 :</th><td><textarea rows="4" cols="80" name="setting[wap_description]"><?php echo C('ftx_wap_description');?></textarea></td></tr><tr><th>是否开启自动跳转 :</th><td><label><input type="radio" class="J_change_zidong" <?php if(C('ftx_site_zidong') == '1'): ?>checked="checked"<?php endif; ?> value="1" name="setting[site_zidong]"><?php echo L('open');?></label> &nbsp;&nbsp;
                <label><input type="radio" class="J_change_zidong" <?php if(C('ftx_site_zidong') == '0'): ?>checked="checked"<?php endif; ?> value="0" name="setting[site_zidong]"><?php echo L('close');?></label><span class="gray ml10">更换状态后需清理缓存</span></td></tr><tr><th></th><td><input type="hidden" name="menuid"  value="<?php echo ($menuid); ?>"/><input type="submit" class="smt mr10" value="<?php echo L('submit');?>"/></td></tr></table></form></div><script src="__STATIC__/js/jquery/jquery.js"></script><script src="__STATIC__/js/jquery/plugins/jquery.tools.min.js"></script><script src="__STATIC__/js/jquery/plugins/formvalidator.js"></script><script src="__STATIC__/js/yangtata.js"></script><script src="__STATIC__/js/admin.js"></script><script src="__STATIC__/js/dialog.js"></script><script>//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script><?php if(isset($list_table)): ?><script src="__STATIC__/js/jquery/plugins/listTable.js"></script><script>$(function(){
	$('.J_tablelist').listTable();
});
</script><?php endif; ?><script src="__STATIC__/js/fileuploader.js"></script><script type="text/javascript">$(function(){
    var upload = new qq.FileUploaderBasic({
    	allowedExtensions: ['jpg','gif','png'],
        button: document.getElementById('J_waplogo_upload_img'),
        multiple: false,
        action: "<?php echo U('setting/ajax_upload');?>",
        inputName: 'img',
        forceMultipart: true, //用$_FILES
        messages: {
        	typeError: lang.upload_type_error,
        	sizeError: lang.upload_size_error,
        	minSizeError: lang.upload_minsize_error,
        	emptyError: lang.upload_empty_error,
        	noFilesError: lang.upload_nofile_error,
        	onLeave: lang.upload_onLeave
        },
        showMessage: function(message){
        	$.yangtata.tip({content:message, icon:'error'});
        },
        onSubmit: function(id, fileName){
        	$('#J_waplogo_upload_img').addClass('btn_disabled').find('span').text(lang.uploading);
        },
        onComplete: function(id, fileName, result){
        	$('#J_waplogo_upload_img').removeClass('btn_disabled').find('span').text(lang.upload);
		if(result.status == '1'){
            		$('#show_waplogo_J_img').attr('src','/'+result.data);
        		$('#J_waplogo_img').val('/'+result.data);
        	}else{
        		$.yangtata.tip({content:result.msg, icon:'error'});
        	}
        }
    });

});
</script></body></html>