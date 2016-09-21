<?php
/**
 * 宝贝标签
 */
class itemTag { 
    public function top_lists($options) {
		$config_file = CONF_PATH . 'url.php';
        $config = require $config_file; 
		$items_mod = D('items');
		$map['pass'] = '1';
		$map['item_type'] = '2';	
		$map['top'] = '1';
		$options['num'] = isset($options['num']) ? trim($options['num']) : '6';
		$order = 'ordid asc,id desc';
        if(C('ftx_wait_time') == '1'){
			$map['coupon_start_time'] = array('egt',time());
		}elseif(C('ftx_wait_time') =='2'){
			$map['coupon_start_time'] = array('elt',time());
		}

		if(C('ftx_end_time') == '1'){
			$map['coupon_end_time'] = array('egt',time());
		}
        $data = $items_mod->where($map)->limit('0 ,' . $options['num'])->order($order)->select();
		foreach($data as $key=>$val){
            $data[$key]			= $val;	
            $data[$key]['zk']		= round(($val['coupon_price']/$val['price'])*10, 1); 
            if($config['URL_MODEL']==2){
            if($val['click_url']){					
            $data[$key]['ckurl'] = 'href="/jump'.$config['URL_PATHINFO_DEPR'].$val['num_iid'].$config['URL_HTML_SUFFIX'].'"';
            }else{
            $data[$key]['ckurl'] = 'isconvert="1" biz-itemid="'.$val['num_iid'].'"';
            }
            $data[$key]['itemurl'] = 'href="/item'.$config['URL_PATHINFO_DEPR'].$val['num_iid'].$config['URL_HTML_SUFFIX'].'"';
            $data[$key]['quanurl'] = 'href="/view'.$config['URL_PATHINFO_DEPR'].$val['num_iid'].$config['URL_HTML_SUFFIX'].'"';
            }else{
            if($val['click_url']){					
            $data[$key]['ckurl'] = 'href="'.U('jump/index',array('id'=>$val['num_iid'])).'"';
            }else{
            $data[$key]['ckurl'] = 'isconvert="1" biz-itemid="'.$val['num_iid'].'"';
            }
            $data[$key]['itemurl'] = 'href="'.U('item/index',array('id'=>$val['num_iid'])).'"';
            $data[$key]['quanurl'] = 'href="'.U('jump/view',array('id'=>$val['num_iid'])).'"';
            }					
            $data[$key]['price'] = number_format($val['price'],1);
            $data[$key]['coupon_price'] = number_format($val['coupon_price'],1);					
        }
		return $data;
	}       
    public function gy_lists($options) {
		$config_file = CONF_PATH . 'url.php';
        $config = require $config_file; 
		$items_mod = D('items');
		$map['pass'] = '1';
		$map['item_type'] = '3';
		$map['top'] = '1';
		$options['num'] = isset($options['num']) ? trim($options['num']) : '4';
		$order = 'ordid asc,id desc';
        if(C('ftx_wait_time') == '1'){
			$map['coupon_start_time'] = array('egt',time());
		}elseif(C('ftx_wait_time') =='2'){
			$map['coupon_start_time'] = array('elt',time());
		}

		if(C('ftx_end_time') == '1'){
			$map['coupon_end_time'] = array('egt',time());
		}
        $data = $items_mod->where($map)->limit('0 ,' . $options['num'])->order($order)->select();
		foreach($data as $key=>$val){
            $data[$key]			= $val;	
            $data[$key]['zk']		= round(($val['coupon_price']/$val['price'])*10, 1); 
            if($config['URL_MODEL']==2){
            if($val['click_url']){					
            $data[$key]['ckurl'] = 'href="/jump'.$config['URL_PATHINFO_DEPR'].$val['num_iid'].$config['URL_HTML_SUFFIX'].'"';
            }else{
            $data[$key]['ckurl'] = 'isconvert="1" biz-itemid="'.$val['num_iid'].'"';
            }
            $data[$key]['itemurl'] = 'href="/item'.$config['URL_PATHINFO_DEPR'].$val['num_iid'].$config['URL_HTML_SUFFIX'].'"';
            $data[$key]['quanurl'] = 'href="/view'.$config['URL_PATHINFO_DEPR'].$val['num_iid'].$config['URL_HTML_SUFFIX'].'"';
            }else{
            if($val['click_url']){					
            $data[$key]['ckurl'] = 'href="'.U('jump/index',array('id'=>$val['num_iid'])).'"';
            }else{
            $data[$key]['ckurl'] = 'isconvert="1" biz-itemid="'.$val['num_iid'].'"';
            }
            $data[$key]['itemurl'] = 'href="'.U('item/index',array('id'=>$val['num_iid'])).'"';
            $data[$key]['quanurl'] = 'href="'.U('jump/view',array('id'=>$val['num_iid'])).'"';
            }					
            $data[$key]['price'] = number_format($val['price'],1);
            $data[$key]['coupon_price'] = number_format($val['coupon_price'],1);					
        }
		return $data;
	} 
	/**
	 *	status  0：默认 1：不显示结束 2：只显示未开始
	 */

