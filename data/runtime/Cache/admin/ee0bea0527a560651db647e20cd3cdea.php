<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><link href="__STATIC__/css/admin/style.css" rel="stylesheet"/><title><?php echo L('website_manage');?></title><script>	var URL = '__URL__';
	var SELF = '__SELF__';
	var ROOT_PATH = '__ROOT__';
	var APP	 =	 '__APP__';
	//语言项目
	var lang = new Object();
	<?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?></script></head><body><div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div><?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content_menu ib_a blue line_x"><?php if(!empty($big_menu)): ?><a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?><a href="<?php echo U($val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo ($val['name']); ?></em></a><?php endforeach; endif; else: echo "" ;endif; endif; ?></div></div><?php endif; ?><div class="subnav"><h1 class="title_2 line_x">网站基本设置</h1></div><div class="pad_lr_10"><form id="info_form" action="<?php echo u('setting/edit');?>" method="post" enctype="multipart/form-data"><table width="100%" class="table_form"><tr><th width="150"><?php echo L('site_url');?> :</th><td><input type="text" name="setting[site_url]" class="input-text" size="30" value="<?php echo C('ftx_site_url');?>"><span class="red ml10">网站地址必须以http:// 开头 / 斜杠结尾</span></td></tr><tr><th>网站主Logo :</th><td><img src="<?php echo C('ftx_site_logo');?>" id="show_logo_J_img" style="height:45px;"/></br><input type="text" name="setting[site_logo]" id="J_logo_img" class="input-text fl mr10" size="50" value="<?php echo C('ftx_site_logo');?>"><div id="J_logo_upload_img" class="upload_btn"><span><?php echo L('upload');?></span></div><span class="attachment_icon J_attachment_icon" file-type="image" ></span></td></tr><tr><th width="150"><?php echo L('site_name');?> :</th><td><input type="text" name="setting[site_name]" class="input-text" size="30" value="<?php echo C('ftx_site_name');?>"></td></tr><tr><th><?php echo L('site_icp');?> :</th><td><input type="text" name="setting[site_icp]" class="input-text" size="30" value="<?php echo C('ftx_site_icp');?>"></td></tr><tr><th>QQ :</th><td><input type="text" name="setting[qq]" class="input-text" size="30" value="<?php echo C('ftx_qq');?>"></td></tr><tr><th><?php echo L('statistics_code');?> :</th><td><textarea rows="4" cols="80" name="setting[statistics_code]"><?php echo C('ftx_statistics_code');?></textarea></td></tr><tr><th width="150">淘点金代码 :</th><td><textarea rows="8" cols="80" name="setting[taojindian_html]"><?php echo C('ftx_taojindian_html');?></textarea><span class="red ml10"><a href="http://bbs.yangtata.com/thread-1793-1-1.html" target="_blank">查看获取教程</a></span></td></tr><tr><th>二维码 :</th><td><img src="<?php echo C('ftx_site_weixin');?>" id="show_weixin_J_img" style="width:90px;height:90px;"/></br><input type="text" name="setting[site_weixin]" id="J_weixin_img" class="input-text fl mr10" size="50" value="<?php echo C('ftx_site_weixin');?>"><div id="J_weixin_upload_img" class="upload_btn"><span><?php echo L('upload');?></span></div><span class="attachment_icon J_attachment_icon" file-type="image" ></span></td></tr><tr><th>LOGO右则长图 :</th><td><img src="<?php echo C('ftx_site_navlogo');?>" id="show_navlogo_J_img" style="width:335px;height:25px;"/></br><input type="text" name="setting[site_navlogo]" id="J_navlogo_img" class="input-text fl mr10" size="50" value="<?php echo C('ftx_site_navlogo');?>"><div id="J_navlogo_upload_img" class="upload_btn"><span><?php echo L('upload');?></span></div><span class="attachment_icon J_attachment_icon" file-type="image" ></span></td></tr><tr><th><?php echo L('site_status');?> :</th><td><label><input type="radio" class="J_change_status" <?php if(C('ftx_site_status') == '1'): ?>checked="checked"<?php endif; ?> value="1" name="setting[site_status]"><?php echo L('open');?></label> &nbsp;&nbsp;
                <label><input type="radio" class="J_change_status" <?php if(C('ftx_site_status') == '0'): ?>checked="checked"<?php endif; ?> value="0" name="setting[site_status]"><?php echo L('close');?></label></td></tr><tr id="J_closed_reason" <?php if(C('ftx_site_status') == 1): ?>class="hidden"<?php endif; ?>><th><?php echo L('closed_reason');?> :</th><td><textarea rows="4" cols="50" name="setting[closed_reason]" id="closed_reason"><?php echo C('ftx_closed_reason');?></textarea></td></tr><tr><th></th><td><input type="hidden" name="menuid"  value="<?php echo ($menuid); ?>"/><input type="submit" class="smt mr10" value="<?php echo L('submit');?>"/></td></tr></table></form></div><script src="__STATIC__/js/jquery/jquery.js"></script><script src="__STATIC__/js/jquery/plugins/jquery.tools.min.js"></script><script src="__STATIC__/js/jquery/plugins/formvalidator.js"></script><script src="__STATIC__/js/yangtata.js"></script><script src="__STATIC__/js/admin.js"></script><script src="__STATIC__/js/dialog.js"></script><script>//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script><?php if(isset($list_table)): ?><script src="__STATIC__/js/jquery/plugins/listTable.js"></script><script>$(function(){
	$('.J_tablelist').listTable();
});
</script><?php endif; ?><script src="__STATIC__/js/fileuploader.js"></script><script>$(function(){
    $('.J_change_status').live('click', function(){
        if($(this).val() == '0'){
            $('#J_closed_reason').fadeIn();
        }else{
            $('#J_closed_reason').fadeOut();
        }
    });
	 var upload = new qq.FileUploaderBasic({
    	allowedExtensions: ['jpg','gif','png'],
        button: document.getElementById('J_logo_upload_img'),
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
        	$('#J_logo_upload_img').addClass('btn_disabled').find('span').text(lang.uploading);
        },
        onComplete: function(id, fileName, result){
        	$('#J_logo_upload_img').removeClass('btn_disabled').find('span').text(lang.upload);
		if(result.status == '1'){
            		$('#show_logo_J_img').attr('src','/'+result.data);
        		$('#J_logo_img').val('/'+result.data);
        	}else{
        		$.yangtata.tip({content:result.msg, icon:'error'});
        	}
        }
    });
	var weixinupload = new qq.FileUploaderBasic({
    	allowedExtensions: ['jpg','gif','png'],
        button: document.getElementById('J_weixin_upload_img'),
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
        	$('#J_weixin_upload_img').addClass('btn_disabled').find('span').text(lang.uploading);
        },
        onComplete: function(id, fileName, result){
        	$('#J_weixin_upload_img').removeClass('btn_disabled').find('span').text(lang.upload);
		if(result.status == '1'){
            		$('#show_weixin_J_img').attr('src','/'+result.data);
        		$('#J_weixin_img').val('/'+result.data);
        	}else{
        		$.yangtata.tip({content:result.msg, icon:'error'});
        	}
        }
    });
});
var navupload = new qq.FileUploaderBasic({
    	allowedExtensions: ['jpg','gif','png'],
        button: document.getElementById('J_navlogo_upload_img'),
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
        	$('#J_navlogo_upload_img').addClass('btn_disabled').find('span').text(lang.uploading);
        },
        onComplete: function(id, fileName, result){
        	$('#J_navlogo_upload_img').removeClass('btn_disabled').find('span').text(lang.upload);
		if(result.status == '1'){
            		$('#show_navlogo_J_img').attr('src','/'+result.data);
        		$('#J_navlogo_img').val('/'+result.data);
        	}else{
        		$.yangtata.tip({content:result.msg, icon:'error'});
        	}
        }
    });
</script></body></html>