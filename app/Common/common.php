<?php

function p($arr){
	dump($arr);
    exit;
}
 
function objtoarr($obj){
	$ret = array();
	foreach($obj as $key =>$value){
		if(gettype($value) == 'array' || gettype($value) == 'object'){
			$ret[$key] = objtoarr($value);
		}
		else{
			$ret[$key] = $value;
		}
	}
	return $ret;
}

function lefttime($second){
	$times = '';
    $day = floor($second/(3600*24));
    $second = $second%(3600*24);//除去整天之后剩余的时间
    $hour = floor($second/3600);
    $second = $second-$hour*3600;//除去整小时之后剩余的时间
    $minute = floor($second/60);
    $second = fmod(floatval($second),60);//除去整分钟之后剩余的时间
	if($day){
		$times = $day.'天';
	}
	if($hour){
		$times.=$hour.'小时';
	}
	if($minute){
		$times.=$minute.'分';
	}
	if($second){
		$times.=$second.'秒';
	}
    //返回字符串
    return $times;
}

function fftime($time){
	$tomorrow = strtotime(date('Y-m-d',strtotime("+1 day")));
	if($tomorrow > $time){
		return '今日<i>'.date('H时i分',$time).'</i>开始';
	}else{
		$lefttime = $time - $tomorrow;
		if($lefttime < 86400){
			return '明日<i>'.date('H时i分',$time).'</i>开始';
		}else{
			return '<i>'.date('m月d日 H点i分',$time).'</i>开始';
		}
	}
}

//秒数转换时间
function changeTimeType($seconds){
	if ($seconds>3600){
		$hours = intval($seconds/3600);
		$minutes = $seconds600;
		$time = $hours."时".gmstrftime('%M分%S秒', $minutes);
	}else{
		$time = gmstrftime('%H时%M分%S秒', $seconds);
	}
	return $time;
}



function addslashes_deep($value) {
    $value = is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    return $value;
}

function stripslashes_deep($value) {
    if (is_array($value)) {
        $value = array_map('stripslashes_deep', $value);
    } elseif (is_object($value)) {
        $vars = get_object_vars($value);
        foreach ($vars as $key => $data) {
            $value->{$key} = stripslashes_deep($data);
        }
    } else {
        $value = stripslashes($value);
    }

    return $value;
}

function filter_default(&$value){
    $value = htmlspecialchars($value);
}

function Newiconv($_input_charset="GBK",$_output_charset="UTF-8",$input ) {
	$output = "";
	if(!isset($_output_charset) )$_output_charset = $this->parameter['_input_charset '];
	if($_input_charset == $_output_charset || $input ==null) { $output = $input;
	}
	elseif (function_exists("m\x62_\x63\x6fn\x76\145\x72\164_\145\x6e\x63\x6f\x64\x69\x6e\147")){
		$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
	} elseif(function_exists("\x69\x63o\156\x76")) {
		$output = iconv($_input_charset,$_output_charset,$input);
		} 
		else die("对不起，你的服务器系统无法进行字符转码.请联系空间商。");
		return $output; 
}

function newicon($time){
	$date = '';
	if (date('Y-m-d') == date('Y-m-d',$time)){
		$date = '<div class="goods_icon" style=" background:url(__STATIC__/newpi/images/today_goods.png) no-repeat;">今日<br>上新</div>';
	}
	return $date;
}

function itemcount($id){
	$map['pass'] = '1';
	$id_arr =  D('items_cate')->get_child_ids($id, true);
    $map['cate_id'] = array('IN', $id_arr);	
	$count = D('items')->where($map)->count();
	return $count;
}
function temaicount($id){
	$map['pass'] = '1';
	$map['item_type'] = '3';
	$id_arr =  D('items_cate')->get_child_ids($id, true);
    $map['cate_id'] = array('IN', $id_arr);	
	$count = D('items')->where($map)->count();
	return $count;
}
function quancount($id){
	$map['pass'] = '1';
	$map['item_type'] = '2';
	$id_arr =  D('items_cate')->get_child_ids($id, true);
    $map['cate_id'] = array('IN', $id_arr);	
	$count = D('items')->where($map)->count();
	return $count;
}

function sannewicon($time){
	$date = '';
	if (date('Y-m-d') == date('Y-m-d',$time)){
		$date = '<span class="label">今日<br>新品</span>';
	}
	return $date;
}
function sanwapnewicon($time){
	$date = '';
	if (date('Y-m-d') == date('Y-m-d',$time)){
		$date = '<span class="today-wrapper"><span>今日</span><span>新品</span></span>';
	}
	return $date;
}