	public function lists($options) {
		$items_mod = D('items');
		$map['pass'] = '1';
		$options['cid'] = isset($options['cid']) ? trim($options['cid']) : '';
		$options['num'] = isset($options['num']) ? trim($options['num']) : '6';
		$options['status'] = isset($options['status']) ? trim($options['status']) : '1';
		$options['min_price'] = isset($options['min_price']) ? trim($options['min_price']) : '';
		$options['max_price'] = isset($options['max_price']) ? trim($options['max_price']) : '';
		$options['min_volume'] = isset($options['min_volume']) ? trim($options['min_volume']) : '';
		$options['max_volume'] = isset($options['max_volume']) ? trim($options['max_volume']) : '';

		if($options['min_price']>0){$map['coupon_price'] = array('egt',$options['min_price']);}
		if($options['max_price']>0){$map['coupon_price'] = array('elt',$options['max_price']);}
		if($options['min_price']>0 && $options['max_price']>0){$map['coupon_price'] = array(array('egt',$options['min_price']),array('elt',$options['max_price']),'and');}

		if($options['min_volume']>0){$map['volume'] = array('egt',$options['min_volume']);}
		if($options['max_volume']>0){$map['volume'] = array('elt',$options['max_volume']);}
		if($options['max_volume']>0 && $options['min_volume']>0){$map['volume'] = array(array('egt',$options['min_volume']),array('elt',$options['max_volume']),'and');}

		if($options['status'] == 1){
			$map['coupon_end_time'] = array('egt',time());
		}else if($options['status'] == 2){
			$map['coupon_start_time'] = array('egt',time());
		}
		if($options['cid']){
			$id_arr = D('items_cate')->get_child_ids($options['cid'], true);
			$map['cate_id'] = array('IN', $id_arr);
		}

        $data = $items_mod->where($map)->limit('0 ,' . $options['num'])->order(C('ftx_index_sort'))->select();
		return $data;
	}
	
	/**
	 *	status  0：默认 1：不显示结束 2：只显示未开始
	 */

	public function brand($options) {
		$items_mod = D('items');
		$map['pass'] = '1';		
		$options['id'] = isset($options['id']) ? trim($options['id']) : '';
		$options['num'] = isset($options['num']) ? trim($options['num']) : '8';
		$options['order'] = isset($options['order']) ? trim($options['order']) : '';
		$options['status'] = isset($options['status']) ? trim($options['status']) : '0';		
		if($options['id']){			
		$map['sellerId'] = $options['id'];
		}		
        $data = $items_mod->where($map)->limit('0 ,' . $options['num'])->order($options['order'])->select();
		return $data;		
	}	
	
	public function hotbrand($options) {
		$brand_mod = D('brand');
		$map['pass'] = '1';
		$options['num'] = isset($options['num']) ? trim($options['num']) : '8';
		$options['order'] = isset($options['order']) ? trim($options['order']) : 'ordid asc';				
        $data = $brand_mod->where($map)->limit('0 ,' . $options['num'])->order($options['order'])->select();
		return $data;		
	}	
	public function rebrand($options) {
		$brand_mod = D('brand');
		$map['pass'] = '1';		
		$options['id'] = isset($options['id']) ? trim($options['id']) : '';
		$options['order'] = isset($options['order']) ? trim($options['order']) : '';	
		if($options['id']){			
		$id_arr = D('items_cate')->get_child_ids($options['id'], true);
		$map['cate_id'] = array('IN', $id_arr);
		}		
        $data = $brand_mod->where($map)->order($options['order'])->select();
		return $data;		
	}	
}