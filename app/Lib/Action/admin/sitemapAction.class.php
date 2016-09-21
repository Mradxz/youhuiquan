<?php
 class sitemapAction extends BackendAction{
    public function _initialize(){
        parent :: _initialize();
    }
    public function index(){
        $this -> display();
    }
    public function set_sitemap(){
        $zym_9 = I('open');
        C('sitemap', $zym_9);
    }
    public function sitemap(){
        $zym_12 = CONF_PATH . 'url.php';
        $zym_13 = require $zym_12;
        $zym_14 = D('sitemap') -> get_last_data();
        $zym_8 = D('sitemap') -> get_cat_list();
        $zym_7 = C('ftx_site_url');
        $zym_2 = '<urlset xmlns=\'http://www.sitemaps.org/schemas/sitemap/0.9\'>';
        $zym_2 .= '<url>';
        $zym_2 .= '<loc>' . htmlspecialchars($zym_7) . '</loc>';
        $zym_2 .= '<changefreq>weekly</changefreq>';
        $zym_2 .= '<lastmod>' . date(DATE_ATOM, time()) . '</lastmod>';
        $zym_2 .= '<priority>1</priority>';
        $zym_2 .= '</url>';
        if($zym_13['URL_MODEL'] == 2){
            if($zym_8){
                foreach($zym_8 as $zym_1 => $zym_3){
                    $zym_2 .= '<url>';
                    $zym_2 .= '<loc>' . htmlspecialchars($zym_7 . 'index/cate/cid/' . $zym_3['id'] . $zym_13['URL_HTML_SUFFIX']) . '</loc>';
                    $zym_2 .= '<changefreq>daily</changefreq>';
                    $zym_2 .= '<lastmod>' . date(DATE_ATOM, time()) . '</lastmod>';
                    $zym_2 .= '<priority>0.9</priority>';
                    $zym_2 .= '</url>';
                }
            }
            if($zym_14){
                foreach($zym_14 as $zym_4 => $zym_6){
                    $zym_2 .= '<url>';
                    $zym_2 .= '<loc>' . htmlspecialchars($zym_7 . 'item/' . $zym_6['num_iid'] . $zym_13['URL_HTML_SUFFIX']) . '</loc>';
                    $zym_2 .= '<changefreq>daily</changefreq>';
                    $zym_2 .= '<lastmod>' . date(DATE_ATOM, time()) . '</lastmod>';
                    $zym_2 .= '<priority>0.9</priority>';
                    $zym_2 .= '</url>';
                }
            }
        }else{
            if($zym_8){
                foreach($zym_8 as $zym_1 => $zym_3){
                    $zym_2 .= '<url>';
                    $zym_2 .= '<loc>' . htmlspecialchars($zym_7 . '?m=index&a=cate&cid=' . $zym_3['id']) . '</loc>';
                    $zym_2 .= '<changefreq>daily</changefreq>';
                    $zym_2 .= '<lastmod>' . date(DATE_ATOM, time()) . '</lastmod>';
                    $zym_2 .= '<priority>0.9</priority>';
                    $zym_2 .= '</url>';
                }
            }
            if($zym_14){
                foreach($zym_14 as $zym_4 => $zym_6){
                    $zym_2 .= '<url>';
                    $zym_2 .= '<loc>' . htmlspecialchars($zym_7 . '?m=item&a=index&id=' . $zym_6['num_iid']) . '</loc>';
                    $zym_2 .= '<changefreq>daily</changefreq>';
                    $zym_2 .= '<lastmod>' . date(DATE_ATOM, time()) . '</lastmod>';
                    $zym_2 .= '<priority>0.9</priority>';
                    $zym_2 .= '</url>';
                }
            }
        }
        $zym_2 .= '</urlset>';
        $zym_5 = file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml', $zym_2);
        if($zym_5){
            $this -> ajaxReturn(1, '更新站点地图完成');
        }else{
            $this -> ajaxReturn(0, '更新失败');
        }
    }
}
?>