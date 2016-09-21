<?php
  /**
  * 站点地图model
  * @author 杨他他  2014年7月24日, PM 01:31:05
  */

class sitemapModel extends Model {
    //关联关系
    /**
    * 查询今天抓取的数据，根据时间排序，获得最后的2000条数据
    * @author 杨他他  2014年7月24日, PM 01:47:51
    */
    public function get_last_data(){
        $day_start = strtotime(date('Y-m-d',time()))-86400 ;
        $day_end = $day_start+86399;
        $sql = "SELECT id,num_iid FROM ".C('DB_PREFIX')."items WHERE id ORDER BY id DESC limit 10000";
        $result = $this->query($sql);
        return $result;
    }
    
    
    /**
    * 查询出来所有商品分类
    * @author 杨他他  2014年7月24日, PM 02:42:21
    */
    public function get_cat_list(){
        $sql = "SELECT id FROM ".C('DB_PREFIX')."items_cate WHERE status = 1 ";
        return  $this->query($sql);
    }
	
   
    
}
?>
