<?php
 class templateAction extends BackendAction{
    public function index(){
        $zym_10 = CONF_PATH . 'index/config.php';
        $zym_11 = CONF_PATH . 'm/config.php';
        $zym_13 = include $zym_10;
        $zym_8 = include $zym_11;
        if ($zym_7 = $this -> _get('dirname', 'trim')){
            $zym_13['DEFAULT_THEME'] = $zym_7;
            $zym_8['DEFAULT_THEME'] = $zym_7;
            file_put_contents($zym_10, '<?php ' . "\xa" . 'return ' . var_export($zym_13, true) . ';', LOCK_EX);
            file_put_contents($zym_11, '<?php ' . "\xa" . 'return ' . var_export($zym_8, true) . ';', LOCK_EX);
            $zym_2 = new Dir;
            is_dir(CACHE_PATH . 'index/') && $zym_2 -> delDir(CACHE_PATH . 'index/');
            is_dir(CACHE_PATH . 'm/') && $zym_2 -> delDir(CACHE_PATH . 'm/');
            @unlink(RUNTIME_FILE);
        }
        $zym_1 = TMPL_PATH . 'index/';
        $zym_3 = dir($zym_1);
        $zym_4 = array();
        while (false !== ($zym_6 = $zym_3 -> read())){
            if ($zym_6{0} == '.'){
                continue;
            }
            if (!is_file($zym_1 . $zym_6 . '/info.php')){
                continue;
            }
            $zym_5 = include_once($zym_1 . $zym_6 . '/info.php');
            $zym_5['preview'] = TMPL_PATH . 'index/' . $zym_6 . '/preview.gif';
            $zym_5['dirname'] = $zym_6;
            $zym_4[$zym_6] = $zym_5;
        }
        $this -> assign('template_list', $zym_4);
        $this -> assign('def_tpl', $zym_13['DEFAULT_THEME']);
        $this -> display();
    }
}
?>