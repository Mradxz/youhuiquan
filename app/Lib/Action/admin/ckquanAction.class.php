<?php
 class ckquanAction extends BackendAction{
    public function _initialize(){
        parent :: _initialize();
        $this -> _cate_mod = D('items_cate');
    }
    public function index(){
        $this -> display();
    }
    public function setting(){
        if(IS_POST){
            $zym_26 = $this -> _post('cate_id', 'trim');
            $zym_24 = I('page', '', 'intval');
            $zym_23 = 1;
            F('ckq_setting', array('cate_id' => $zym_26, 'page' => $zym_24, 'step' => $zym_23,));
            $this -> ckq();
        }
    }
    public function ckq(){
        if (false === $zym_20 = F('ckq_setting')){
            $this -> ajaxReturn(0, L('illegal_parameters'));
        }
        if (false === $zym_21 = F('totalcoll')){
            $zym_21 = 0;
        }
        if (false === $zym_22 = F('nocoll')){
            $zym_22 = 0;
        }
        if (false === $zym_27 = F('okcoll')){
            $zym_27 = 0;
        }
        $zym_28 = $zym_20['cate_id'];
        $zym_23 = $zym_20['step'];
        $this -> item_mod = M('items');
        $zym_24 = $zym_20['page'];
        $zym_34 = $this -> _get('p', 'intval', $zym_24);
        $zym_35['item_type'] = 2;
        if($zym_28){
            $zym_35['cate_id'] = $zym_28;
        }
        $zym_36 = $zym_23;
        $zym_33 = ($zym_34-1) * $zym_36;
        $zym_29 = $this -> item_mod -> where($zym_35) -> count('id');
        $zym_30 = 'id asc ';
        $zym_31 = $this -> item_mod -> field('num_iid,wap_url') -> where($zym_35) -> order($zym_30) -> limit($zym_33, $zym_36) -> select();
        if($zym_29){
            foreach ($zym_31 as $zym_19 => $zym_18){
                $zym_6 = parse_url($zym_18['wap_url']);
                $zym_7 = $this -> convertUrlQuery($zym_6['query']);
                $zym_8 = $zym_7['seller_id'];
                if(!$zym_8){
                    $zym_8 = $zym_7['sellerId'];
                }
                $zym_5 = $zym_7['activity_id'];
                if(!$zym_5){
                    $zym_5 = $zym_7['activityId'];
                }
                $zym_4 = 'http://shop.m.taobao.com/shop/coupon.htm?seller_id=' . $zym_8 . '&activity_id=' . $zym_5;
                $zym_1 = 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4';
                $zym_2 = http($zym_4, $zym_1);
                $zym_3 = get_word($zym_2, '<title>', '<\/title>');
                if($zym_3 == '手机淘宝' || !$zym_2){
                    $zym_9 = curl_init();
                    curl_setopt($zym_9, CURLOPT_URL, $zym_4);
                    curl_setopt($zym_9, CURLOPT_USERAGENT, $zym_1);
                    curl_setopt($zym_9, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($zym_9, CURLOPT_FOLLOWLOCATION, 1);
                    $zym_2 = curl_exec($zym_9);
                    curl_close($zym_9);
                }
                $zym_10 = get_word($zym_2, '<div class="coupon-info">', '<\/div>');
                if ($zym_10){
                    $zym_16 = array();
                    $zym_16['q_sur'] = get_word($zym_10, '<span class="rest">', '<\/span>');
                    $zym_16['q_has'] = get_word($zym_10, '<span class="count">', '<\/span>');
                    $zym_16['q_price'] = get_word($zym_10, '<dt>', '元');
                    $zym_16['q_time'] = get_word($zym_10, '至', '<\/dd>');
                    $this -> item_mod -> where($zym_18) -> save($zym_16);
                    $zym_27++;
                }else{
                    $this -> item_mod -> where($zym_18) -> delete();
                    $zym_22++;
                }
                $zym_21++;
            }
        }else{
            $this -> ajaxReturn(0, '您设置的条件下没有商品可以检测');
        }
        F('totalcoll', $zym_21);
        F('okcoll', $zym_27);
        F('nocoll', $zym_22);
        if($zym_21 >= $zym_29){
            F('totalcoll', NULL);
            F('okcoll', NULL);
            F('nocoll', NULL);
            $this -> ajaxReturn(0, '检测完成,谢谢！');
        }
        $zym_17['title'] = '优惠券检测';
        $this -> assign('p', $zym_34);
        $this -> assign('CheckItemCount', $zym_29);
        $this -> assign('totalcoll', $zym_21);
        $this -> assign('okcoll', $zym_27);
        $this -> assign('nocoll', $zym_22);
        $zym_15 = $this -> fetch('info');
        $this -> ajaxReturn(1, $zym_17, $zym_15);
    }
    public function convertUrlQuery($zym_14){
        $zym_11 = explode('&', $zym_14);
        $zym_12 = array();
        foreach ($zym_11 as $zym_7){
            $zym_13 = explode('=', $zym_7);
            $zym_12[$zym_13[0]] = $zym_13[1];
        }
        return $zym_12;
    }
}
?>