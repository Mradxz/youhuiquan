<?php
class indexAction extends FirstendAction {
	
	public function _initialize() {
		parent::_initialize();
		$this->_mod = D("items");
		$this->_cate_mod = D('items_cate');
	}
	
	public function _empty(){
		$this->index();
	}
	public function index() {
		$www_config_file = CONF_PATH . 'm/config.php';
		$www_config = include $www_config_file;
		$p		= I('p',1 ,'intval'); //页码
		$sort	= I('sort', 'default', 'trim'); //排序
		$mode	= I('mode', 'all', 'trim'); //排序
		$order = 'ordid asc';
		$config_file = CONF_PATH . 'url.php';
        $config = require $config_file; 
		switch ($sort){
			case 'new':
 				$order.= ', add_time DESC';
  				break;
  			case 'price':
				$order.= ', coupon_price ASC';
				break;
			case 'rate':
				$order.= ', coupon_rate ASC';
				break;
			case 'hot':
				$order.= ', hits DESC';
 				break;
			case 'default':
				$order.= ', '.C('ftx_index_sort');
				break;
		}
		switch ($mode) {
			case 'jiu':
                $where['coupon_price'] = array(array('egt',0.01),array('elt',9.99),'and');
                break;			
			case 'ershi':
                $where['coupon_price'] = array(array('egt',10),array('elt',20),'and');
                break;
			case 'tuijian':
                $where['top']=1;
                break;			
			case 'all':
				if(C('ftx_index_mix_price')>0){$where['coupon_price'] = array('egt',C('ftx_index_mix_price'));}
				if(C('ftx_index_max_price')>0){$where['coupon_price'] = array('elt',C('ftx_index_max_price'));}
				if(C('ftx_index_mix_price')>0 && C('ftx_index_max_price')>0){$where['coupon_price'] = array(array('egt',C('ftx_index_mix_price')),array('elt',C('ftx_index_max_price')),'and');}
		}
		if(C('ftx_index_not_text')){
			$not_arr = explode(",",C('ftx_index_not_text'));
			$arrs =array();
			foreach($not_arr as $key =>$value){
				$arrs[] = '%'.$value.'%';
			}
			$where['title'] =array('notlike',$arrs,'AND');
		}
		if(C('ftx_index_cids')){
			$where['cate_id'] =  array('in',C('ftx_index_cids'));
		}
		if(C('ftx_wait_time') == '1'){
			$where['coupon_start_time'] = array('egt',time());
		}elseif(C('ftx_wait_time') =='2'){
			$where['coupon_start_time'] = array('elt',time());
		}

		if(C('ftx_end_time') == '1'){
			$where['coupon_end_time'] = array('egt',time());
		}
		if(C('ftx_index_shop_type')){$where['shop_type'] = C('ftx_index_shop_type');}		
		if(C('ftx_index_mix_volume')>0){$where['volume'] = array('egt',C('ftx_index_mix_volume'));}
		if(C('ftx_index_max_volume')>0){$where['volume'] = array('elt',C('ftx_index_max_volume'));}
		if(C('ftx_index_mix_volume')>0 && C('ftx_index_max_volume')>0){$where['volume'] = array(array('egt',C('ftx_index_mix_volume')),array('elt',C('ftx_index_max_volume')),'and');}
        if($www_config['DEFAULT_THEME']=='san'){
		$where['item_type'] = '2';	
		}
		$where['pass'] = '1';	
		$page_size = 10;
        $start = $page_size * ($p - 1) ;
		$mdarray = $where;
		$mdarray['sort'] = $sort;
		$mdarray['mode'] = $mode;
		$mdarray['order'] = $order;
		$mdarray['p'] = $p; 
		$md_id = md5(implode("-",$mdarray));
		$file = 'm_index_'.$md_id; 
		if(C('ftx_site_cache')){
			if(!$items_list = S($file)){
				$items_list = $this->_mod->field('id,num_iid,title,price,volume,coupon_price,pic_url,shop_type,item_type,q_price,wap_url,click_url,q_sur,q_has,coupon_start_time')->where($where)->order($order)->limit($start . ',' . $page_size)->select();				
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
					$items_list[$key]['quanurl'] = 'href="'.$val['wap_url'].'"';
					}else{
					if($val['click_url']){					
					$items_list[$key]['ckurl'] = 'href="'.U('jump/index',array('id'=>$val['num_iid'])).'"';
					}else{
					$items_list[$key]['ckurl'] = 'isconvert="1" biz-itemid="'.$val['num_iid'].'"';
					}
					$items_list[$key]['itemurl'] = 'href="'.U('item/index',array('id'=>$val['num_iid'])).'"';
					$items_list[$key]['quanurl'] = 'href="'.$val['wap_url'].'"';
					}
					$items_list[$key]['pic']   = attach(get_thumb($val['pic_url'], '_b'),'item');
					if($val['shop_type']==B){
					$items_list[$key]['typeimg'] = '/static/images/tmall.png';	
					$items_list[$key]['type'] = '天猫';	
					}else{
					$items_list[$key]['typeimg'] = '/static/images/taobao.png';	
					$items_list[$key]['type'] = '淘宝';	
					}
					$items_list[$key]['price'] = number_format($val['price'],1);
					$items_list[$key]['newico'] = sanwapnewicon($val['coupon_start_time']);
					$items_list[$key]['coupon_price'] = number_format($val['coupon_price'],1);					
				}
				S($file,$items_list,C('ftx_site_cachetime'));
			}
		}else{
			$items_list = $this->_mod->field('id,num_iid,title,price,volume,coupon_price,pic_url,shop_type,item_type,q_price,wap_url,click_url,q_sur,q_has,coupon_start_time')->where($where)->order($order)->limit($start . ',' . $page_size)->select();			
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
				$items_list[$key]['quanurl'] = 'href="'.$val['wap_url'].'"';
				}else{
				if($val['click_url']){					
				$items_list[$key]['ckurl'] = 'href="'.U('jump/index',array('id'=>$val['num_iid'])).'"';
				}else{
				$items_list[$key]['ckurl'] = 'isconvert="1" biz-itemid="'.$val['num_iid'].'"';
				}
				$items_list[$key]['itemurl'] = 'href="'.U('item/index',array('id'=>$val['num_iid'])).'"';
				$items_list[$key]['quanurl'] ='href="'.$val['wap_url'].'"';
				}
				$items_list[$key]['pic']   = attach(get_thumb($val['pic_url'], '_b'),'item');
				if($val['shop_type']==B){
				$items_list[$key]['typeimg'] = '/static/images/tmall.png';	
				$items_list[$key]['type'] = '天猫';	
				}else{
				$items_list[$key]['typeimg'] = '/static/images/taobao.png';	
				$items_list[$key]['type'] = '淘宝';	
				}
				$items_list[$key]['price'] = number_format($val['price'],1);
				$items_list[$key]['coupon_price'] = number_format($val['coupon_price'],1);	
				$items_list[$key]['newico'] = sanwapnewicon($val['coupon_start_time']);
			}
		}	
		if(IS_AJAX){
		if(!$items_list){$this->ajaxReturn(0, '加载完成');}
		$this->assign('items_list', $items_list);		
        $this->ajaxReturn(1, '', $items_list);
		}
		$this->assign('items_list', $items_list);
		$count = $this->_mod->where($where)->count();
		$all_quan = $this->_mod->where(array('pass'=>'1','item_type'=>'2'))->count();
		$pager = $this->_pager($count, $page_size);
		$this->assign('page', $pager->mshow());		
		$ajaxurl = C('ftx_header_html').'?m=index&a=index&sort='.$sort.'&mode='.$mode;
		$this->assign('ajaxurl',$ajaxurl);
    	$this->assign('nav_curr', 'index');
		$this->assign('sort', $sort);
		$this->assign('mode', $mode);
		$this->assign('all_quan', $all_quan);
    	$page_seo=array(
			'title' => C('ftx_wap_title'),
			'keywords' => C('ftx_wap_keywords'),
			'description' => C('ftx_wap_description'),
		);
		$maxpage = ceil($count/$page_size);
		$this->assign('maxpage', $maxpage);
		$this->assign('p', $p);
		$this->assign('page_seo', $page_seo);
		$this->display('index');
	  }
	
	
	
	public function cate(){
		$p = I('p',1,'intval'); //页码		
		$cid	=	I('cid','', 'intval');
		$sort	=	I('sort', 'default', 'trim'); //排序
		$mode	= I('mode', 'all', 'trim'); //排序
		$order	=	'ordid asc ';
        $cinfo = $this->_cate_mod->where(array('id'=>$cid))->find();
       !$cinfo && $this->_404();
	   $config_file = CONF_PATH . 'url.php';
        $config = require $config_file; 
		switch ($sort) {
			case 'new':
                $order.= ', coupon_start_time DESC';
                break;
			case 'price':
                $order.= ', price DESC';
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
		$this->assign('cid',$cid);		
		$this->assign('cinfo',$cinfo);
		if($cinfo['wait_time'] == '1'){
			$map['coupon_start_time'] = array('egt',time());
		}elseif($cinfo['wait_time'] =='2'){
			$map['coupon_start_time'] = array('elt',time());
		}
		if($cinfo['end_time'] == '1'){
			$map['coupon_end_time'] = array('egt',time());
		}	
		$map['pass']="1";
		$page_size = 10;		
		$start = $page_size * ($p - 1) ;
		$mdarray = $map;
		$mdarray['cid'] = $cid;
		$mdarray['sort'] = $sort;
		$mdarray['mode'] = $mode;		
		$mdarray['p'] = $p;
		$mdarray['order'] = $order;
		$md_id = md5(implode("-",$mdarray));
		$file = 'm_cate_'.$md_id;
		if(C('ftx_site_cache')){
			if(!$items_list = S($file)){
				$items_list = $this->_mod->field('id,num_iid,title,price,volume,coupon_price,pic_url,shop_type,item_type,q_price,wap_url,click_url,q_sur,q_has,coupon_start_time')->where($map)->order($order)->limit($start . ',' . $page_size)->select();				
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
		    		$items_list[$key]['quanurl'] = 'href="'.$val['wap_url'].'"';
		    		}else{
		    		if($val['click_url']){					
		    		$items_list[$key]['ckurl'] = 'href="'.U('jump/index',array('id'=>$val['num_iid'])).'"';
		    		}else{
		    		$items_list[$key]['ckurl'] = 'isconvert="1" biz-itemid="'.$val['num_iid'].'"';
		    		}
		    		$items_list[$key]['itemurl'] = 'href="'.U('item/index',array('id'=>$val['num_iid'])).'"';
		    		$items_list[$key]['quanurl'] = 'href="'.$val['wap_url'].'"';
		    		}
					$items_list[$key]['pic']   = attach(get_thumb($val['pic_url'], '_b'),'item');	
					if($val['shop_type']==B){
					$items_list[$key]['typeimg'] = '/static/images/tmall.png';	
					$items_list[$key]['type'] = '天猫';	
					}else{
					$items_list[$key]['typeimg'] = '/static/images/taobao.png';	
					$items_list[$key]['type'] = '淘宝';	
					}
					$items_list[$key]['price'] = number_format($val['price'],1);
					$items_list[$key]['coupon_price'] = number_format($val['coupon_price'],1);	
					$items_list[$key]['newico'] = sanwapnewicon($val['coupon_start_time']);
				}
				S($file,$items_list,C('ftx_site_cachetime'));
			}
		}else{
			$items_list = $this->_mod->field('id,num_iid,title,price,volume,coupon_price,pic_url,shop_type,item_type,q_price,wap_url,click_url,q_sur,q_has,coupon_start_time')->where($map)->order($order)->limit($start . ',' . $page_size)->select();			
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
		    		$items_list[$key]['quanurl'] = 'href="'.$val['wap_url'].'"';
		    		}else{
		    		if($val['click_url']){					
		    		$items_list[$key]['ckurl'] = 'href="'.U('jump/index',array('id'=>$val['num_iid'])).'"';
		    		}else{
		    		$items_list[$key]['ckurl'] = 'isconvert="1" biz-itemid="'.$val['num_iid'].'"';
		    		}
		    		$items_list[$key]['itemurl'] = 'href="'.U('item/index',array('id'=>$val['num_iid'])).'"';
		    		$items_list[$key]['quanurl'] = 'href="'.$val['wap_url'].'"';
		    		}
	    			$items_list[$key]['pic']= attach(get_thumb($val['pic_url'], '_b'),'item');						
	    			if($val['shop_type']==B){
					$items_list[$key]['typeimg'] = '/static/images/tmall.png';	
					$items_list[$key]['type'] = '天猫';	
					}else{
					$items_list[$key]['typeimg'] = '/static/images/taobao.png';	
					$items_list[$key]['type'] = '淘宝';	
					}
				$items_list[$key]['price'] = number_format($val['price'],1);
				$items_list[$key]['coupon_price'] = number_format($val['coupon_price'],1);	
				$items_list[$key]['newico'] = sanwapnewicon($val['coupon_start_time']);
			}
		}			
		if(IS_AJAX){
		if(!$items_list){$this->ajaxReturn(0, '加载完成');}
		$this->assign('items_list', $items_list);		
        $this->ajaxReturn(1, '', $items_list);
		}		
		$this->assign('items_list', $items_list);		
		$count = $this->_mod->where($map)->count();
		$pager = $this->_pager($count, $page_size);
		$this->assign('page', $pager->mshow());	
		$ajaxurl = C('ftx_header_html').'?m=index&a=cate&cid='.$cid.'&sort='.$sort.'&mode='.$mode;
		$this->assign('ajaxurl',$ajaxurl);		
        $this->assign('nav_curr', 'index');
		$this->assign('sort', $sort);
		$this->assign('mode', $mode);
        $this->_config_seo(C('ftx_seo_config.cate'), array(
            'cate_name' => $cinfo['name'],
            'seo_title' => $cinfo['seo_title'],
			'seo_keywords' => $cinfo['seo_keys'],
			'seo_description' => $cinfo['seo_desc'],
        ));
		$maxpage = ceil($count/$page_size);
		$this->assign('maxpage', $maxpage);
		$this->assign('p', $p);
		$this->display('cate');
	}
}