<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><link href="__STATIC__/css/admin/style.css" rel="stylesheet"/><title><?php echo L('website_manage');?></title><script>	var URL = '__URL__';
	var SELF = '__SELF__';
	var ROOT_PATH = '__ROOT__';
	var APP	 =	 '__APP__';
	//语言项目
	var lang = new Object();
	<?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?></script></head><body><div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div><?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content_menu ib_a blue line_x"><?php if(!empty($big_menu)): ?><a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?><a href="<?php echo U($val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo ($val['name']); ?></em></a><?php endforeach; endif; else: echo "" ;endif; endif; ?></div></div><?php endif; ?><!--数据库恢复--><div class="subnav"><h1 class="title_2 line_x"><?php echo L('database_restore');?></h1></div><div class="pad_lr_10"><div class="J_tablelist table_list"><form action="<?php echo U('backup/import');?>" method="post"><table width="100%" cellspacing="0" class="treeTable"><thead><tr><th align="left"><?php echo L('backup_name');?></th><th><?php echo L('backup_size');?></th><th><?php echo L('backup_time');?></th
                    ><th><?php echo L('operations_manage');?></th></tr></thead><tbody><?php if(is_array($backups)): $i = 0; $__LIST__ = $backups;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr class="collapsed"><td><span style="padding-left: 20px" name="<?php echo ($val["name"]); ?>" class="expander"></span><?php echo ($val["name"]); ?></td><td align="center"><?php echo ($val["total_size"]); ?>kb</td><td align="center"><?php echo (frienddate($val["date"])); ?></td><td align="center"><a href="<?php echo u('backup/del_backup', array('backup'=>$val['name']));?>"><?php echo L('delete');?></a> | 
                    <a href="<?php echo u('backup/import', array('backup'=>$val['name']));?>" ><?php echo L('import');?></a></td></tr><?php if(is_array($val['vols'])): $i = 0; $__LIST__ = $val['vols'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol): $mod = ($i % 2 );++$i;?><tr parent="<?php echo ($val["name"]); ?>" class="hidden"><td><?php echo ($vol["file"]); ?></td><td align="center"><?php echo ($vol["size"]); ?>kb</td><td align="center"><?php echo (frienddate($val["date"])); ?></td><td align="center"><a href="<?php echo u('backup/download', array('backup'=>$val['name'], 'file'=>$vol['file']));?>"><?php echo L('download');?></a></td></tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?></tbody></table></form></div></div><script src="__STATIC__/js/jquery/jquery.js"></script><script src="__STATIC__/js/jquery/plugins/jquery.tools.min.js"></script><script src="__STATIC__/js/jquery/plugins/formvalidator.js"></script><script src="__STATIC__/js/yangtata.js"></script><script src="__STATIC__/js/admin.js"></script><script src="__STATIC__/js/dialog.js"></script><script>//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script><?php if(isset($list_table)): ?><script src="__STATIC__/js/jquery/plugins/listTable.js"></script><script>$(function(){
	$('.J_tablelist').listTable();
});
</script><?php endif; ?><script>$(function(){
    $(".show_sub").click(function(){
        $(this).attr("src",function(){
            if(this.src == '<?php echo ($tpl_dir); ?>/images/tv-expandable.gif'){
                return '<?php echo ($tpl_dir); ?>/images/tv-collapsable.gif';
            } else {
                return '<?php echo ($tpl_dir); ?>/images/tv-expandable.gif';
            }
        });
        var sub_id = $(this).attr('sub');
        $("tr[parent='"+sub_id+"']").toggle();
    });
    $('.expander').toggle(function(){
        $(this).parent().parent().removeClass('collapsed').addClass('expanded');
        $('tr[parent="'+$(this).attr('name')+'"]').show();
    },function(){
        $(this).parent().parent().removeClass('expanded').addClass('collapsed');
        $('tr[parent="'+$(this).attr('name')+'"]').hide();
    });
});
</script></body></html>