function mnewicon($time){
	$date = '';
	if (date('Y-m-d') == date('Y-m-d',$time)){
		$date = '<img class="new-icon" src="__STATIC__/wap/images/new.png" />';
	}
	return $date;
}

function todaytime() {
    return mktime(0, 0, 0, date('m'), date('d'), date('Y'));
}

function get_word($html,$star,$end){
	$pat = '/'.$star.'(.*?)'.$end.'/s';
	if(!preg_match_all($pat, $html, $mat)) {                
	}else{
		$wd= $mat[1][0];
	}
	return $wd;
}

/**
 * 友好时间
 */
function frienddate($time) {
    $rtime = date("m-d H:i",$time);
    $htime = date("H:i",$time);
    $timetime = time() - $time;

    if ($timetime < 60) {
       $str = '刚刚';
    }
    else if ($timetime < 60 * 60) {
       $min = floor($timetime/60);
       $str = $min.'分钟前';
    }
    else if ($timetime < 60 * 60 * 24) {
       $h = floor($timetime/(60*60));
       $str = $h.'小时前 ';
    }
    else if ($timetime < 60 * 60 * 24 * 3) {
       $d = floor($timetime/(60*60*24));
       if($d==1)
       $str = '昨天 '.$htime;
    else
       $str = '前天 '.$htime;
    }
    else {
       $str = $rtime;
    }
    return $str;
}


function fdate($time) {
    if (!$time)
        return false;
    $fdate = '';
    $d = time() - intval($time);
    $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //年
    $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //月
    $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
    $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
    $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
    $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
    $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
    if ($d == 0) {
        $fdate = '刚刚';
    } else {
        switch ($d) {
            case $d < $atd:
                $fdate = date('Y年m月d日', $time);
                break;
            case $d < $td:
                $fdate = '后天' . date('H:i', $time);
                break;
            case $d < 0:
                $fdate = '明天' . date('H:i', $time);
                break;
            case $d < 60:
                $fdate = $d . '秒前';
                break;
            case $d < 3600:
                $fdate = floor($d / 60) . '分钟前';
                break;
            case $d < $dd:
                $fdate = floor($d / 3600) . '小时前';
                break;
            case $d < $yd:
                $fdate = '昨天' . date('H:i', $time);
                break;
            case $d < $byd:
                $fdate = '前天' . date('H:i', $time);
                break;
            case $d < $md:
                $fdate = date('m月d日 H:i', $time);
                break;
            case $d < $ld:
                $fdate = date('m月d日', $time);
                break;
            default:
                $fdate = date('Y年m月d日', $time);
                break;
        }
    }
    return $fdate;
}

function utf_substr($str, $len) {
	for ($i = 0; $i < $len; $i++) {
		$temp_str = substr($str, 0, 1);
		if (ord($temp_str) > 127) {
			$i++;
			if ($i < $len) {
				$new_str[] = substr($str, 0, 3);
				$str = substr($str, 3);
			}
		} else {
			$new_str[] = substr($str, 0, 1);
			$str = substr($str, 1);
		}
	}
	return join($new_str).'......';
}
 
/**
 * 获取用户头像
 */
function avatar($uid, $size) {
    $avatar_size = explode(',', C('ftx_avatar_size'));
    $size = in_array($size, $avatar_size) ? $size : '100';
    $avatar_dir = avatar_dir($uid);
    $avatar_file = $avatar_dir . md5($uid) . "_{$size}.jpg";
    if (!is_file(C('ftx_attach_path') . 'avatar/' . $avatar_file)) {
        $avatar_file = "default_{$size}.jpg";
    }
    return __ROOT__ . '/' . C('ftx_attach_path') . 'avatar/' . $avatar_file;
}

function avatar_dir($uid) {
    $uid = abs(intval($uid));
    $suid = sprintf("%09d", $uid);
    $dir1 = substr($suid, 0, 3);
    $dir2 = substr($suid, 3, 2);
    $dir3 = substr($suid, 5, 2);
    return $dir1 . '/' . $dir2 . '/' . $dir3 . '/';
}


function http( $url, $ua = "" ){
	$opts = array(
		"http" => array(
			"header" => "USER-AGENT: ".$ua)
	);
	$context = stream_context_create( $opts );
    $html = @file_get_contents( $url, FALSE, $context );
	if(!$html){
		$html = @file_get_contents( $url, FALSE, $context );
		if(!$html){
			$html = @file_get_contents( $url, FALSE, $context);
		}
	}
	for($i=0; $i < 10; $i++ ){
		if(!($html=== FALSE )){
			break;
		}
		$html = @file_get_contents( $url, FALSE, $context );
	}
	return $html;
}

