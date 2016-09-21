<?php
 class dtkAction extends BackendAction{
    public function _initialize(){
        parent :: _initialize();
    }
    public function index(){
        $this -> display();
    }
    public function getitem(){
        $zym_46 = C('ftx_dtk');
        if(!$zym_46){
            $zym_48 = array('state' => 1, 'msg' => '请先在采集设置中填写大淘客API');
        }else{
            $zym_49 = 'http://www.dataoke.com/open/api/?type=www_quan&appkey=' . $zym_46;
            $zym_51 = new yangtata();
            $zym_51 -> fetch($zym_49);
            $zym_50 = $zym_51 -> results;
            $zym_50 = iconv('GBK', 'UTF-8//IGNORE', $zym_50);
            $zym_50 = preg_replace('/\s/', "", $zym_50);
            $zym_50 = str_replace('' . "\x1f" . '', '', $zym_50);
            $zym_45 = FTX_DATA_PATH . 'cookies/';
            if (!is_dir($zym_45)){
                mkdir($zym_45, 0777);
            }
            $zym_44 = $zym_45 . 'dataoke.txt';
            file_put_contents($zym_44, "");
            file_put_contents($zym_44, $zym_50, FILE_APPEND);
            $zym_39 = array('dtk_file' => FTX_DATA_PATH . 'cookies/dataoke.txt',);
            $zym_38 = file_get_contents($zym_39['dtk_file']);
            if($zym_38){
                $zym_48 = array('state' => 1, 'msg' => '获取成功');
            }else{
                $zym_48 = array('state' => 1, 'msg' => '获取失败,请检查data目录权限。');
            }
        }
        echo json_encode($zym_48);
    }
    public function setting(){
        if(IS_POST){
            $zym_37 = C('ftx_fz');
            $zym_40 = I('page', '', 'intval');
            if(!$zym_37){
                $this -> ajaxReturn(0, '请先在采集设置中绑定分类ID!');
            }
            F('dtk_setting', array('page' => $zym_40,));
            $this -> collect();
        }
    }
    public function collect(){
        if (false === $zym_41 = F('dtk_setting')){
            $this -> ajaxReturn(0, L('illegal_parameters'));
        }
        $zym_39 = array('dtk_file' => FTX_DATA_PATH . 'cookies/dataoke.txt',);
        $zym_40 = $zym_41['page'];
        $zym_43 = $this -> _get('p', 'intval', $zym_40);
        if($zym_43 == 1){
            $zym_42 = 0;
        }else{
            $zym_42 = F('totalcoll');
        }
        $zym_50 = file_get_contents($zym_39['dtk_file']);
        if(!$zym_50 && $zym_50 = '操作异常！'){
            $this -> ajaxReturn(0, '请先获取数据。');
        }else{
            $zym_52 = json_decode($zym_50, true);
            $zym_53 = $zym_52['data']['total_num'];
            $zym_64 = $zym_43 - 1;
            $zym_63 = $zym_64 * 1;
            $zym_65 = $zym_43 * 1;
            $zym_66 = 0;
            if($zym_53){
                for($zym_68 = $zym_63;$zym_68 < $zym_65;$zym_68++){
                    $zym_67 = $zym_52['data']['result'][$zym_68]['GoodsID'];
                    $zym_69 = $zym_52['data']['result'][$zym_68]['Title'];
                    $zym_62 = $zym_52['data']['result'][$zym_68]['Pic'];
                    $zym_56 = $zym_52['data']['result'][$zym_68]['Cid'];
                    $zym_55 = $zym_52['data']['result'][$zym_68]['Org_Price'];
                    $zym_54 = $zym_52['data']['result'][$zym_68]['Price'];
                    $zym_57 = $zym_52['data']['result'][$zym_68]['IsTmall'];
                    if($zym_57){
                        $zym_58 = 'B';
                    }else{
                        $zym_58 = 'C';
                    }
                    $zym_60 = $zym_52['data']['result'][$zym_68]['Sales_num'];
                    $zym_59 = $zym_52['data']['result'][$zym_68]['SellerID'];
                    $zym_36 = $zym_52['data']['result'][$zym_68]['Introduce'];
                    $zym_35 = $zym_52['data']['result'][$zym_68]['Quan_id'];
                    $zym_12 = $zym_52['data']['result'][$zym_68]['Quan_time'];
                    $zym_11 = $zym_52['data']['result'][$zym_68]['Quan_surplus'];
                    $zym_10 = $zym_52['data']['result'][$zym_68]['Quan_receive'];
                    $zym_13 = $zym_52['data']['result'][$zym_68]['Quan_price'];
                    $zym_14 = $zym_52['data']['result'][$zym_68]['Quan_condition'];
                    $zym_16 = $zym_52['data']['result'][$zym_68]['Quan_m_link'];
                    $zym_15 = $zym_52['data']['result'][$zym_68]['ali_click'];
                    if(strpos($zym_15, 'click')){
                        $zym_9 = $zym_15;
                    }
                    $zym_8 = 'http://taoquan.taobao.com/coupon/unify_apply.htm?sellerId=' . $zym_59 . '&activityId=' . $zym_35;
                    $zym_3 = 'http://h5.m.taobao.com/ump/coupon/detail/index.html?sellerId=' . $zym_59 . '&activityId=' . $zym_35;
                    $zym_2 = round(($zym_54 / $zym_55) * 10, 1);
                    $zym_1 = C('ftx_coupon_add_time');
                    if($zym_1){
                        $zym_4 = (int)(time() + $zym_1 * 3600);
                    }else{
                        $zym_4 = (int)(time() + 72 * 86400);
                    }
                    if($zym_56 == 1){
                        $zym_37 = C('ftx_fz');
                    }
                    if($zym_56 == 2){
                        $zym_37 = C('ftx_my');
                    }
                    if($zym_56 == 3){
                        $zym_37 = C('ftx_hzp');
                    }
                    if($zym_56 == 4){
                        $zym_37 = C('ftx_jj');
                    }
                    if($zym_56 == 5){
                        $zym_37 = C('ftx_xbps');
                    }
                    if($zym_56 == 6){
                        $zym_37 = C('ftx_ms');
                    }
                    if($zym_56 == 7){
                        $zym_37 = C('ftx_wtcp');
                    }
                    if($zym_56 == 8){
                        $zym_37 = C('ftx_smjd');
                    }
                    $zym_5 = d('items') -> get_tags_by_title($zym_69);
                    $zym_7 = implode(',', $zym_5);
                    $zym_6['item_type'] = 2;
                    $zym_6['q_sur'] = $zym_11;
                    $zym_6['q_has'] = $zym_10;
                    $zym_6['q_price'] = $zym_13;
                    $zym_6['q_time'] = $zym_12;
                    $zym_6['pc_url'] = $zym_8;
                    $zym_6['wap_url'] = $zym_3;
                    $zym_6['q_info'] = $zym_14;
                    $zym_6['shop_type'] = $zym_58;
                    $zym_6['tags'] = $zym_7;
                    $zym_6['price'] = $zym_55;
                    $zym_6['volume'] = $zym_60;
                    $zym_6['desc'] = getdesc($zym_67);
                    $zym_6['intro'] = $zym_36;
                    $zym_6['coupon_rate'] = $zym_2 * 1000;
                    $zym_6['sellerId'] = $zym_59;
                    $zym_6['title'] = $zym_69;
                    $zym_6['click_url'] = $zym_9;
                    $zym_6['num_iid'] = $zym_67;
                    $zym_6['pic_url'] = $zym_62;
                    $zym_6['coupon_price'] = $zym_54;
                    $zym_6['cate_id'] = $zym_37;
                    $zym_6['coupon_end_time'] = $zym_4;;
                    $zym_6['coupon_start_time'] = time();
                    if($zym_67 && $zym_37 && $zym_11){
                        $zym_17['item_list'][] = $zym_6;
                    }
                }
            }
            if($zym_43 > $zym_53){
                $this -> ajaxReturn(0, '已经采集完成！请返回，谢谢');
            }
            $zym_66 = 0;
            foreach ($zym_17['item_list'] as $zym_18 => $zym_30){
                $zym_29 = $this -> _ajax_tb_publish_insert($zym_30);
                if($zym_29 > 0){
                    $zym_66++;
                }
                $zym_42++;
            }
            F('totalcoll', $zym_42);
            $this -> assign('p', $zym_43);
            $this -> assign('totalnum', $zym_53);
            $this -> assign('totalcoll', $zym_42);
            $zym_28 = $this -> fetch('collect');
            $this -> ajaxReturn(1, '', $zym_28);
        }
    }
    private function _ajax_tb_publish_insert($zym_31){
        $zym_31['title'] = trim(strip_tags($zym_31['title']));
        $zym_17 = D('items') -> ajax_tb_publish($zym_31);
        return $zym_17;
    }
    public function getok(){
        $zym_28 = $this -> fetch('getok');
        $this -> ajaxReturn(1, '批量加入推广', $zym_28);
    }
    public function isget(){
        if(IS_POST){
            $zym_32 = I('fid', '', 'trim');
            $zym_34 = I('eid', '', 'trim');
            $zym_33 = C('ftx_dtkcookies');
            if(!$zym_33){
                $this -> ajaxReturn(0, '请在采集设置中设置大淘客cookies！');
            }
            if(!$zym_32 && !$zym_34){
                $this -> ajaxReturn(0, '请选设置大淘客优惠券商品ID起止范围！');
            }
            F('isget_setting', array('fid' => $zym_32, 'eid' => $zym_34, 'cookies' => $zym_33,));
            $this -> isgeting();
        }
    }
    public function isgeting(){
        if (false === $zym_41 = F('isget_setting')){
            $this -> ajaxReturn(0, L('illegal_parameters'));
        }
        $zym_33 = $zym_41['cookies'];
        $zym_32 = $zym_41['fid'];
        $zym_34 = $zym_41['eid'];
        $zym_27 = $zym_34 - $zym_32 + 1;
        $zym_43 = $this -> _get('p', 'intval', 1);
        $zym_26 = $zym_43-1 + $zym_32;
        if (false === $zym_21 = F('bed')){
            $zym_21 = 0;
        }
        if (false === $zym_20 = F('okis')){
            $zym_20 = 0;
        }
        $zym_19 = microtime(true) * 1000;
        $zym_19 = explode('.', $zym_19);
        $zym_49 = 'http://www.dataoke.com/ucenter/save.asp?act=add_quan&id=' . $zym_26 . '&_=' . $zym_19[0];
        $zym_22 = $this -> isgetok($zym_49, $zym_33);
        if($zym_22 == 'error'){
            $zym_21++;
        }else{
            if($zym_22 == 'is_in'){
                $zym_22 = $this -> isgetok($zym_49, $zym_33);
            }
            $zym_20++;
        }
        F('okis', $zym_20);
        F('bed', $zym_21);
        $zym_23['title'] = '批量添加推广';
        $this -> assign('num', $zym_27);
        $this -> assign('p', $zym_43);
        $this -> assign('okcoll', $zym_20);
        $this -> assign('nocoll', $zym_21);
        if($zym_43 > $zym_27){
            F('okis', NULL);
            F('bed', NULL);
            $this -> ajaxReturn(0, '已经获取完成！请返回，谢谢');
        }
        $zym_28 = $this -> fetch('info');
        $this -> ajaxReturn(1, $zym_23, $zym_28);
    }
    public function isgetok($zym_49, $zym_33){
        $zym_25 = curl_init();
        curl_setopt($zym_25, CURLOPT_URL, $zym_49);
        curl_setopt($zym_25, CURLOPT_HTTPHEADER, array('Cookie:{' . $zym_33 . '}',));
        curl_setopt($zym_25, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($zym_25, CURLOPT_FOLLOWLOCATION, 1);
        $zym_24 = curl_exec($zym_25);
        curl_close($zym_25);
        return $zym_24;
    }
}
?>