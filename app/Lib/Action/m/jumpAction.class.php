<?php

class jumpAction extends FirstendAction {

    public function _initialize() {
        parent::_initialize();
		$this->_mod = D('items');		
    }

    /**
     * 淘宝跳转
     */
    public function index() {		
		$id = I('id','', 'trim');			
		$item = $this->_mod->where(array('num_iid' => $id))->find();
		$this->assign('item', $item);
        $this->display();
    }
	 public function view() {		
		$id = I('id','', 'trim');			
		$item = $this->_mod->where(array('num_iid' => $id))->find();
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,"http://dwz.cn/create.php");
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		$data=array('url'=>$item['wap_url']);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		$strRes=curl_exec($ch);
		curl_close($ch);
		$arrResponse=json_decode($strRes,true);
		$data['click_url'] = $arrResponse['tinyurl'];
		if(!$data['click_url']){
		$url = 	"http://50r.cn/urls/add.json?url=".$item['wap_url'];
		$ch = curl_init();         
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
	    curl_setopt($ch, CURLOPT_MAXREDIRS,2);
	    $content = curl_exec($ch);
	    curl_close($ch);
	    $dapi = json_decode($content,true); 		
		$data['click_url'] = $dapi['url'];	
		}		
		$this->assign('item', $data);
        $this->display('index');
    }
	
}
?>