<?php
 class FirstendAction extends TopAction{
    public function _initialize(){
        parent :: _initialize();
        if (!C('ftx_site_status')){
            header('Content-Type:text/html; charset=utf-8');
            exit(C('ftx_closed_reason'));
        }
        $this -> assign('nav_curr', '');
        $zym_11 = D('items_cate') -> cate_data_cache();
        $this -> assign('cate_data', $zym_11);
    }
    protected function _config_seo($zym_13 = array(), $zym_14 = array()){
        $zym_16 = array('title' => C('ftx_site_title'), 'keywords' => C('ftx_site_keyword'), 'description' => C('ftx_site_description'));
        $zym_16 = array_merge($zym_16, $zym_13);
        $zym_15 = array('{site_name}', '{site_title}', '{site_keywords}', '{site_description}');
        $zym_10 = array(C('ftx_site_name'), C('ftx_site_title'), C('ftx_site_keyword'), C('ftx_site_description'));
        preg_match_all('/\{([a-z0-9_-]+?)\}/', implode(' ', array_values($zym_16)), $zym_9);
        if ($zym_9){
            foreach ($zym_9[1] as $zym_3){
                $zym_15[] = '{' . $zym_3 . '}';
                $zym_10[] = $zym_14[$zym_3] ? strip_tags($zym_14[$zym_3]) : '';
            }
            $zym_2 = array('((\s*\-\s*)+)', '((\s*\,\s*)+)', '((\s*\|\s*)+)', '((\s*\t\s*)+)', '((\s*_\s*)+)');
            $zym_1 = array('-', ',', '|', ' ', '_');
            foreach ($zym_16 as $zym_4 => $zym_5){
                $zym_16[$zym_4] = trim(preg_replace($zym_2, $zym_1, str_replace($zym_15, $zym_10, $zym_5)), ' ,-|_');
            }
        }
        $this -> assign('page_seo', $zym_16);
    }
    protected function _pager($zym_8, $zym_7){
        $zym_6 = new Page($zym_8, $zym_7);
        $zym_6 -> rollPage = 5;
        $zym_6 -> setConfig('header', '' . "\xcc\xf5\xbc\xc7\xc2\xbc" . '');
        $zym_6 -> setConfig('prev', '' . "\xc9\xcf\xd2\xbb\xd2\xb3" . '');
        $zym_6 -> setConfig('next', '' . "\xcf\xc2\xd2\xbb\xd2\xb3" . '');
        $zym_6 -> setConfig('first', '' . "\xb5\xda\xd2\xbb\xd2\xb3" . '');
        $zym_6 -> setConfig('last', '' . "\xd7\xee\xba\xf3\xd2\xbb\xd2\xb3" . '');
        $zym_6 -> setConfig('theme', '%upPage% %first% %linkPage% %end% %downPage%');
        return $zym_6;
    }
}
?>