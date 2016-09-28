<?php

class robotsAction extends BackendAction {

	private $_tbconfig = null;
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('robots');
        $this->_cate_mod = D('items_cate');
		$api_config = M('items_site')->where(array('code' => 'taobao'))->getField('config');
        $this->_tbconfig = unserialize($api_config);
//        echo '<pre>';
//        var_dump($this->_tbconfig);die;
    }
 
	 public function _before_index() {
        $res = $this->_cate_mod->field('id,name')->select();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
        }
        $this->assign('cate_list', $cate_list);
		$robots_collect_data = F('robots_collect_data');
		$this->assign('robots_collect_data', $robots_collect_data);
        $this->sort = 'ordid ASC,';
        $this->order ='last_time DESC';
    }

	public function add(){
		if (IS_POST) {
			$name					= I('name','', 'trim');
			$cid					= I('cid','', 'trim');
			$recid					= I('recid','', 'trim');
			$cate_id				= I('cate_id','', 'trim');
			$keyword				= I('keyword','', 'trim');
			$page					= I('page','1', 'trim');
			$sort					= I('sort','normal', 'trim');
			$start_commissionRate	= I('start_commissionRate','10', 'trim');
			$end_commissionRate		= I('end_commissionRate','9999', 'trim');
			$start_price			= I('start_price','0', 'trim');
			$end_price				= I('end_price','99999', 'trim'); 
			$shop_type				= I('shop_type','all', 'trim');
			if( !$name||!trim($name) ){
				$this->error('请填写采集器名称');
			}
			if( !$cate_id||!trim($cate_id) ){
				$this->error('请选择商品分类');
			}
			if (!$keyword && !$cid) {
				$this->error('请填写关键词或选择API分类');
			}
			if($start_commissionRate > $end_commissionRate){
				$this->error('起始佣金不能高于最高佣金');
			}
			
			$data['name'] = $name;
			$data['cid'] = $cid;
			$data['recid'] = $recid;
			$data['cate_id'] = $cate_id;
			$data['keyword'] = $keyword;
			$data['page'] = $page;
			$data['sort'] = $sort;
			$data['start_commissionRate'] = $start_commissionRate;
			$data['end_commissionRate'] = $end_commissionRate;
			$data['start_price'] = $start_price;
			$data['end_price'] = $end_price;
			$data['shop_type'] = $shop_type;

			$this->_mod->create($data);
			$item_id = $this->_mod->add();
			$this->success('添加成功！');
		}
	}

	 public function edit() {
        if (IS_POST) {
			$id			= I('id','', 'trim');
			$name		= I('name','', 'trim');
			$cid		= I('cid','', 'trim');
			$recid		= I('recid','', 'trim');
			$cate_id	= I('cate_id','', 'trim');
			$keyword	= I('keyword', 'trim');
			$page		= I('page','', 'trim');
			$sort		= I('sort','', 'trim');
			$start_commissionRate		= I('start_commissionRate','', 'trim');
			$end_commissionRate		= I('end_commissionRate','', 'trim');
			$start_price			= I('start_price','', 'trim');
			$end_price				= I('end_price','', 'trim');
			$shop_type				= I('shop_type','all', 'trim');

			if( !$name||!trim($name) ){
				$this->error('请填写采集器名称');
			}
			if( !$cate_id||!trim($cate_id) ){
				$this->error('请选择商品分类');
			}

			//API采集
			if (!$keyword && !$cid) {
				$this->error('请填写关键词或选择填写淘宝分类id');
			}
			if($start_commissionRate > $end_commissionRate){
				$this->error('起始佣金不能高于最高佣金');
			}
		
			$data['name'] = $name;
			$data['cid'] = $cid;
			$data['recid'] = $recid;
			$data['cate_id'] = $cate_id;
			$data['keyword'] = $keyword;
			$data['page'] = $page;
			$data['sort'] = $sort;
			$data['start_commissionRate'] = $start_commissionRate;
			$data['end_commissionRate'] = $end_commissionRate;
			$data['start_price'] = $start_price;
			$data['end_price'] = $end_price;
			$data['shop_type'] = $shop_type;
 
            $this->_mod->where(array('id'=>$id))->save($data);
            $this->success(L('operation_success'));
        } else {
            $id = $this->_get('id','intval');
            $item = $this->_mod->where(array('id'=>$id))->find();
            $spid = $this->_cate_mod->where(array('id'=>$item['cate_id']))->getField('spid');
            if( $spid==0 ){
                $spid = $item['cate_id'];
            }else{
                $spid .= $item['cate_id'];
            }//p($item);
            $this->assign('selected_ids',$spid); //分类选中
            $this->assign('info', $item);
            //来源
            $orig_list = M('items_orig')->select();
            $this->assign('orig_list', $orig_list);
			if (!function_exists("curl_getinfo")) {
				$this->error(L('curl_not_open'));
			}
			//获取淘宝商品分类
			$items_cate = $this->_get_tbcats();
			$this->assign('items_cate', $items_cate);
            $this->display();
        }
    }


	public function add_do() {
		//判断CURL
        if (!function_exists("curl_getinfo")) {
            $this->error(L('curl_not_open'));
        }
		//获取淘宝商品分类
        $items_cate = $this->_get_tbcats();
        $this->assign('items_cate', $items_cate);
        $this->display();
	}

	public function collect(){
		$id	= I('id','','intval');
		$auto	= I('auto',0,'intval');
		$p		= I('p',1,'intval');
		if($auto){
			if(!$this->_tbconfig['app_key']){$this->ajaxReturn(0, '请设置淘宝appkey');}
			$rid	= I('rid',0,'intval');
			$robots_collect_data['rid'] = $rid;
			$robots_collect_data['p'] = $p;
			F('robots_collect_data',$robots_collect_data);
			if(false === F('robots_time')){
				F('robots_time', time());
			}
			if(!$rid){
				$where['status'] = 1;
				$taorobots = $this->_mod->where($where)->order('ordid asc')->select();
				F('taorobots', $taorobots);
				$rid = 0;
			}
			$taorobots = F('taorobots');
			$date = $taorobots[$rid];
			if(!$date){
				F('totalcoll', NULL);
				F('robots_time', NULL);
				$this->ajaxReturn(0, '一键全自动已经采集完成！请返回，谢谢');
			}
			
			if ($p > $date['page']) {
				$p = 1;
				$rid = $rid+1;
				$date = $taorobots[$rid];
				if(!$date){
					F('totalcoll', NULL);
					F('robots_time', NULL);
					$this->ajaxReturn(0, '一键全自动已经采集完成！请返回，谢谢');
				}
			}
			$np = $p+1;

			$result_data = $this->api_collect($date,$p);
			$this->assign('result_data', $result_data);
			$msg['title'] = '一键全自动采集';
			$msg['np'] = $np;
			$msg['rid'] = $rid;
			$this->assign('date',$date);
			$this->assign('ftxrobots_count',count($taorobots));
			$this->assign('rids',$rid+1);
			$resp = $this->fetch('auto_collect');
			$this->ajaxReturn(1,$msg,$resp);
		}else{
			$date = $this->_mod->where(array('id'=>$id))->find();
			F('robot_setting', $date);
		}
		
		if($date){ 
				if(!$this->_tbconfig['app_key']){$this->ajaxReturn(0, '请设置淘宝appkey');}
				if ($p > $date['page']) {
					F('totalcoll', NULL);
					$this->ajaxReturn(0, '已经采集完成'.$date['page'].'页！请返回，谢谢');
				}
				$result_data = $this->api_collect($date,$p);
				$this->assign('result_data', $result_data);
				$resp = $this->fetch('collect');
				$this->ajaxReturn(1, '', $resp);
		 
		}else{
			$this->ajaxReturn(0, 'error');
		}
	}
 
	public function api_collect($date,$p){
		$this->_mod->where(array('id'=>$date['id']))->save(array('last_page'=>$p,'last_time'=>time()));
		if (false === $totalcoll = F('totalcoll')) {
			$totalcoll = 0;
		}
		if (false === $robots_time = F('robots_time')) {
			$robots_time = time();
			F('robots_time', time());
		}
		$map['keyword']		= $date['keyword'];									//关键词
		$map['cid']			= $date['cid'];										//api分类ID
		$map['cate_id']		= $date['cate_id'];									//入库分类ID
		if($date['start_commissionRate']<100){
			$map['start_commissionRate']	= ($date['start_commissionRate']*100);//佣金比率下限
		}else{
			$map['start_commissionRate']	= $date['start_commissionRate'];	//佣金比率下限
		}
		if($date['end_commissionRate']<100){
			$map['end_commissionRate']		= ($date['end_commissionRate']*100);	//佣金比率上限
		}else{
			$map['end_commissionRate']		= $date['end_commissionRate'];		//佣金比率上限
		}
		$map['start_price']				= $date['start_price'];				//价格下限
		$map['end_price']				= $date['end_price'];				//价格上限
		$map['shop_type']				= $date['shop_type'];				//是否天猫商品
		$map['recid']					= $date['recid'];					//是否更新分类
		$map['sort']					= $date['sort'];					//排序方法		
		$result							= $this->_get_list($map, $p);
		$taobaoke_item_list				= $result['item_list'];
		$totalnum						= $result['count']; 
		$coll=0;
		$thiscount=0;
		if(is_array($taobaoke_item_list)){
			$msg = '成功！';
		}else{
			$msg = '失败！';
		}
		foreach ($taobaoke_item_list as $key => $val) {
			/*入库操作START*/
			$coupon_add_time = C('ftx_coupon_add_time');
			if($coupon_add_time){
				$times	=	(int)(strtotime(date("Y-m-d H:00:00",time()))+$coupon_add_time*3600);
			}else{
				$times	=	(int)(strtotime(date("Y-m-d H:00:00",time()))+72*86400);
			}
			$val['coupon_start_time'] = strtotime(date("Y-m-d H:00:00",time()));
			$val['coupon_end_time'] = $times;
			$val['astime'] = date("Ymd");
			$val['recid'] = $map['recid'];
			$res= $this->_ajax_ftx_publish_insert($val);
			if($res>0){
				$coll++;
				$totalcoll++;
			}
			/*入库操作END*/
			$thiscount++;	  
		}
		F('totalcoll',$totalcoll);
		$result_data['p']			= $p;
		$result_data['msg']			= $msg;
		$result_data['coll']		= $coll;
		$result_data['totalcoll']	= $totalcoll;
		$result_data['totalnum']	= $totalnum;
		$result_data['thiscount']	= $thiscount;
		$result_data['times']		= lefttime(time()-$robots_time);
		return $result_data;
	}
 
	private function _ajax_ftx_publish_insert($item) {
        $result = D('items')->ajax_ftx_publish($item);
        return $result;
    }

    /**
     * 获取商品列表
     * 返回商品列表和总数
     */
    private function _get_list($map, $p) {
        $tb_top = $this->_get_tb_top();
        $req = $tb_top->load_api('TbkItemGetRequest');
        $req->setFields('num_iid,title,nick,pict_url,volume,reserve_price,zk_final_price,user_type,item_url,seller_id');
        $req->setPageSize('40');
		$req->setPageNo($p);
        $map['keyword'] && $req->setQ($map['keyword']); //关键词
        $map['cid'] && $req->setCat($map['cid']); //分类
		if($map['start_price'] && $map['end_price']){
			$req->setStartPrice(intval($map['start_price']));
			$req->setEndPrice(intval($map['end_price']));
		}
		$map['start_commissionRate'] && $req->setStartTkRate($map['start_commissionRate']);
        $map['end_commissionRate'] && $req->setEndTkRate($map['end_commissionRate']);
		if($map['shop_type'] == 'B'){
			$req->setIsTmall('true');
		}
        $map['sort'] && $req->setSort($map['sort']);
        $resp = $tb_top->execute($req);
        $count = $resp->total_results;
		$taobaoke_item_list = array();
		$resp_items = (array) $resp->results;
		if(!$resp_items){
			return array(
				'count' => intval($count),
				'item_list' => $taobaoke_item_list,
			);
			$resul = (array)$resp;
			$this->ajaxReturn(0, $resul['msg']);
		}
		$iids = '';
        foreach ($resp_items['n_tbk_item']  as $val) {
            $val = (array) $val;
			$iid = (string)$val['num_iid'];
			$val['cate_id']=$map['cate_id'];
            $val['item_type'] = 4;
			$val['nick']			= $val['nick'];
			$val['pic_url']		= $val['pict_url'];
            $val['click_url']      = $this->getqueqiao($iid);
			$val['price']			= $val['reserve_price'];
			$val['coupon_price']	= $val['zk_final_price'];
			$val['coupon_rate']	= round( $val['coupon_price']/$val['price'],4) * 10000;
			$val['sellerId']		= $val['seller_id'];
			$val['volume']			= $val['volume']; 
			$val['ems']				= 1;
			if($val['user_type']){
				$val['shop_type']	= 'B';
			}else{
				$val['shop_type']	= 'C';
			}
			unset($val['reserve_price']);
			unset($val['zk_final_price']);
			unset($val['seller_id']);
			unset($val['pict_url']);
			unset($val['user_type']);
            $taobaoke_item_list[$iid] = $val;
        }
        return array(
            'count' => intval($count),
            'item_list' => $taobaoke_item_list,
        );
    }

	public function item_check(){
		$this->display();
	}
	public function item_checks(){
		$this->item_mod = m( "items" );
		$p = I('p',1,'intval');
        $where['coupon_end_time'] = array("elt",time());
		$where['pass'] = "1";
		$page_size = 40;
		if (false === $itemcheckdata = F('itemcheck')) {
			$itemcheckdata['this_good'] = 0;
			$itemcheckdata['this_bad'] = 0;
			$itemcheckdata['that_good'] = 0;
			$itemcheckdata['that_bad'] = 0;
			$itemcheckdata['total'] = 0;
			$itemcheckdata['not_total'] = 0;
			F('itemcheck', $itemcheckdata);
		}
		$itemcheckdata['this_good'] = 0;
		$itemcheckdata['this_bad'] = 0;

		$CheckItemCount = $this->item_mod->where($where)->count("id");
		$itemcheckdata['not_total'] = $CheckItemCount ;

        $order = "add_time asc ";
        $items_list = $this->item_mod->field('num_iid')->where($where)->order( $order )->limit(0,$page_size)->select();
		$arr_iids = array();
        if($items_list){
            foreach($items_list as $key =>$val){
				$item = array();
				$arr_iids[] = $val['num_iid'];
				$item['status']			= 'sellout';
				$item['num_iid']		= $val['num_iid'];
				$taobaoke_item_list[$val['num_iid']] = $item;
			}
		}else{
			$this->ajaxReturn(0, '更新完成，谢谢！');
		}
		$num_iids = implode(',',$arr_iids);
		if(!$this->_tbconfig['app_key']){$this->ajaxReturn(0, '请设置淘宝appkey');}
		$tb_tops = $this->_get_tb_top();
		$reqs = $tb_tops->load_api('TbkItemInfoGetRequest');
		$reqs->setFields('num_iid,title,nick,pict_url,volume,reserve_price,zk_final_price,user_type,item_url,seller_id');
		$reqs->setNumIids($num_iids);
		$resps = $tb_tops->execute($reqs);
		$items = (array)$resps->results;
 
		foreach ($items['n_tbk_item']  as $val) {
				$val					= (array) $val;
				$iid					= (string)$val['num_iid'];
				$item					= array();
				$item['num_iid']			= $val['num_iid'];
				$item['nick']			= $val['nick'];
				$item['pic_url']		= $val['pict_url'];
				$item['price']			= $val['reserve_price'];
				$item['coupon_price']	= $val['zk_final_price'];
				$item['coupon_rate']	= round( $item['coupon_price']/$item['price'],4) * 10000;
				$item['sellerId']		= $val['seller_id'];
				$item['status']			= 'underway';
				$item['volume']			= $val['volume']; 
				if($val['user_type']){
					$item['shop_type']	= 'B';
				}else{
					$item['shop_type']	= 'C';
				}
				$taobaoke_item_list[$iid] = $item;
		}

        foreach ($taobaoke_item_list as $val) {
			$coupon_add_time = C('ftx_coupon_add_time');
			if($coupon_add_time){
				$times	=	(int)(time()+$coupon_add_time*3600);
			}else{
				$times	=	(int)(time()+72*86400);
			}
			if($val['status'] =='underway' && $val['coupon_price'] && $val['price']){
				$val['coupon_start_time']	= strtotime(date('Y-m-d H:00:00',time()));
				$val['coupon_end_time']		= $times;
				$itemcheckdata['this_good'] = $itemcheckdata['this_good']+1;
				$itemcheckdata['that_good'] = $itemcheckdata['that_good']+1;
			}else{
				$val['pass'] = 0;
				$itemcheckdata['this_bad']	= $itemcheckdata['this_bad']+1;
				$itemcheckdata['that_bad']	= $itemcheckdata['that_bad']+1;
				F('bad_item',$val);
			}
			$itemcheckdata['total'] = $itemcheckdata['total']+1;
			$this->item_mod->where(array('num_iid'=>(string)$val['num_iid']))->save($val);
        }
		F('itemcheck', $itemcheckdata);
		$msg['title'] = '商品检测';
		$msg['p'] = $p+1;

		$this->assign('item', $itemcheckdata);
		$resp = $this->fetch('ajax_itemcheck');
		$this->ajaxReturn(1, $msg, $resp);
    }

	public function ajax_get_tbcats() {
        $cid = $this->_get('cid', 'intval', 0);
        $item_cate = $this->_get_tbcats($cid);
        if ($item_cate) {
            $this->ajaxReturn(1, '', $item_cate);
        } else {
            $this->ajaxReturn(0);
        }
    }

	private function _get_tbcats($cid = 0) {
	    return [];
//		$tb_top = $this->_get_ftx_top();
//        $req = $tb_top->load_api('TaobaoItemcatsGetRequest');
//        $req->setFields("cid,parent_cid,name,is_parent");
//        $req->setParentCid($cid);
//		$req->setTime(date("Y-m-d"));
//        $resp = $tb_top->execute($req);
//        $res_cats = (array) $resp->item_cats;
//        $item_cate = array();
//        foreach ($res_cats['item_cat'] as $val) {
//            $val = (array) $val;
//            $item_cate[] = $val;
//        }
//        return $item_cate;
    }

    private function _get_tb_top() {
		vendor('taobao.TopClient');
		vendor('taobao.RequestCheckUtil');
		vendor('taobao.ResultSet');
		$tb_top = new TopClient;
		$tb_top->appkey = $this->_tbconfig['app_key'];
		$tb_top->secretKey = $this->_tbconfig['app_secret'];
		return $tb_top;
    }


    public function getqueqiao($zym_95)
    {
        $zym_19 = C('ftx_cookie');
        if (!$zym_19){
            return $this->ajaxReturn(0, '请设置阿里妈妈cookie');
        }
        $zym_20 = array(' ','　','' . "\xa" . '','' . "\xd" . '','' . "\x9" . '');
        $zym_24 = array("","","","","");
        $zym_19 = str_replace($zym_20, $zym_24, $zym_19);
        $zym_23 =get_word($zym_19,'_tb_token_=',';');
        $zym_22 = get_word($zym_19,'t=',';');
        $zym_21 = get_word($zym_19,'cna=',';');
        $zym_13 = get_word($zym_19,'l=',';');
        $zym_12 = get_word($zym_19,'mm-guidance3',';');
        $zym_5 = get_word($zym_19,'_umdata=',';');
        $zym_4 = get_word($zym_19,'cookie2=',';');
        $zym_3 = get_word($zym_19,'cookie32=',';');
        $zym_1 = get_word($zym_19,'cookie31=',';');
        $zym_2 = get_word($zym_19,'alimamapwag=',';');
        $zym_6 = get_word($zym_19,'login=',';');
        $zym_7 = get_word($zym_19,'alimamapw=',';');
        $zym_11 = 't='.$zym_22.';cna='.$zym_21.';l='.$zym_13.';mm-guidance3='.$zym_12.';_umdata='.$zym_5.';cookie2='.$zym_4.';_tb_token_='.$zym_23.';v=0;cookie32='.$zym_3.';cookie31='.$zym_1.';alimamapwag='.$zym_2.';login='.$zym_6.';alimamapw='.$zym_7;
        $zym_10 =microtime(true)*1000;
        $zym_10 = explode('.', $zym_10);
        $zym_31 = get_client_ip();
        $zym_30 = '19_'.$zym_31.'_366_1468693605455';
        $zym_29 = C('ftx_youpid');
        if (!$zym_29){
            return $this->ajaxReturn(0, '请设置淘客pid');
        }
        $zym_27 = explode('_',$zym_29);
        $zym_28 = $zym_27[2];
        $zym_32 = $zym_27[3];
        $zym_33 = 'http://pub.alimama.com/common/code/getAuctionCode.json?auctionid='.$zym_95.'&adzoneid='.$zym_32.'&siteid='.$zym_28.'&scenes=3&channel=tk_qqhd&t='.$zym_10[0].'&_tb_token_='.$zym_23.'&pvid='.$zym_30;
        $zym_37 = curl_init();
        curl_setopt($zym_37, CURLOPT_URL, $zym_33);
        curl_setopt($zym_37, CURLOPT_REFERER, 'http://pub.alimama.com/promo/item/channel/index.htm?channel=qqhd');
        curl_setopt($zym_37, CURLOPT_HTTPHEADER, array( 'Cookie:{'.$zym_11.'}', ));
        curl_setopt($zym_37, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($zym_37, CURLOPT_FOLLOWLOCATION, 1);
        $zym_36 = curl_exec($zym_37);
        curl_close($zym_37);
        $zym_35 = json_decode($zym_36,true);
        if($zym_35)
        {
            $zym_34 = $zym_35['data']['shortLinkUrl'];
        }
        return $zym_34;
    }

}