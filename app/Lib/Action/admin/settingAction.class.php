<?php
 class settingAction extends BackendAction{
    public function _initialize(){
        parent :: _initialize();
        $this -> _mod = D('setting');
        $zym_9 = D('items_cate') -> cate_data_cache();
        $this -> assign('cate_data', $zym_9);
    }
    public function index(){
        $zym_10 = $this -> _get('type', 'trim', 'index');
        $zym_12 = C('ftx_index_cids');
        $this -> assign('index_cids', $zym_12);
        $this -> display($zym_10);
    }
    public function user(){
        $this -> display();
    }
    public function cache(){
        $this -> display();
    }
    public function edit(){
        $zym_7 = $this -> _post('setting', ',');
        foreach ($zym_7 as $zym_6 => $zym_2){
            $zym_2 = is_array($zym_2) ? serialize($zym_2) : $zym_2;
            if($this -> _mod -> where(array('name' => $zym_6)) -> count()){
                $this -> _mod -> where(array('name' => $zym_6)) -> save(array('data' => $zym_2));
            }else{
                $this -> _mod -> add(array('name' => $zym_6, 'data' => $zym_2));
            }
        }
        $zym_10 = $this -> _post('type', 'trim', 'index');
        $this -> success(L('operation_success'));
    }
    public function ajax_mail_test(){
        $zym_1 = $this -> _get('email', 'trim');
        !$zym_1 && $this -> ajaxReturn(0);
        $zym_3 = mailer :: get_instance();
        if ($zym_3 -> send($zym_1, '这是一封测试邮件', '您好，此邮件由优惠券程序系统发送，请勿回复，收到此邮件证明你的邮箱已配置正确，官方网站：http://aitiaotiao.com')){
            $this -> ajaxReturn(1);
        }else{
            $this -> ajaxReturn(0);
        }
    }
    public function ajax_upload(){
        if(!empty($_FILES['img']['name'])){
            $zym_4 = $this -> _upload($_FILES['img'], 'site/');
            if ($zym_4['error']){
                $this -> error($zym_4['info']);
            }else{
                $zym_5['img'] = $zym_4['info'][0]['savename'];
                $this -> ajaxReturn(1, L('operation_success'), C('ftx_attach_path') . 'site/' . $zym_5['img']);
            }
        }else{
            $this -> ajaxReturn(0, L('illegal_parameters'));
        }
    }
}
?>