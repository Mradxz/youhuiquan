<?php vendor('Taobaotop.TopClient');
vendor('Taobaotop.RequestCheckUtil');
vendor('Taobaotop.ResultSet');
vendor('Taobaotop.request.TbkUatmFavoritesGetRequest');
vendor('Taobaotop.request.TbkUatmFavoritesItemGetRequest');
class quelistAction extends BackendAction{
    public function _initialize(){
        parent :: _initialize();
        $zym_32 = M('items_site') -> where(array('code' => 'taobao')) -> getField('config');
        $this -> _tbconfig = unserialize($zym_32);
    }
    public function index(){
        $zym_33 = I('p', 1 , 'intval');
        $zym_34 = I('type', '' , 'trim');
        $zym_30 = new TopClient;
        $zym_30 -> appkey = $this -> _tbconfig['app_key'];
        $zym_30 -> secretKey = $this -> _tbconfig['app_secret'];
        $zym_29 = new TbkUatmFavoritesGetRequest;
        $zym_29 -> setPageNo("$zym_33");
        $zym_29 -> setPageSize('20');
        $zym_29 -> setFields('favorites_title,favorites_id,type');
        if($zym_34){
            $zym_29 -> setType($zym_34);
        }else{
            $zym_29 -> setType('-1');
        }
        $zym_25 = $zym_30 -> execute($zym_29);
        $zym_26 = objtoarr($zym_25);
        $zym_27 = $zym_26['total_results'];
        $zym_28 = count($zym_26['results']['tbk_favorites']);
        if($zym_28){
            $zym_36 = array();
            for($zym_41 = 0;$zym_41 < $zym_28;$zym_41++){
                $zym_36['id'] = $zym_41 + 1;
                $zym_36['title'] = $zym_26['results']['tbk_favorites'][$zym_41]['favorites_title'];
                $zym_36['eventId'] = $zym_26['results']['tbk_favorites'][$zym_41]['favorites_id'];
                $zym_36['type'] = $zym_26['results']['tbk_favorites'][$zym_41]['type'];
                $zym_43['item_list'][] = $zym_36;
            }
        }
        $zym_45 = $zym_43['item_list'];
        foreach ($zym_45 as $zym_46 => $zym_44){
            $zym_42[$zym_46] = $zym_44;
            $zym_42[$zym_46]['id'] = $zym_44['id'] + ($zym_33-1) * $zym_28;
            if($zym_44['type'] == 1){
                $zym_42[$zym_46]['type'] = '普通选品';
            }
            if($zym_44['type'] == 2){
                $zym_42[$zym_46]['type'] = '高佣选品';
            }
        }
        $zym_37 = new Page($zym_27, 20);
        $this -> assign('page', $zym_37 -> jshow());
        $this -> assign('list', $zym_42);
        $this -> assign('so', array('type' => $zym_34,));
        $this -> assign('p', $zym_33);
        $this -> display();
    }
    public function caiji(){
        $zym_38 = I('id', '', 'trim');
        $this -> assign('eventId', $zym_38);
        $zym_25 = $this -> fetch('caiji');
        $this -> ajaxReturn(1, '设置分类', $zym_25);
    }
    public function setting(){
        if(IS_POST){
            $zym_39 = $this -> _post('cate_id', 'trim');
            $zym_38 = I('eventId', '', 'trim');
            if(!$zym_39){
                $this -> ajaxReturn(0, '入库分类必须选择！');
            }
            F('ku_setting', array('cate_id' => $zym_39, 'eventId' => $zym_38,));
            $this -> getitem();
        }
    }
    public function getitem(){
        if (false === $zym_40 = F('ku_setting')){
            $this -> ajaxReturn(0, L('illegal_parameters'));
        }
        $zym_33 = I('p', 1 , 'intval');
        $zym_24 = 'abc123';
        $zym_23 = $zym_40['eventId'];
        $zym_39 = $zym_40['cate_id'];
        $zym_7 = C('ftx_youpid');
        $zym_8 = explode('_', $zym_7);
        $zym_9 = $zym_8[3];
        if(!$zym_9){
            $this -> ajaxReturn(0, '请在采集设置中按教程设置高佣PID');
        }
        $zym_30 = new TopClient;
        $zym_30 -> appkey = $this -> _tbconfig['app_key'];
        $zym_30 -> secretKey = $this -> _tbconfig['app_secret'];
        $zym_29 = new TbkUatmFavoritesItemGetRequest;
        $zym_29 -> setPlatform('1');
        $zym_29 -> setPageSize('20');
        $zym_29 -> setAdzoneId($zym_9);
        $zym_24 && $zym_29 -> setUnid($zym_24);
        $zym_29 -> setFavoritesId($zym_23);
        $zym_29 -> setPageNo("$zym_33");
        $zym_29 -> setFields('num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,seller_id,volume,event_start_time,event_end_time,status,type,click_url');
        $zym_25 = $zym_30 -> execute($zym_29);
        $zym_26 = objtoarr($zym_25);
        $zym_10 = $zym_26['total_results'];
        if($zym_10){
            for($zym_41 = 0;$zym_41 < 20;$zym_41++){
                $zym_36 = array();
                $zym_36['title'] = $zym_26['results']['uatm_tbk_item'][$zym_41]['title'];
                $zym_36['num_iid'] = $zym_26['results']['uatm_tbk_item'][$zym_41]['num_iid'];
                $zym_36['pic_url'] = $zym_26['results']['uatm_tbk_item'][$zym_41]['pict_url'];
                $zym_36['price'] = $zym_26['results']['uatm_tbk_item'][$zym_41]['reserve_price'];
                $zym_36['coupon_price'] = $zym_26['results']['uatm_tbk_item'][$zym_41]['zk_final_price'];
                $zym_36['click_url'] = $zym_26['results']['uatm_tbk_item'][$zym_41]['click_url'];
                $zym_36['sellerId'] = $zym_26['results']['uatm_tbk_item'][$zym_41]['seller_id'];
                $zym_36['volume'] = $zym_26['results']['uatm_tbk_item'][$zym_41]['volume'];
                $zym_6 = $zym_26['results']['uatm_tbk_item'][$zym_41]['status'];
                $zym_5 = $zym_26['results']['uatm_tbk_item'][$zym_41]['user_type'];
                if($zym_5){
                    $zym_36['shop_type'] = 'B';
                }else{
                    $zym_36['shop_type'] = 'C';
                }
                $zym_1 = round(($zym_36['coupon_price'] / $zym_36['price']) * 10, 1);
                $zym_2 = $zym_26['results']['uatm_tbk_item'][$zym_41]['small_images'];
                $zym_34 = $zym_26['results']['uatm_tbk_item'][$zym_41]['type'];
                if($zym_2){
                    foreach($zym_2 as $zym_46 => $zym_3){
                        $zym_4 = implode('', $zym_3);
                        foreach($zym_3 as $zym_11){
                            $zym_12 = '<img class="lazy" src=' . $zym_11 . '></br>';
                            $zym_4 = str_replace($zym_11, $zym_12, $zym_4);
                        }
                    }
                }
                if($zym_34 == 1){
                    $zym_19 = C('ftx_coupon_add_time');
                    if($zym_19){
                        $zym_20 = (int)(time() + $zym_19 * 3600);
                    }else{
                        $zym_20 = (int)(time() + 72 * 86400);
                    }
                    $zym_21 = time();
                    $zym_22 = $zym_20;
                }
                if($zym_34 == 2){
                    $zym_21 = strtotime($zym_26['results']['uatm_tbk_item'][$zym_41]['event_start_time']);
                    $zym_22 = strtotime($zym_26['results']['uatm_tbk_item'][$zym_41]['event_end_time']);
                }
                $zym_18 = d('items') -> get_tags_by_title($zym_36['title']);
                $zym_17 = implode(',', $zym_18);
                $zym_13['title'] = $zym_36['title'];
                $zym_13['shop_type'] = $zym_36['shop_type'];
                $zym_13['item_type'] = 3;
                $zym_13['tags'] = $zym_17;
                $zym_13['num_iid'] = $zym_36['num_iid'];
                $zym_13['click_url'] = $zym_36['click_url'];
                $zym_13['pic_url'] = $zym_36['pic_url'];
                $zym_13['price'] = $zym_36['price'];
                $zym_13['coupon_price'] = $zym_36['coupon_price'];
                $zym_13['coupon_rate'] = $zym_1 * 1000;
                $zym_13['volume'] = $zym_36['volume'];
                $zym_13['cate_id'] = $zym_39;
                $zym_13['desc'] = $zym_4;
                $zym_13['sellerId'] = $zym_36['sellerId'];
                $zym_13['coupon_end_time'] = $zym_22;
                $zym_13['coupon_start_time'] = $zym_21;
                if($zym_36['click_url'] && $zym_6){
                    $zym_43['item_list'][] = $zym_13;
                }
            }
        }
        $zym_14 = 0;
        foreach ($zym_43['item_list'] as $zym_46 => $zym_3){
            $zym_15 = $this -> _ajax_que_publish_insert($zym_3);
            if($zym_15 > 0){
                $zym_14++;
            }
        }
        $zym_16 = ceil($zym_10 / 20);
        if($zym_33 > $zym_16){
            F('ku_setting', false);
            $this -> ajaxReturn(0, '已经采集完成！请返回，谢谢');
        }
        $this -> assign('p', $zym_33);
        $this -> assign('coll', $zym_14);
        $this -> assign('totalnum', $zym_10);
        $zym_25 = $this -> fetch('collect');
        $this -> ajaxReturn(1, '批量采集中', $zym_25);
    }
    private function _ajax_que_publish_insert($zym_36){
        $zym_43 = D('items') -> ajax_que_publish($zym_36);
        return $zym_43;
    }
}
?>