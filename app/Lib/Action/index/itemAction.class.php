<?php

class itemAction extends FirstendAction {	
    public function _initialize() {
        parent::_initialize();
		$this->_mod = D('items');
        $this->_cate_mod = D('items_cate');
		$this->assign('nav_curr', 'index'); 
    }

    /**
     * 商品详细页
     */
    public function index() {
        $id = I('id', '','trim');
        $item = $this->_mod->where(array('num_iid' => $id))->find();
        !$item && $this->_404();
		$item['type'] = $item['shop_type'].''.$item['que'];
		if($item['shop_type']==B){
		$type = '天猫';			
		}
		if($item['shop_type']==C){
		$type = '淘宝';			
		}
		$this->assign('type', $type);	
		$item['ccid'] = $item['cate_id'];	
			if(isset($item['cate_id'])){
            $item['cname'] = D('items_cate')->where(array('id'=>$item['cate_id']))->getField('name');	
        }	

		if(C('ftx_item_hit')){
			$hits_data = array('hits'=>array('exp','hits+1'));
			$this->_mod->where(array('num_iid'=>$id))->setField($hits_data);
		}
        $config_file = CONF_PATH . 'url.php';
        $config = require $config_file; 
		$cate_data =F('cate_list');
		$cid = $item["cate_id"];
		$pid = $cate_data[$cid]['pid'];
		$tag_list = D('items')->get_tags_by_title($item['title']);
		$tags = implode(',', $tag_list);
		$this->assign('tags', $tag_list);
		if(false === $cate_list = F('cate_list')) {
			$cate_list = D('items_cate')->cate_cache();
		}
		$this->assign('cate_list', $cate_list); //分类
		if($config['URL_MODEL']==2){
		if($item['click_url']){					
		$item['ckurl'] = 'href="/jump'.$config['URL_PATHINFO_DEPR'].$item['num_iid'].$config['URL_HTML_SUFFIX'].'"';
		}else{
		$item['ckurl'] = 'isconvert="1" biz-itemid="'.$item['num_iid'].'"';
		}
		$item['itemurl'] = 'href="/item'.$config['URL_PATHINFO_DEPR'].$item['num_iid'].$config['URL_HTML_SUFFIX'].'"';
		$item['url'] = 'item'.$config['URL_PATHINFO_DEPR'].$item['num_iid'].$config['URL_HTML_SUFFIX'];
		$item['quanurl'] = 'href="/view'.$config['URL_PATHINFO_DEPR'].$item['num_iid'].$config['URL_HTML_SUFFIX'].'"';
		}else{
		if($item['click_url']){					
		$item['ckurl'] = 'href="'.U('jump/index',array('id'=>$item['num_iid'])).'"';
		}else{
		$item['ckurl'] = 'isconvert="1" biz-itemid="'.$item['num_iid'].'"';
		}
		$item['itemurl'] = 'href="'.U('item/index',array('id'=>$item['num_iid'])).'"';
		$item['url'] = U('item/index',array('id'=>$item['num_iid']));
		$item['quanurl'] = 'href="'.U('jump/view',array('id'=>$item['num_iid'])).'"';
		}
		if(!$item['intro']){
		$item['intro'] = $item['title'];
		}
		if(!$item['desc']){
        $infoUrl = "http://hws.m.taobao.com/cache/mtop.wdetail.getItemDescx/4.1/?data=%7B%22item_num_id%22%3A%22".$item['num_iid']."%22%7D";
        $che = curl_init();         
        curl_setopt($che, CURLOPT_URL, $infoUrl);
        curl_setopt($che, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($che, CURLOPT_FOLLOWLOCATION,true);
        curl_setopt($che, CURLOPT_MAXREDIRS,2);
        $source = curl_exec($che);
        curl_close($che);
		$dapi = json_decode($source,true);
		$imglist = $dapi['data']['images'];
	    $num   = count($imglist);
		for($i=0;$i<$num;$i++){
		$imgurl .= '<img class="lazy" src='.$imglist[$i].'>';
		}
		$item['desc'] = $imgurl;				
		$data['desc'] = $item['desc'];
		$this->_mod->where(array('id' => $id))->save($data);		
		}
		$map['num_iid']  = array('neq',$id);
		$map['cate_id']  = $cid;
		$map['pass']  = 1;
		if(C('ftx_site_cache')){
			$file = 'item_orlike_'.$cid;
			if(!$items_list = S($file)){				
				$items_list = $this->_mod->where($map)->limit('0,12')->order('id desc')->select();				
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
			
				$items_list = $this->_mod->where($map)->limit('0,12')->order('id desc')->select();			
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
		$this->assign('item', $item);
		$this->_config_seo(C('ftx_seo_config.item'), array(
            'title' => $item['title'],
            'intro' => $item['intro'],
			'price' => $item['price'],
			'coupon_price' => $item['coupon_price'],
			'tags' => $tags,
            'seo_title' => $item['seo_title'],
            'seo_keywords' => $item['seo_keys'],
            'seo_description' => $item['seo_desc'],
        ));
		
		$this->display();
    }


}