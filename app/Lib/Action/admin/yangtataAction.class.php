<?php
 vendor('Taobaotop.TopClient');
vendor('Taobaotop.RequestCheckUtil');
vendor('Taobaotop.ResultSet');
vendor('Taobaotop.request.TbkItemInfoGetRequest');
class yangtataAction extends BackendAction{
    public function _initialize(){
        parent :: _initialize();
        $this -> _cate_mod = D('items_cate');
        $zym_22 = M('items_site') -> where(array('code' => 'taobao')) -> getField('config');
        $this -> _tbconfig = unserialize($zym_22);
        if (!$this -> _tbconfig['app_key']){
            header('Content-type: text/html; charset=utf-8');
            die('<script language="javascript">alert("你还没有安装淘宝API接口");this.location.href="' . U('items_site/index', array('menu' => 135)) . '";</script>');
        }
    }
    public function index(){
        $this -> display();
    }
    public function setting(){
        if(IS_POST){
            $zym_20 = $this -> _post('cate_id', 'trim');
            $zym_19 = $this -> _post('fid', 'trim');
            $zym_17 = $this -> _post('eid', 'trim');
            $zym_18 = I('step', '1', 'intval');
            F('cktata_setting', array('cate_id' => $zym_20, 'step' => $zym_18, 'fid' => $zym_19, 'eid' => $zym_17,));
            $this -> ckyangtata();
        }
    }
    public function ckyangtata(){
        if (false === $zym_24 = F('cktata_setting')){
            $this -> ajaxReturn(0, L('illegal_parameters'));
        }
        if (false === $zym_27 = F('totalcoll')){
            $zym_27 = 0;
        }
        if (false === $zym_30 = F('nocoll')){
            $zym_30 = 0;
        }
        if (false === $zym_29 = F('okcoll')){
            $zym_29 = 0;
        }
        $zym_28 = $zym_24['cate_id'];
        $this -> item_mod = M('items');
        $zym_25 = $this -> _get('p', 'intval', 1);
        $zym_19 = $zym_24['fid'];
        $zym_17 = $zym_24['eid'];
        $zym_18 = $zym_24['step'];
        if($zym_19 && $zym_17){
            $zym_26['id'] = array(array('egt', $zym_19), array('elt', $zym_17));
        }
        if($zym_28){
            $zym_26['cate_id'] = $zym_28;
        }
        $zym_26['pass'] = '1';
        $zym_16 = $zym_18;
        $zym_15 = ($zym_25-1) * $zym_16;
        $zym_5 = $this -> item_mod -> where($zym_26) -> count('id');
        $zym_6 = 'id asc ';
        $zym_4 = $this -> item_mod -> field('num_iid') -> where($zym_26) -> order($zym_6) -> limit($zym_15, $zym_16) -> select();
        if($zym_5){
            foreach ($zym_4 as $zym_3 => $zym_1){
                $zym_2 = array();
                $zym_7 = $this -> getcheck($zym_1['num_iid']);
                if ($zym_7){
                    $zym_2['pass'] = 1;
                    $zym_2['status'] = 'underway';
                    $this -> item_mod -> where($zym_1) -> save($zym_2);
                    $zym_29++;
                }else{
                    $this -> item_mod -> where($zym_1) -> delete();
                    $zym_30++;
                }
                $zym_27++;
            }
        }else{
            $this -> ajaxReturn(0, '您设置的条件下没有商品可以检测');
        }
        F('totalcoll', $zym_27);
        F('okcoll', $zym_29);
        F('nocoll', $zym_30);
        if($zym_27 >= $zym_5){
            F('totalcoll', false);
            F('okcoll', false);
            F('nocoll', false);
            $this -> ajaxReturn(0, '检测完成，请刷新本页面查看未参与推广的商品列表，谢谢！');
        }
        $zym_8['title'] = '佣金检测';
        $this -> assign('CheckItemCount', $zym_5);
        $this -> assign('totalcoll', $zym_27);
        $this -> assign('okcoll', $zym_29);
        $this -> assign('nocoll', $zym_30);
        $zym_13 = $this -> fetch('info');
        $this -> ajaxReturn(1, $zym_8, $zym_13);
    }
    public function getcheck($zym_14){
        $zym_12 = new TopClient;
        $zym_12 -> appkey = $this -> _tbconfig['app_key'];
        $zym_12 -> secretKey = $this -> _tbconfig['app_secret'];
        $zym_11 = new TbkItemInfoGetRequest;
        $zym_11 -> setFields('title');
        $zym_11 -> setPlatform(1);
        $zym_11 -> setNumIids($zym_14);
        $zym_13 = $zym_12 -> execute($zym_11);
        $zym_9 = objtoarr($zym_13);
        $zym_10 = $zym_9['results']['n_tbk_item'][0]['title'];
        return $zym_10;
    }
}
?>