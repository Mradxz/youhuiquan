<?php
 class itemsAction extends BackendAction{
    public function _initialize(){
        parent :: _initialize();
        $this -> _mod = D('items');
        $this -> _cate_mod = D('items_cate');
    }
    public function pass(){
        if (IS_POST){
            $zym_57['pass'] = $this -> _post('pass', 'intval');
            $zym_56 = $this -> _post('ids');
            $zym_59 = explode(',', $zym_56);
            $zym_60['pass'] = $zym_57['pass'];
            $this -> _mod -> where(array('id' => array('in', $zym_56))) -> save($zym_60);
            $this -> ajaxReturn(1, L('operation_success'), '', 'pass');
        }else{
            $zym_56 = trim($this -> _request('id'), ',');
            $this -> assign('ids', $zym_56);
            $zym_63 = $this -> fetch();
            $this -> ajaxReturn(1, '', $zym_63);
        }
    }
    public function move(){
        if (IS_POST){
            $zym_57['pid'] = $this -> _post('pid', 'intval');
            $zym_56 = $this -> _post('ids');
            $zym_59 = explode(',', $zym_56);
            $zym_60['cate_id'] = $zym_57['pid'];
            $this -> _mod -> where(array('id' => array('in', $zym_56))) -> save($zym_60);
            $this -> ajaxReturn(1, L('operation_success'), '', 'move');
        }else{
            $zym_56 = trim(I('id'), ',');
            $this -> assign('ids', $zym_56);
            $zym_63 = $this -> fetch();
            $this -> ajaxReturn(1, '', $zym_63);
        }
    }
    public function _before_index(){
        $zym_62 = $this -> _cate_mod -> field('id,name') -> select();
        $zym_61 = array();
        foreach ($zym_62 as $zym_55){
            $zym_61[$zym_55['id']] = $zym_55['name'];
        }
        $this -> assign('cate_list', $zym_61);
        $this -> sort = 'ordid ASC,';
        $this -> order = 'add_time DESC';
    }
    public function ajax_upload_img(){
        $zym_54 = $this -> _get('type', 'trim', 'img');
        if (!empty($_FILES[$zym_54]['name'])){
            $zym_48 = date('ym/d/');
            $zym_47 = $this -> _upload($_FILES[$zym_54], 'item/' . $zym_48);
            if ($zym_47['error']){
                $this -> ajaxReturn(0, $zym_47['info']);
            }else{
                $zym_46 = $zym_48 . $zym_47['info'][0]['savename'];
                $this -> ajaxReturn(1, L('operation_success'), $zym_46);
            }
        }else{
            $this -> ajaxReturn(0, L('illegal_parameters'));
        }
    }
    protected function _search(){
        $zym_45 = array();
        ($zym_49 = $this -> _request('time_start', 'trim')) && $zym_45['add_time'][] = array('egt', strtotime($zym_49));
        ($zym_50 = $this -> _request('time_end', 'trim')) && $zym_45['add_time'][] = array('elt', strtotime($zym_50) + (24 * 60 * 60-1));
        ($zym_53 = $this -> _request('price_min', 'trim')) && $zym_45['coupon_price'][] = array('egt', $zym_53);
        ($zym_52 = $this -> _request('price_max', 'trim')) && $zym_45['coupon_price'][] = array('elt', $zym_52);
        ($zym_51 = $this -> _request('num_iid', 'trim')) && $zym_45['num_iid'] = array('like', '%' . $zym_51 . '%');
        $zym_64 = $this -> _request('cate_id', 'intval');
        if ($zym_64){
            $zym_65 = $this -> _cate_mod -> get_child_ids($zym_64, true);
            $zym_45['cate_id'] = array('IN', $zym_65);
            $zym_79 = $this -> _cate_mod -> where(array('id' => $zym_64)) -> getField('spid');
            if($zym_79 == 0){
                $zym_79 = $zym_64;
            }else{
                $zym_79 .= $zym_64;
            }
        }
        $zym_45['pass'] = 1;
        ($zym_78 = $this -> _request('keyword', 'trim')) && $zym_45['title'] = array('like', '%' . $zym_78 . '%');
        $this -> assign('search', array('time_start' => $zym_49, 'time_end' => $zym_50, 'price_min' => $zym_53, 'price_max' => $zym_52, 'num_iid' => $zym_51, 'pass' => $zym_77, 'selected_ids' => $zym_79, 'cate_id' => $zym_64, 'keyword' => $zym_78,));
        return $zym_45;
    }
    private function __getItemsOrig(){
        $zym_80 = M('items_orig') -> field('id,type') -> select();
        $zym_47 = array();
        if(!is_array($zym_80)) $zym_80 = array();
        foreach($zym_80 as $zym_81){
            $zym_47[$zym_81['type']] = $zym_81['id'];
        }
        return $zym_47;
    }
    public function add(){
        if (IS_POST){
            if (FALSE === ($zym_57 = $this -> _mod -> create())){
                $this -> error($this -> _mod -> getError());
            }
            if (!trim($zym_57['cate_id'])){
                $this -> error('请选择商品分类');
            }
            if($this -> _mod -> where(array('num_iid' => $zym_57['num_iid'])) -> count()){
                $this -> error('商品已存在，请更换其他商品');
            }
            if(empty($zym_57['click_url'])){
                $zym_57['click_url'] = '';
            }
            $zym_57['desc'] = $_POST['desc'];
            if (!trim($zym_57['volume'])){
                $this -> error('请填写正确的销量');
            }
            $zym_57['coupon_start_time'] = strtotime($_POST['coupon_start_time']);
            $zym_57['coupon_end_time'] = strtotime($_POST['coupon_end_time']);
            $zym_85 = $this -> _mod -> add($zym_57);
            if (!$zym_85){
                $this -> error(l('operation_failure'));
            }
            $this -> success(l('operation_success'));
        }else{
            $zym_84 = m('items_orig') -> where(array('pass' => 1)) -> select();
            $this -> assign('orig_list', $zym_84);
            $this -> display();
        }
    }
    public function edit(){
        if (IS_POST){
            if (false === $zym_57 = $this -> _mod -> create()){
                $this -> error($this -> _mod -> getError());
            }
            if(!$zym_57['cate_id'] || !trim($zym_57['cate_id'])){
                $this -> error('请选择商品分类');
            }
            $zym_85 = $zym_57['id'];
            $zym_57['coupon_start_time'] = strtotime($zym_57['coupon_start_time']);
            $zym_57['coupon_end_time'] = strtotime($zym_57['coupon_end_time']);
            $this -> _mod -> where(array('id' => $zym_85)) -> save($zym_57);
            $this -> success(L('operation_success'));
        }else{
            $zym_82 = $this -> _get('id', 'intval');
            $zym_81 = $this -> _mod -> where(array('id' => $zym_82)) -> find();
            $zym_79 = $this -> _cate_mod -> where(array('id' => $zym_81['cate_id'])) -> getField('spid');
            if($zym_79 == 0){
                $zym_79 = $zym_81['cate_id'];
            }else{
                $zym_79 .= $zym_81['cate_id'];
            }
            $this -> assign('selected_ids', $zym_79);
            $this -> assign('info', $zym_81);
            $zym_83 = 'http://hws.m.taobao.com/cache/wdetail/5.0/?id=' . $zym_81['num_iid'];
            $zym_76 = curl_init();
            curl_setopt($zym_76, CURLOPT_URL, $zym_83);
            curl_setopt($zym_76, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($zym_76, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($zym_76, CURLOPT_MAXREDIRS, 2);
            $zym_69 = curl_exec($zym_76);
            curl_close($zym_76);
            $zym_68 = json_decode($zym_69, true);
            $zym_67 = $zym_68['data']['itemInfoModel']['picsPath'];
            $zym_66 = count($zym_67);
            for($zym_70 = 0;$zym_70 < $zym_66;$zym_70++){
                $zym_71 .= '<img width="100" id="a' . $zym_70 . '" src=' . $zym_67[$zym_70] . '>';
            }
            $this -> assign('plist', $zym_71);
            $zym_84 = M('items_orig') -> select();
            $this -> assign('orig_list', $zym_84);
            $this -> display();
        }
    }
    public function clear(){
        if(IS_POST){
            if ($_REQUEST['isok'] == '1'){
                $zym_74 = $this -> _post('action', 'trim');
                $zym_73 = '1=1';
                if('outtime' == $zym_74){
                    $zym_73 .= ' AND pass=1  AND coupon_end_time <' . time();
                }elseif('notpass' == $zym_74){
                    $zym_73 .= ' AND pass=0';
                }elseif('clear' == $zym_74){
                    $zym_72 = CONF_PATH . 'db.php';
                    $zym_44 = require $zym_72;
                    $zym_43 = 'TRUNCATE TABLE ' . $zym_44['DB_PREFIX'] . 'items ';
                    $zym_15 = M() -> execute($zym_43);
                    $this -> success(L('clear_success'));
                }
                $this -> _mod -> where($zym_73) -> delete();
                $this -> success(L('clear_success'));
            }else{
                $this -> success('确认是否要删除？', U('items/clear'));
            }
        }else{
            $this -> display();
        }
    }
    public function delete_search(){
        if (isset($_REQUEST['dosubmit'])){
            if ($_REQUEST['isok'] == '1'){
                $zym_73 = '1=1';
                $zym_78 = trim($_POST['keyword']);
                $zym_64 = trim($_POST['cate_id']);
                $zym_49 = trim($_POST['time_start']);
                $zym_50 = trim($_POST['time_end']);
                $zym_14 = trim($_POST['min_price']);
                $zym_13 = trim($_POST['max_price']);
                if ($zym_78 != ''){
                    $zym_73 .= ' AND title LIKE \'%' . $zym_78 . '%\'';
                }
                if ($zym_64 != '' && $zym_64 != 0){
                    $zym_73 .= ' AND cate_id=' . $zym_64;
                }
                if ($zym_49 != ''){
                    $zym_12 = strtotime($zym_49);
                    $zym_73 .= ' AND coupon_end_time>=\'' . $zym_12 . '\'';
                }
                if ($zym_50 != ''){
                    $zym_16 = strtotime($zym_50);
                    $zym_73 .= ' AND coupon_end_time<=\'' . $zym_16 . '\'';
                }
                if ($zym_14 != ''){
                    $zym_73 .= ' AND coupon_price>=' . $zym_14;
                }
                if ($zym_13 != ''){
                    $zym_73 .= ' AND coupon_price<=' . $zym_13;
                }
                $this -> _mod -> where($zym_73) -> delete();
                $this -> success('删除成功', U('items/clear'));
            }else{
                $this -> success('确认是否要删除？', U('items/clear'));
            }
        }else{
            $this -> display();
        }
    }
    public function key_addtime(){
        if(IS_POST){
            $zym_74 = $this -> _post('action', 'trim');
            $zym_73 = '1=1';
            $zym_73 .= ' AND pass=1  AND coupon_end_time <' . time();
            if('aday' == $zym_74){
                $zym_60['coupon_end_time'] = time() + 86400;
            }elseif('twday' == $zym_74){
                $zym_60['coupon_end_time'] = time() + 2 * 86400;
            }elseif('trday' == $zym_74){
                $zym_60['coupon_end_time'] = time() + 3 * 86400;
            }else{
                $zym_17 = $this -> _post('times', 'intval');
                !$zym_17 && $this -> error('请输入延时时间', U('items/key_addtime'));
                $zym_60['coupon_end_time'] = time() + $zym_17 * 3600;
            }
            if ($zym_60){
                $this -> _mod -> where($zym_73) -> save($zym_60);
                $this -> success('延时成功！');
            }else{
                $this -> error('错误', U('items/key_addtime'));
            }
        }else{
            $this -> display();
        }
    }
    public function outtime(){
        if(IS_POST){
            $zym_74 = $this -> _post('action', 'trim');
            $zym_73 = '  pass=1  AND coupon_end_time <' . time();
            $this -> _mod -> where($zym_73) -> select();
        }else{
            if ($this -> _request('sort', 'trim')){
                $zym_20 = $this -> _request('sort', 'trim');
            }else{
                $zym_20 = 'id';
            }
            if ($this -> _request('order', 'trim')){
                $zym_19 = $this -> _request('order', 'trim');
            }else{
                $zym_19 = 'DESC';
            }
            $zym_45['pass'] = 1 ;
            $zym_45['coupon_end_time'] = array('elt', time());
            $zym_18 = $this -> _mod -> where($zym_45) -> count('id');
            $zym_11 = new Page($zym_18, 20);
            $zym_10 = $this -> _mod -> where($zym_45) -> order('id DESC');
            $zym_10 -> order($zym_20 . ' ' . $zym_19);
            $zym_10 -> limit($zym_11 -> firstRow . ',' . $zym_11 -> listRows);
            $zym_4 = $zym_11 -> show();
            $this -> assign('page', $zym_4);
            $zym_3 = $zym_10 -> select();
            $this -> assign('list', $zym_3);
            $this -> assign('list_table', true);
            $this -> display();
        }
    }
    public function add_time(){
        $zym_2 = D($this -> _name);
        $zym_1 = $zym_2 -> getPk();
        $zym_56 = trim($this -> _request($zym_1), ',');
        $zym_60['id'] = array('in', $zym_56);
        $zym_60['coupon_end_time'] = time() + C('ftx_item_add_time') * 3600;
        if ($zym_60){
            if (false !== $zym_2 -> save($zym_60)){
                IS_AJAX && $this -> ajaxReturn(1, L('operation_success'));
            }else{
                IS_AJAX && $this -> ajaxReturn(0, L('operation_failure'));
            }
        }else{
            IS_AJAX && $this -> ajaxReturn(0, L('illegal_parameters'));
        }
    }
    public function ajax_gettags(){
        $zym_5 = $this -> _get('title', 'trim');
        $zym_6 = d('items') -> get_tags_by_title($zym_5);
        $zym_9 = implode(',', $zym_6);
        $this -> ajaxReturn(1, l('operation_success'), $zym_9);
    }
    public function ajaxGetItem(){
        if(!isset($_REQUEST['link'])) $this -> ajaxReturn(0, '未传入商品链接');
        $zym_8 = parse_url($_REQUEST['link']);
        $zym_7 = convertUrlQuery($zym_8['query']);
        $zym_21 = $zym_7['id'];
        if($this -> _mod -> where(array('num_iid' => $zym_21)) -> count()){
            $this -> ajaxReturn(0, '该商品已存在');
        }else{
            $zym_22 = getInfo($_REQUEST['link']);
            $zym_37 = (string)($zym_22['coupon_price'] / $zym_22['price']);
            $zym_37 = substr($zym_37, 0, 4);
            $zym_22['coupon_rate'] = $zym_37 * 10000;
            $this -> ajaxReturn(1, '', $zym_22);
        }
    }
    public function ajax_getquan(){
        $zym_36 = $this -> _get('qurl', 'trim');
        $zym_35 = parse_url($zym_36);
        $zym_34 = convertUrlQuery($zym_35['query']);
        $zym_38 = $zym_34['seller_id'];
        if(!$zym_38){
            $zym_38 = $zym_34['sellerId'];
        }
        $zym_39 = $zym_34['activity_id'];
        if(!$zym_39){
            $zym_39 = $zym_34['activityId'];
        }
        $zym_42 = 'http://shop.m.taobao.com/shop/coupon.htm?seller_id=' . $zym_38 . '&activity_id=' . $zym_39;
        $zym_41 = 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4';
        $zym_40 = http($zym_42, $zym_41);
        $zym_5 = get_word($zym_40, '<title>', '<\/title>');
        if($zym_5 == '手机淘宝'){
            $zym_76 = curl_init();
            curl_setopt($zym_76, CURLOPT_URL, $zym_42);
            curl_setopt($zym_76, CURLOPT_USERAGENT, $zym_41);
            curl_setopt($zym_76, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($zym_76, CURLOPT_FOLLOWLOCATION, 1);
            $zym_40 = curl_exec($zym_76);
            curl_close($zym_76);
        }
        $zym_5 = get_word($zym_40, '<title>', '<\/title>');
        $zym_33 = get_word($zym_40, '<div class="coupon-info">', '<\/div>');
        $zym_32 = get_word($zym_40, '<dt>', '<\/dt>');
        $zym_26 = $zym_5 . ' ' . $zym_32;
        $zym_26 = mb_convert_encoding($zym_26, 'GBK', 'UTF-8');
        $zym_26 = urlencode($zym_26);
        $zym_22 = array();
        $zym_22['q_sur'] = get_word($zym_33, '<span class="rest">', '<\/span>');
        $zym_22['q_has'] = get_word($zym_33, '<span class="count">', '<\/span>');
        $zym_22['q_price'] = get_word($zym_33, '<dt>', '元');
        $zym_25 = get_word($zym_33, '单笔满', '元');
        $zym_22['q_info'] = '单笔满' . $zym_25 . '元可用，每人限领1张';
        $zym_22['q_time'] = get_word($zym_33, '至', '<\/dd>');
        $zym_22['pc_url'] = 'http://taoquan.taobao.com/coupon/unify_apply.htm?sellerId=' . $zym_38 . '&activityId=' . $zym_39;
        $zym_22['wap_url'] = 'http://h5.m.taobao.com/ump/coupon/detail/index.html?sellerId=' . $zym_38 . '&activityId=' . $zym_39;
        if ($zym_33){
            $zym_22['item_type'] = 2;
            $this -> ajaxReturn(1, L('operation_success'), $zym_22);
        }
        $this -> ajaxReturn(0, L('operation_failure'));
    }
}
function getInfo($zym_36){
    $zym_35 = parse_url($zym_36);
    $zym_34 = convertUrlQuery($zym_35['query']);
    $zym_24['param'] = $zym_34;
    $zym_83 = 'http://hws.m.taobao.com/cache/wdetail/5.0/?id=' . $zym_34['id'];
    $zym_76 = curl_init();
    curl_setopt($zym_76, CURLOPT_URL, $zym_83);
    curl_setopt($zym_76, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($zym_76, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($zym_76, CURLOPT_MAXREDIRS, 2);
    $zym_23 = curl_exec($zym_76);
    curl_close($zym_76);
    if(!$zym_23){
        $zym_23 = file_get_contents($zym_83);
    }
    $zym_57 = getTaobaoShopInfo($zym_23);
    return $zym_57;
}
function getTaobaoShopInfo($zym_69){
    $zym_57 = json_decode($zym_69, true);
    $zym_22 = array();
    $zym_27 = json_decode($zym_57['data']['apiStack'][0]['value'], true);
    $zym_22['title'] = $zym_57['data']['itemInfoModel']['title'];
    $zym_22['num_iid'] = $zym_57['data']['itemInfoModel']['itemId'];
    $zym_67 = $zym_57['data']['itemInfoModel']['picsPath'];
    $zym_66 = count($zym_67);
    for($zym_70 = 0;$zym_70 < $zym_66;$zym_70++){
        $zym_71 .= '<img width="100" id="a' . $zym_70 . '" src=' . $zym_67[$zym_70] . '>';
    }
    $zym_22['piclist'] = $zym_71;
    $zym_6 = D('items') -> get_tags_by_title($zym_22['title']);
    $zym_9 = implode(',', $zym_6);
    $zym_22['tags'] = $zym_9;
    $zym_22['volume'] = $zym_27['data']['itemInfoModel']['totalSoldQuantity'];
    $zym_22['coupon_price'] = $zym_27['data']['itemInfoModel']['priceUnits'][0]['price'];
    if(substr_count($zym_22['coupon_price'], '-')){
        $zym_28 = explode('-', $zym_22['coupon_price']);
        $zym_22['coupon_price'] = min($zym_28[0], $zym_28[1]);
    }
    $zym_22['price'] = $zym_27['data']['itemInfoModel']['priceUnits'][1]['price'];
    if(substr_count($zym_22['price'], '-')){
        $zym_27 = explode('-', $zym_22['price']);
        $zym_22['price'] = min($zym_27[0], $zym_27[1]);
    }
    $zym_22['pic_url'] = $zym_57['data']['itemInfoModel']['picsPath'][0];
    $zym_22['pic_url'] = str_replace('_320x320.jpg', "", $zym_22['pic_url']);
    $zym_22['sellerId'] = $zym_57['data']['seller']['userNumId'];
    $zym_22['type'] = $zym_57['data']['seller']['type'];
    $zym_22['item_type'] = 1;
    $zym_22['desc'] = getdesc($zym_22['num_iid']);
    return $zym_22;
}
function convertUrlQuery($zym_31){
    $zym_30 = explode('&', $zym_31);
    $zym_29 = array();
    foreach ($zym_30 as $zym_34){
        $zym_81 = explode('=', $zym_34);
        $zym_29[$zym_81[0]] = $zym_81[1];
    }
    return $zym_29;
}
?>