function utf8( $string, $code = "" ){
	$code = @mb_detect_encoding($string,array("UTF-8", "GBK"));
	return mb_convert_encoding( $string, "UTF-8", $code );
}

function attach($attach, $type) {
    if (false === strpos($attach, 'http://')) {
        //本地附件
        return __ROOT__ . '/' . C('ftx_attach_path') . $type . '/' . $attach;
        //远程附件
        //todo...
    } else {
        //URL链接
        return $attach;
    }
}
function get_id($url) {
        $id = 0;
        $parse = parse_url($url);
        if (isset($parse['query'])) {
            parse_str($parse['query'], $params);
            if (isset($params['id'])) {
                $id = $params['id'];
            } elseif (isset($params['item_id'])) {
                $id = $params['item_id'];
            } elseif (isset($params['default_item_id'])) {
                $id = $params['default_item_id'];
            } elseif (isset($params['amp;id'])) {
                $id = $params['amp;id'];
            } elseif (isset($params['amp;item_id'])) {
                $id = $params['amp;item_id'];
            } elseif (isset($params['amp;default_item_id'])) {
                $id = $params['amp;default_item_id'];
            }
        }
        return $id;
    }
/*
 * 获取缩略图
 */
function get_thumb($img, $suffix = '_thumb') {
	$img = str_replace("https", "http", $img);	
    if (false === strpos($img, 'http://')) {
        $ext = array_pop(explode('.', $img));
        $thumb = str_replace('.' . $ext, $suffix . '.' . $ext, $img);
    } else {
        if (false !== strpos($img, 'taobaocdn.com') || false !== strpos($img, 'taobao.com') || false !== strpos($img, 'alicdn.com')|| false !== strpos($img, 'tbcdn.cn')) {
            //淘宝图片 _s _m _b
            switch ($suffix) {
                case '_s':
                    $thumb = $img . '_100x100.jpg';
                    break;
				case '_g':
                    $thumb = $img . '_150x150.jpg';
                    break;
                case '_m':
                    $thumb = $img . '_240x240.jpg';
                    break;
                case '_b':
                    $thumb = $img . '_310x310.jpg';
                    break;
				case '_a':
                    $thumb = $img . '_320x320.jpg';
                    break;
				case '_t':
                    $thumb = $img . '_350x350.jpg';
                    break;	
				case '_f':
                    $thumb = $img . '_350x350.jpg';
                    break;	
				case '_w':
                    $thumb = $img;
                    break;		
				case '_p':
                    $thumb = $img . '_200x200.jpg';
                    break;	
            }
        }else{
			$thumb = $img;
		}
    }
    return $thumb;
}


/**
 * 将数组键名变成大写或小写
 * @param array $arr 数组
 * @param int $type 转换方式 1大写   0小写
 * @return array
 */
function array_change_key_case_d($arr, $type = 0)
{
    $function = $type ? 'strtoupper' : 'strtolower';
    $newArr = array(); //格式化后的数组
    if (!is_array($arr) || empty($arr))
        return $newArr;
    foreach ($arr as $k => $v) {
        $k = $function($k);
        if (is_array($v)) {
            $newArr[$k] = array_change_key_case_d($v, $type);
        } else {
            $newArr[$k] = $v;
        }
    }
    return $newArr;
}
/**
 * 对象转换成数组
 */
function object_to_array($obj) {
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val) {
        $val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}


