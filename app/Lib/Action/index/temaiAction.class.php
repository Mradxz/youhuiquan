<?php
class temaiAction extends FirstendAction {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('items');		
        $this->_cate_mod = D('items_cate');		
		
    }  
     /**
	  * 分类
	  */
	public function index(){	    
		$cid	=	I('cid','', 'intval');
		$sort	=	I('sort', 'default', 'trim'); //排序	
		$mode	=	I('mode', 'all', 'trim');
		$order	=	'ordid asc ';
		$config_file = CONF_PATH . 'url.php';
        $config = require $config_file; 
		$www_config_file = CONF_PATH . 'index/config.php';
		$www_config = include $www_config_file;
		if($cid){
        $cinfo = $this->_cate_mod->where(array('id'=>$cid))->find();
        !$cinfo && $this->_404();
		switch ($sort) {
			case 'new':
                $order.= ', coupon_start_time DESC';
                break;			
			case 'price':
                $order.= ', coupon_price ASC';
                break;
			case 'hot':
                $order.= ', volume DESC';
                break;
			case 'rate':
                $order.= ', coupon_rate ASC';
                break;
			case 'default':
				$order.= ', '.$cinfo['sort'];
		}	
		switch ($mode) {
			case 'jiu':
                $map['coupon_price'] = array(array('egt',0.01),array('elt',9.99),'and');
                break;			
			case 'ershi':
                $map['coupon_price'] = array(array('egt',10),array('elt',20),'and');
                break;
			case 'tuijian':
                $map['top']=1;
                break;			
			case 'all':
				if($cinfo['mix_price']>0){$map['coupon_price'] = array('egt',$cinfo['mix_price']);}
				if($cinfo['max_price']>0){$map['coupon_price'] = array('elt',$cinfo['max_price']);}
				if($cinfo['max_price']>0 && $cinfo['mix_price']>0){$map['coupon_price'] = array(array('egt',$cinfo['mix_price']),array('elt',$cinfo['max_price']),'and');}
		}	
		if($cinfo['shop_type']){$map['shop_type'] = $cinfo['shop_type'];}		
		if($cinfo['mix_volume']>0){$map['volume'] = array('egt',$cinfo['mix_volume']);}
		if($cinfo['max_volume']>0){$map['volume'] = array('elt',$cinfo['max_volume']);}
		if($cinfo['max_volume']>0 && $cinfo['mix_volume']>0){$map['volume'] = array(array('egt',$cinfo['mix_volume']),array('elt',$cinfo['max_volume']),'and');}
		if($cinfo['thiscid']==0){
    		$id_arr = $this->_cate_mod->get_child_ids($cid, true);
    		$map['cate_id'] = array('IN', $id_arr);
			$today_wh['cate_id'] = array('IN', $id_arr);
		}
		if($cinfo['wait_time'] == '1'){
			$map['coupon_start_time'] = array('egt',time());
		}elseif($cinfo['wait_time'] =='2'){
			$map['coupon_start_time'] = array('elt',time());
		}
		if($cinfo['end_time'] == '1'){
			$map['coupon_end_time'] = array('egt',time());
		}	
		}else{
		switch ($sort) {
			case 'new':
                $order.= ', coupon_start_time DESC';
                break;			
			case 'price':
                $order.= ', coupon_price ASC';
                break;
			case 'hot':
                $order.= ', volume DESC';
                break;
			case 'rate':
                $order.= ', coupon_rate ASC';
                break;
			case 'default':
				$order.= ', '.C('ftx_index_sort');				 
		}	
		switch ($mode) {
			case 'jiu':
                $map['coupon_price'] = array(array('egt',0.01),array('elt',9.99),'and');
                break;			
			case 'ershi':
                $map['coupon_price'] = array(array('egt',10),array('elt',20),'and');
                break;
			case 'tuijian':
                $map['top']=1;
                break;			
			case 'all':
				if(C('ftx_index_mix_price')>0){$map['coupon_price'] = array('egt',C('ftx_index_mix_price'));}
				if(C('ftx_index_max_price')>0){$map['coupon_price'] = array('elt',C('ftx_index_max_price'));}
				if(C('ftx_index_mix_price')>0 && C('ftx_index_max_price')>0){$map['coupon_price'] = array(array('egt',C('ftx_index_mix_price')),array('elt',C('ftx_index_max_price')),'and');}				
		}	
		}
		$map['pass']="1";
		$map['item_type'] = 3;
		$page_size = C('ftx_index_page_size');
		$p = I('p',1,'intval'); //页码		
		$start = $page_size * ($p - 1) ;
		if(C('ftx_site_cache')){
		    $mdarray = $map;
			$mdarray['cid'] = $cid;			
			$mdarray['sort'] = $sort;	
			$mdarray['mode'] = $mode;
			$mdarray['p'] = $p;
			$mdarray['order'] = $order;
			$md_id = md5(implode("-",$mdarray));
			$file = 'temai_cate_'.$md_id;
			if(!$items_list = S($file)){
				$items_list = $this->_mod->where($map)->order($order)->limit($start . ',' . $page_size)->select();				
				foreach($items_list as $key=>$val){
					$items_list[$key]			= $val;					
					$items_list[$key]['zk']		= round(($val['coupon_price']/$val['price'])*10, 1); 
					if($config['URL_MODEL']==2){
					if($val['click_url']){					
					$items_list[$key]['ckurl'] = 'href="/jump'.$config['URL_PATHINFO_DEPR'].$val['num_iid'].$config['URL_HTML_SUFFIX'].'"';
					}else{
					$items_list[$key]['ckurl'] = 'isconvert="1" biz-itemid="'.$val['num_iid'].'"';
					}
					$items_list[$key]['itemurl'] = 'href="/item'.$config['URL_PATHINFO_DEPR'].$val['num_iid'].$config['URL_HTML_SUFFIX'].'"';
					$items_list[$key]['quanurl'] = 'href="/view'.$config['URL_PATHINFO_DEPR'].$val['num_iid'].$config['URL_HTML_SUFFIX'].'"';
					}else{
					if($val['click_url']){					
					$items_list[$key]['ckurl'] = 'href="'.U('jump/index',array('id'=>$val['num_iid'])).'"';
					}else{
					$items_list[$key]['ckurl'] = 'isconvert="1" biz-itemid="'.$val['num_iid'].'"';
					}
					$items_list[$key]['itemurl'] = 'href="'.U('item/index',array('id'=>$val['num_iid'])).'"';
					$items_list[$key]['quanurl'] = 'href="'.U('jump/view',array('id'=>$val['num_iid'])).'"';
					}
					$items_list[$key]['price'] = number_format($val['price'],1);
					$items_list[$key]['coupon_price'] = number_format($val['coupon_price'],1);					
				}				
				S($file,$items_list,C('ftx_site_cachetime'));
			}
		}else{
			$items_list = $this->_mod->where($map)->order($order)->limit($start . ',' . $page_size)->select();			
			foreach($items_list as $key=>$val){
				$items_list[$key]			= $val;				
				$items_list[$key]['zk']		= round(($val['coupon_price']/$val['price'])*10, 1); 	
				if($config['URL_MODEL']==2){
					if($val['click_url']){					
					$items_list[$key]['ckurl'] = 'href="/jump'.$config['URL_PATHINFO_DEPR'].$val['num_iid'].$config['URL_HTML_SUFFIX'].'"';
					}else{
					$items_list[$key]['ckurl'] = 'isconvert="1" biz-itemid="'.$val['num_iid'].'"';
					}
					$items_list[$key]['itemurl'] = 'href="/item'.$config['URL_PATHINFO_DEPR'].$val['num_iid'].$config['URL_HTML_SUFFIX'].'"';
					$items_list[$key]['quanurl'] = 'href="/view'.$config['URL_PATHINFO_DEPR'].$val['num_iid'].$config['URL_HTML_SUFFIX'].'"';
					}else{
					if($val['click_url']){					
					$items_list[$key]['ckurl'] = 'href="'.U('jump/index',array('id'=>$val['num_iid'])).'"';
					}else{
					$items_list[$key]['ckurl'] = 'isconvert="1" biz-itemid="'.$val['num_iid'].'"';
					}
					$items_list[$key]['itemurl'] = 'href="'.U('item/index',array('id'=>$val['num_iid'])).'"';
					$items_list[$key]['quanurl'] = 'href="'.U('jump/view',array('id'=>$val['num_iid'])).'"';
					}
				$items_list[$key]['price'] = number_format($val['price'],1);
				$items_list[$key]['coupon_price'] = number_format($val['coupon_price'],1);	
			}
		} 		
	
		$this->assign('items_list', $items_list);
		$count = $this->_mod->where($map)->count();
		$all_count = $this->_mod->where(array('pass'=>'1','item_type'=>'3'))->count();
		$pager = $this->_pager($count, $page_size);
		if($www_config['DEFAULT_THEME']=='san'){
		$this->assign('page', $pager->sshow());
		}else{
		$this->assign('page', $pager->jshow());
		}	
		$this->assign('total_item',$count);
        $this->assign('nav_curr', 'temai');
		$this->assign('all_count', $all_count);		
		$this->assign('sort', $sort);
		$this->assign('mode', $mode);
		if($cid){
		$this->assign('cid',$cid);		
		$this->assign('cinfo',$cinfo);	
		$this->_config_seo(C('ftx_seo_config.cate'), array(
            'cate_name' => $cinfo['name'],
            'seo_title' => $cinfo['seo_title'],
			'seo_keywords' => $cinfo['seo_keys'],
			'seo_description' => $cinfo['seo_desc'],
        ));
		}else{
		$this->_config_seo(C('ftx_seo_config.cate'), array(            
            'seo_title' =>'特卖精选',
			'seo_keywords' => '精选特卖',
			'seo_description' =>'小编精心挑选的特卖产品，个个都是精品哦。',
        ));	
		}        
		$this->display();
	}   

}