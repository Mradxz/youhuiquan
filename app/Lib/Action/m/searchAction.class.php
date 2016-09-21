<?php
class searchAction extends FirstendAction {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('items');        
    } 

	public function _empty(){
		$this->index();
	}
	
	public function index() {
	    $config_file = CONF_PATH . 'url.php';
        $config = require $config_file; 
		$sort	= I('sort', 'new', 'trim'); //排序	
		$mode	= I('mode', '', 'trim'); //排序	
		$k		= I('k');
		$order	= 'ordid asc';
		switch ($sort) {
			case 'new':
                $order.= ', coupon_start_time DESC';
                break;			
			case 'price':
                $order.= ', coupon_price ASC';
                break;
			case 'hot':
                $order.= ', volume DESC';            
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
			}		        
		if($k){
			if(strpos($k,' ')){
				$karr=split(' ',$k);
				foreach($karr as $kw){
					$like[] = array('like', '%' . $kw . '%');
				}
				$where['title'] = $like;
			}else{
				$where['title'] = array('like', '%' . $k . '%');
			}
			$this->assign('k',$k);
		}
		
		$where['pass'] = '1';		
		$page_size = 10;
        $p = I('p',1, 'intval'); //页码		
        $start = $page_size * ($p - 1) ; 
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
		if(IS_AJAX){
		if(!$items_list){$this->ajaxReturn(0, '加载完成');}
		$this->assign('items_list', $items_list);		
        $this->ajaxReturn(1, '', $items_list);
		}
		$this->assign('sort', $sort);
		$this->assign('mode', $mode);
		$this->assign('items_list', $items_list);		
		$count = $this->_mod->where($where)->count();
		$pager = $this->_pager($count, $page_size);
		$page = $pager->mshow();
		$page = str_replace('/search/index/k/','/?m=search&a=index&k=',$page);
		$page = str_replace('/p/','&p=',$page);
		$page = str_replace('/search-index-k-','/?m=search&a=index&k=',$page);
		$page = str_replace('-p-','&p=',$page);
		$page = str_replace('.html','',$page);
		$this->assign('nav_curr', 'index');
		$this->assign('page',$page);		
		$page_seo=array(
			'title' => '搜索"'.$k.'"的宝贝结果页 - 第'.$p.'页 - '.C('ftx_site_name'),
		);
		$this->assign('page_seo', $page_seo);
		$maxpage = ceil($count/$page_size);
		$this->assign('maxpage', $maxpage);
		$this->assign('p', $p);		
		$this->display();
    }
 
}