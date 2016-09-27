<?php
 class getdtkAction extends BackendAction{
    public function _initialize(){
        parent :: _initialize();
    }
    public function index(){
        $zym_15 = I('appkey', '', 'trim');
        $zym_14 = I('edp', '', 'trim');
        if(!$zym_15){
            $this -> ajaxReturn(0, '请先在采集设置中填写大淘客API!');
        }
        $zym_13 = I('p', 1, 'intval');
        $zym_18 = 'http://api.dataoke.com/index.php?r=Port/index&type=total&appkey=' . $zym_15 . '&v=2&page=' . $zym_13;
        $zym_20 = new yangtata();
        $zym_20 -> fetch($zym_18);
        $zym_22 = $zym_20 -> results;
        $zym_22 = preg_replace('/\s/', "", $zym_22);
        $zym_22 = str_replace('' . "\x1f" . '', '', $zym_22);
        $zym_21 = json_decode($zym_22, true);
        $zym_19 = $zym_21['data']['total_num'];
        if($zym_14){
            $zym_12 = $zym_14;
        }else{
            $zym_12 = ceil($zym_19 / 200);
        }
        $zym_11 = FTX_DATA_PATH . 'cookies/';
        if (!is_dir($zym_11)){
            mkdir($zym_11, 0777);
        }
        $zym_4 = $zym_11 . 'all' . $zym_13 . '.txt';
        file_put_contents($zym_4, "");
        file_put_contents($zym_4, $zym_22, FILE_APPEND);
        $this -> assign('p', $zym_13);
        $this -> assign('page', $zym_12);
        $this -> assign('totalnum', $zym_19);
        if($zym_13 >= $zym_12){
            $this -> assign('msg', '采集完成!开始合并数据！不要关闭窗口');
            for($zym_3 = 0;$zym_3 < $zym_12;$zym_3++){
                $zym_2 = $zym_3 + 1;
                $zym_1 = array('dataoke_file' => FTX_DATA_PATH . 'cookies/all' . $zym_2 . '.txt',);
                $zym_5 = file_get_contents($zym_1['dataoke_file']);
                $zym_6 = json_decode($zym_5, true);
                $zym_10[] = $zym_6['result'];
            }
            $zym_9 = array_merge($zym_10);
            $zym_9 = json_encode($zym_9);
            $zym_9 = str_replace('],[', ',', $zym_9);
            $zym_9 = str_replace('[[', '[', $zym_9);
            $zym_9 = str_replace(']]', ']', $zym_9);
            $zym_8 = $zym_11 . 'dataoke2.txt';
            file_put_contents($zym_8, "");
            file_put_contents($zym_8, $zym_9, FILE_APPEND);
            $this -> ajaxReturn(0, '全部获取完成!');
        }
        $zym_7 = $this -> fetch('collect');
        $this -> ajaxReturn(1, '', $zym_7);
    }
}
?>