function is_email($user_email){
	$chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
	if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false){
		if (preg_match($chars, $user_email)){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}


/**
 * ID 字母 转换
 */
function id_num($in,$to_num = false,$pad_up = false,$passKey = null)  {
    if(!function_exists('bcpow')) {
            return $in;
    }
        $index = 'abcdefghijklmnopqrstuvwxyz0123456789';
        if ($passKey !== null) {
            for ($n = 0;$n<strlen($index);$n++) {
                $i[] = substr( $index,$n ,1);
            }
            $passhash = hash('sha256',$passKey);
            $passhash = (strlen($passhash) <strlen($index))?hash('sha512',$passKey) : $passhash;
            for ($n=0;$n <strlen($index);$n++) {
                $p[] =  substr($passhash,$n ,1);
            }
            array_multisort($p,SORT_DESC,$i);
            $index = implode($i);
        }
        $base  = strlen($index);
        if ($to_num) {
            $in  = strrev($in);
            $out = 0;
            $len = strlen($in) -1;
            for ($t = 0;$t <= $len;$t++) {
                $bcpow = bcpow($base,$len -$t);
                $out   = $out +strpos($index,substr($in,$t,1)) * $bcpow;
            }
            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up >0) {
                    $out -= pow($base,$pad_up);
                }
            }
            $out = sprintf('%F',$out);
            $out = substr($out,0,strpos($out,'.'));
        }else {
            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up >0) {
                    $in += pow($base,$pad_up);
                }
            }
            $out = '';
            for ($t = floor(log($in,$base));$t >= 0;$t--) {
                $bcp = bcpow($base,$t);
                $a   = floor($in / $bcp) %$base;
                $out = $out .substr($index,$a,1);
                $in  = $in -($a * $bcp);
            }
            $out = strrev($out);
        }
        return $out;
}
     function check_cookies($url){
	        $cookies = C('ftx_cookie');
		  	$search = array(" ","　","\n","\r","\t");  $replace = array("","","","","");  
			$cookies = str_replace($search, $replace, $cookies); 
			$token	=get_word($cookies,'_tb_token_=',';');
			$t = get_word($cookies,'t=',';');
			$cna = get_word($cookies,'cna=',';');
			$l = get_word($cookies,'l=',';');
			$guidance3 = get_word($cookies,'mm-guidance3',';');
			$_umdata = get_word($cookies,'_umdata=',';');
			$cookie2 = get_word($cookies,'cookie2=',';');
			$cookie32 = get_word($cookies,'cookie32=',';');
			$cookie31 = get_word($cookies,'cookie31=',';');
			$alimamapwag = get_word($cookies,'alimamapwag=',';');
			$login = get_word($cookies,'login=',';');
			$alimamapw = get_word($cookies,'alimamapw=',';');
			$cookie = 't='.$t.';cna='.$cna.';l='.$l.';mm-guidance3='.$guidance3.';_umdata='.$_umdata.';cookie2='.$cookie2.';_tb_token_='.$token.';v=0;cookie32='.$cookie32.';cookie31='.$cookie31.';alimamapwag='.$alimamapwag.';login='.$login.';alimamapw='.$alimamapw;		
			$time   =microtime(true)*1000;
		    $time   = explode('.', $time);
			$chu = curl_init();
			curl_setopt($chu, CURLOPT_URL, $url);
			curl_setopt($chu, CURLOPT_REFERER, "http://pub.alimama.com/myunion.htm?spm=a2320.7388781.a214tr8.d006.gtkltZ"); 
			curl_setopt($chu, CURLOPT_HTTPHEADER, array(
			"Cookie:{".$cookie."}",
			));
			curl_setopt($chu, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($chu, CURLOPT_FOLLOWLOCATION, 1);
			$ckunt = curl_exec($chu);
			curl_close($chu);		
			$ckapi = json_decode($ckunt,true);
			$id = $ckapi['data']['memberid'];						
		    return $id;
	  }
   function getquaninfo($sellerid,$activityid){
        $qurl = "http://shop.m.taobao.com/shop/coupon.htm?seller_id=".$sellerid."&activity_id=".$activityid; 		
	    $wapua = "Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4";
	    $sources = http($qurl,$wapua);
		if(!$sources){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $qurl);
		curl_setopt($ch, CURLOPT_USERAGENT, $wapua);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$sources = curl_exec($ch);	
		curl_close($ch);	
		}
	    $shoptitle = get_word($sources,'<title>','<\/title>');
	    $quan  = get_word($sources,'<dt>','<\/dt>');	
	    $quantitle = $shoptitle.' '.$quan;		
	    $quantitle = mb_convert_encoding($quantitle, "GBK", "UTF-8");
	    $quantitle = urlencode($quantitle);				
	    $info = array();	   			
	    $info['pcurl'] = "http://taoquan.taobao.com/coupon/unify_apply_result.htm?seller_id=".$sellerid."&success=false&need_check=false&need_ok=true&activity_id=".$activityid."&apply_source=daily&is_collina_check=true&ok_str=".$quantitle; 	
		$info['wapurl'] = $qurl;
		if(!$quan){
		$info['msg'] = 0;	
		}else{
		$info['msg'] = 1;	
		}
		return $info;
	}
	function getdesc($iid){
		$infoUrl = "http://hws.m.taobao.com/cache/mtop.wdetail.getItemDescx/4.1/?data=%7B%22item_num_id%22%3A%22".$iid."%22%7D";
        $che = curl_init();         
        curl_setopt($che, CURLOPT_URL, $infoUrl);
        curl_setopt($che, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($che, CURLOPT_FOLLOWLOCATION,true);
        curl_setopt($che, CURLOPT_MAXREDIRS,2);
        $source = curl_exec($che);
        curl_close($che);
		$dapi = json_decode($source,true);
		$imglist = $dapi['data']['images'];
	    $num   = count($imglist);
		for($i=0;$i<$num;$i++){
		$imgurl .= '<img class="lazy" src='.$imglist[$i].'>';
		}		
		
		return $imgurl;
	}
	