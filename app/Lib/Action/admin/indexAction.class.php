<?php
 class indexAction extends BackendAction{
    public function _initialize(){
        parent :: _initialize();
        $this -> _mod = D('menu');
    }
    public function index(){
        $zym_16 = $this -> _mod -> admin_menu(0);
        $this -> assign('top_menus', $zym_16);
        $zym_15 = array('username' => $_SESSION['admin']['username'], 'roleid' => $_SESSION['admin']['role_id'], 'rolename' => $_SESSION['admin']['role_name']);
        $this -> assign('my_admin', $zym_15);
        $this -> display();
    }
    public function panel(){
        $zym_14 = M('items_site') -> where(array('code' => 'ftxia')) -> getField('config');
        $zym_18 = unserialize($zym_14);
        $zym_19 = array();
        if (is_dir('./install')){
            $zym_19[] = array('type' => 'Error', 'content' => '您还没有删除 install 文件夹，出于安全的考虑，我们建议您删除 install 文件夹。',);
        }
        if (APP_DEBUG == true){
            $zym_19[] = array('type' => 'Error', 'content' => '您网站的 DEBUG 没有关闭，出于安全考虑，我们建议您关闭程序 DEBUG。',);
        }
        if (!function_exists('curl_getinfo')){
            $zym_19[] = array('type' => 'Error', 'content' => '系统不支持 CURL ,将无法采集商品数据。',);
        }
        $this -> assign('message', $zym_19);
        $zym_24 = array('ftxia_version' => FTX_VERSION . '  ' . FTX_RELEASE . ' [<a href="http://aitiaotiao.com" class="blue" target="_blank">查看最新版本</a>]', 'server_domain' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]', 'server_os' => PHP_OS, 'web_server' => $_SERVER['SERVER_SOFTWARE'], 'php_version' => PHP_VERSION, 'mysql_version' => mysql_get_server_info(), 'upload_max_filesize' => ini_get('upload_max_filesize'), 'max_execution_time' => ini_get('max_execution_time') . '秒', 'safe_mode' => (boolean) ini_get('safe_mode') ? 'onCorrect' : 'onError', 'zlib' => function_exists('gzclose') ? 'onCorrect' : 'onError', 'curl' => function_exists('curl_getinfo') ? 'onCorrect' : 'onError', 'timezone' => function_exists('date_default_timezone_get') ? date_default_timezone_get() : L('no'));
        $this -> assign('system_info', $zym_24);
        $zym_22 = array('action' => 'not' . 'ice', 'sitename' => 'ftxia', 'siteurl' => $_SERVER['SERVER_NAME'], 'version' => FTX_VERSION, 'release' => FTX_RELEASE,);
        $zym_15 = array('username' => $_SESSION['admin']['username'], 'roleid' => $_SESSION['admin']['role_id'], 'rolename' => $_SESSION['admin']['role_name']);
        $this -> assign('my_admin', $zym_15);
        $this -> assign('time', date('Y-m-d H:i'));
        $this -> assign('ip', get_client_ip());
        $this -> display();
    }
    public function login(){
        if (IS_POST){
            $zym_21 = $this -> _post('username', 'trim');
            $zym_20 = $this -> _post('password', 'trim');
            if(!$zym_21 || !$zym_20){
                $this -> error(L('input_empty'));
            }
            $zym_13 = $this -> _post('verify_code', 'trim');
            if(session('verify') != md5($zym_13)){
                $this -> error(L('verify_code_error'));
            }
            $zym_12 = M('admin') -> where(array('username' => $zym_21, 'status' => 1)) -> find();
            if (!$zym_12){
                $this -> error(L('admin_not_exist'));
            }
            if ($zym_12['password'] != md5($zym_20)){
                $this -> error(L('password_error'));
            }
            $zym_5 = M('admin_role') -> where(array('id' => $zym_12['role_id'])) -> find();
            session('admin', array('id' => $zym_12['id'], 'role_id' => $zym_12['role_id'], 'role_name' => $zym_5['name'], 'username' => $zym_12['username'],));
            M('admin') -> where(array('id' => $zym_12['id'])) -> save(array('last_time' => time(), 'last_ip' => get_client_ip()));
            $this -> success(L('login_success'), U('index/index'));
        }else{
            $this -> display();
        }
    }
    public function logout(){
        session('admin', null);
        $this -> success(L('logout_success'), U('index/login'));
        exit;
    }
    public function verify_code(){
        Image :: buildImageVerify(4, 1, 'png', '50', '24');
    }
    public function left(){
        $zym_4 = $this -> _request('menuid', 'intval');
        if ($zym_4){
            $zym_3 = $this -> _mod -> admin_menu($zym_4);
            foreach ($zym_3 as $zym_1 => $zym_2){
                $zym_3[$zym_1]['sub'] = $this -> _mod -> admin_menu($zym_2['id']);
            }
        }else{
            $zym_3[0] = array('id' => 0, 'name' => L('common_menu'));
            $zym_3[0]['sub'] = array();
            if ($zym_6 = $this -> _mod -> where(array('often' => 1)) -> select()){
                $zym_3[0]['sub'] = $zym_6;
            }
            array_unshift($zym_3[0]['sub'], array('id' => 0, 'name' => L('common_menu_set'), 'module_name' => 'index', 'action_name' => 'often_menu'));
        }
        $this -> assign('left_menu', $zym_3);
        $this -> display();
    }
    public function often(){
        if (isset($_POST['do'])){
            $zym_7 = isset($_POST['id']) && is_array($_POST['id']) ? $_POST['id'] : '';
            $this -> _mod -> where(array('ofen' => 1)) -> save(array('often' => 0));
            $zym_11 = implode(',', $zym_7);
            $this -> _mod -> where('id IN(' . $zym_11 . ')') -> save(array('often' => 1));
            $this -> success(L('operation_success'));
        }else{
            $zym_6 = $this -> _mod -> admin_menu(0);
            $zym_10 = array();
            foreach ($zym_6 as $zym_9){
                $zym_9['sub'] = $this -> _mod -> admin_menu($zym_9['id']);
                foreach ($zym_9['sub'] as $zym_1 => $zym_8){
                    $zym_9['sub'][$zym_1]['sub'] = $this -> _mod -> admin_menu($zym_8['id']);
                }
                $zym_10[] = $zym_9;
            }
            $this -> assign('list', $zym_10);
            $this -> display();
        }
    }
    public function map(){
        $zym_6 = $this -> _mod -> admin_menu(0);
        $zym_10 = array();
        foreach ($zym_6 as $zym_9){
            $zym_9['sub'] = $this -> _mod -> admin_menu($zym_9['id']);
            foreach ($zym_9['sub'] as $zym_1 => $zym_8){
                $zym_9['sub'][$zym_1]['sub'] = $this -> _mod -> admin_menu($zym_8['id']);
            }
            $zym_10[] = $zym_9;
        }
        $this -> assign('list', $zym_10);
        $this -> display();
    }
}
?>