<?php
 class BackendAction extends TopAction{
    protected $_name = '';
    protected $menuid = 0;
    public function _initialize(){
        parent :: _initialize();
        $this -> _name = $this -> getActionName();
        $this -> check_priv();
        $this -> menuid = $this -> _request('menuid', 'trim', 0);
        if ($this -> menuid){
            $zym_26 = D('menu') -> sub_menu($this -> menuid, $this -> big_menu);
            $zym_24 = '';
            foreach ($zym_26 as $zym_23 => $zym_20){
                $zym_26[$zym_23]['class'] = '';
                if (MODULE_NAME == $zym_20['module_name'] && ACTION_NAME == $zym_20['action_name'] && strpos(__SELF__, $zym_20['data'])){
                    $zym_26[$zym_23]['class'] = $zym_24 = 'on';
                }
            }
            if (empty($zym_24)){
                foreach ($zym_26 as $zym_23 => $zym_20){
                    if (MODULE_NAME == $zym_20['module_name'] && ACTION_NAME == $zym_20['action_name']){
                        $zym_26[$zym_23]['class'] = 'on';
                        break;
                    }
                }
            }
            $this -> assign('sub_menu', $zym_26);
        }
        $this -> assign('menuid', $this -> menuid);
    }
    public function index(){
        $zym_21 = $this -> _search();
        $zym_22 = D($this -> _name);
        !empty($zym_22) && $this -> _list($zym_22, $zym_21);
        $this -> display();
    }
    public function add(){
        $zym_22 = D($this -> _name);
        if (IS_POST){
            if (false === $zym_28 = $zym_22 -> create()){
                IS_AJAX && $this -> ajaxReturn(0, $zym_22 -> getError());
                $this -> error($zym_22 -> getError());
            }
            if (method_exists($this, '_before_insert')){
                $zym_28 = $this -> _before_insert($zym_28);
            }
            if($zym_22 -> add($zym_28)){
                if(method_exists($this, '_after_insert')){
                    $zym_35 = $zym_22 -> getLastInsID();
                    $this -> _after_insert($zym_35);
                }
                IS_AJAX && $this -> ajaxReturn(1, L('operation_success'), '', 'add');
                $this -> success(L('operation_success'));
            }else{
                IS_AJAX && $this -> ajaxReturn(0, L('operation_failure'));
                $this -> error(L('operation_failure'));
            }
        }else{
            $this -> assign('open_validator', true);
            if (IS_AJAX){
                $zym_33 = $this -> fetch();
                $this -> ajaxReturn(1, '', $zym_33);
            }else{
                $this -> display();
            }
        }
    }
    public function edit(){
        $zym_22 = D($this -> _name);
        $zym_34 = $zym_22 -> getPk();
        if (IS_POST){
            if (false === $zym_28 = $zym_22 -> create()){
                IS_AJAX && $this -> ajaxReturn(0, $zym_22 -> getError());
                $this -> error($zym_22 -> getError());
            }
            if (method_exists($this, '_before_update')){
                $zym_28 = $this -> _before_update($zym_28);
            }
            if (false !== $zym_22 -> save($zym_28)){
                if(method_exists($this, '_after_update')){
                    $zym_35 = $zym_28['id'];
                    $this -> _after_update($zym_35);
                }
                IS_AJAX && $this -> ajaxReturn(1, L('operation_success'), '', 'edit');
                $this -> success(L('operation_success'));
            }else{
                IS_AJAX && $this -> ajaxReturn(0, L('operation_failure'));
                $this -> error(L('operation_failure'));
            }
        }else{
            $zym_35 = $this -> _get($zym_34, 'intval');
            $zym_32 = $zym_22 -> find($zym_35);
            $this -> assign('info', $zym_32);
            $this -> assign('open_validator', true);
            if (IS_AJAX){
                $zym_33 = $this -> fetch();
                $this -> ajaxReturn(1, '', $zym_33);
            }else{
                $this -> display();
            }
        }
    }
    public function ajax_edit(){
        $zym_22 = D($this -> _name);
        $zym_34 = $zym_22 -> getPk();
        $zym_35 = $this -> _get($zym_34, 'intval');
        $zym_31 = $this -> _get('field', 'trim');
        $zym_20 = $this -> _get('val', 'trim');
        $zym_22 -> where(array($zym_34 => $zym_35)) -> setField($zym_31, $zym_20);
        $this -> ajaxReturn(1);
    }
    public function delete(){
        $zym_22 = D($this -> _name);
        $zym_34 = $zym_22 -> getPk();
        $zym_29 = trim($this -> _request($zym_34), ',');
        if ($zym_29){
            if (false !== $zym_22 -> delete($zym_29)){
                IS_AJAX && $this -> ajaxReturn(1, L('operation_success'));
                $this -> success(L('operation_success'));
            }else{
                IS_AJAX && $this -> ajaxReturn(0, L('operation_failure'));
                $this -> error(L('operation_failure'));
            }
        }else{
            IS_AJAX && $this -> ajaxReturn(0, L('illegal_parameters'));
            $this -> error(L('illegal_parameters'));
        }
    }
    protected function _search(){
        $zym_22 = D($this -> _name);
        $zym_21 = array();
        foreach ($zym_22 -> getDbFields() as $zym_23 => $zym_20){
            if (substr($zym_23, 0, 1) == '_'){
                continue;
            }
            if ($this -> _request($zym_20)){
                $zym_21[$zym_20] = $this -> _request($zym_20);
            }
        }
        return $zym_21;
    }
    protected function _list($zym_30, $zym_21 = array(), $zym_19 = '', $zym_18 = '', $zym_6 = '*', $zym_7 = 100){
        $zym_8 = $zym_30 -> getPk();
        if ($this -> _request('sort', 'trim')){
            $zym_5 = $this -> _request('sort', 'trim');
        }else if (!empty($zym_19)){
            $zym_5 = $zym_19;
        }else if ($this -> sort){
            $zym_5 = $this -> sort;
        }else{
            $zym_5 = $zym_8;
        }
        if ($this -> _request('order', 'trim')){
            $zym_4 = $this -> _request('order', 'trim');
        }else if (!empty($zym_18)){
            $zym_4 = $zym_18;
        }else if ($this -> order){
            $zym_4 = $this -> order;
        }else{
            $zym_4 = 'DESC';
        }
        if ($zym_7){
            $zym_1 = $zym_30 -> where($zym_21) -> count($zym_8);
            $zym_2 = new Page($zym_1, $zym_7);
        }
        $zym_3 = $zym_30 -> field($zym_6) -> where($zym_21) -> order($zym_5 . ' ' . $zym_4);
        $this -> list_relation && $zym_3 -> relation(true);
        if ($zym_7){
            $zym_3 -> limit($zym_2 -> firstRow . ',' . $zym_2 -> listRows);
            $zym_9 = $zym_2 -> show();
            $this -> assign('page', $zym_9);
        }
        $zym_10 = $zym_3 -> select();
        $this -> assign('list', $zym_10);
        $this -> assign('list_table', true);
    }
    public function check_priv(){
        if (MODULE_NAME == 'attachment'){
            return true;
        }
        if ((!isset($_SESSION['admin']) || !$_SESSION['admin']) && !in_array(ACTION_NAME, array('login', 'verify_code'))){
            $this -> redirect('index/login');
        }
        if($_SESSION['admin']['role_id'] == 1){
            return true;
        }
        if (in_array(MODULE_NAME, explode(',', 'index'))){
            return true;
        }
        $zym_16 = M('menu');
        $zym_17 = $zym_16 -> where(array('module_name' => MODULE_NAME, 'action_name' => ACTION_NAME)) -> getField('id');
        $zym_15 = D('admin_auth');
        $zym_14 = $zym_15 -> where(array('menu_id' => $zym_17, 'role_id' => $_SESSION['admin']['role_id'])) -> count();
        if (!$zym_14){
            $this -> error(L('_VALID_ACCESS_'));
        }
    }
    protected function update_config($zym_11, $zym_12 = ''){
        !is_file($zym_12) && $zym_12 = CONF_PATH . 'index/config.php';
        if (is_writable($zym_12)){
            $zym_13 = require $zym_12;
            $zym_13 = array_merge($zym_13, $zym_11);
            file_put_contents($zym_12, '<?php ' . "\xa" . 'return ' . stripslashes(var_export($zym_13, true)) . ';', LOCK_EX);
            @unlink(RUNTIME_FILE);
            return true;
        }else{
            return false;
        }
    }
}
?>