<?php
 class cacheAction extends BackendAction{
    public function _initialize(){
        parent :: _initialize();
    }
    public function index(){
        $this -> display();
    }
    public function clear(){
        $zym_1 = $this -> _get('type', 'trim');
        $zym_4 = new Dir;
        switch ($zym_1){
        case 'field': is_dir(DATA_PATH . '_fields/') && $zym_4 -> del(DATA_PATH . '_fields/');
            break;
        case 'tpl': is_dir(CACHE_PATH) && $zym_4 -> delDir(CACHE_PATH);
            break;
        case 'data': is_dir(DATA_PATH) && $zym_4 -> del(DATA_PATH);
            is_dir(TEMP_PATH) && $zym_4 -> delDir(TEMP_PATH);
            break;
        case 'runtime': @unlink(RUNTIME_FILE);
            break;
        case 'logs': is_dir(LOG_PATH) && $zym_4 -> delDir(LOG_PATH);
            break;
        case 'taobaoapi': is_dir(FTX_DATA_PATH . 'taobaoapi/') && $zym_4 -> delDir(FTX_DATA_PATH . 'taobaoapi/');
            break;
        }
        $this -> ajaxReturn(1);
    }
    public function qclear(){
        $zym_4 = new Dir;
        is_dir(DATA_PATH . '_fields/') && $zym_4 -> del(DATA_PATH . '_fields/');
        is_dir(CACHE_PATH) && $zym_4 -> delDir(CACHE_PATH);
        is_dir(DATA_PATH) && $zym_4 -> del(DATA_PATH);
        is_dir(TEMP_PATH) && $zym_4 -> delDir(TEMP_PATH);
        is_dir(FTX_DATA_PATH . 'taobaoapi/') && $zym_4 -> delDir(FTX_DATA_PATH . 'taobaoapi/');
        is_dir(LOG_PATH) && $zym_4 -> delDir(LOG_PATH);
        @unlink(RUNTIME_FILE);
        $this -> ajaxReturn(1, L('clear_success'));
    }
}
?>