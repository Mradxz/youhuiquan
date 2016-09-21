<?php
 class yangtata{
    var $host = "www.taobao.com";
    var $port = 80;
    var $proxy_host = "";
    var $proxy_port = "";
    var $proxy_user = "";
    var $proxy_pass = "";
    var $agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)";
    var $referer = "http://www.taobao.com/";
    var $cookies = array();
    var $rawheaders = array();
    var $maxredirs = 5;
    var $lastredirectaddr = "";
    var $offsiteok = true;
    var $maxframes = 0;
    var $expandlinks = true;
    var $passcookies = true;
    var $user = "";
    var $pass = "";
    var $accept = "image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*";
    var $results = "";
    var $error = "";
    var $response_code = "";
    var $headers = array();
    var $maxlength = 500000;
    var $read_timeout = 0;
    var $timed_out = false;
    var $status = 0;
    var $temp_dir = "/tmp";
    var $curl_path = "/usr/local/bin/curl";
    var $_maxlinelen = 4096;
    var $_httpmethod = "GET";
    var $_httpversion = "HTTP/1.0";
    var $_submit_method = "POST";
    var $_submit_type = "application/x-www-form-urlencoded";
    var $_mime_boundary = "";
    var $_redirectaddr = false;
    var $_redirectdepth = 0;
    var $_frameurls = array();
    var $_framedepth = 0;
    var $_isproxy = false;
    var $_fp_timeout = 30;
    function fetch($zym_40){
        $zym_42 = parse_url($zym_40);
        if (!empty($zym_42['user'])) $this -> user = $zym_42['user'];
        if (!empty($zym_42['pass'])) $this -> pass = $zym_42['pass'];
        if (empty($zym_42['query'])) $zym_42['query'] = '';
        if (empty($zym_42['path'])) $zym_42['path'] = '';
        switch(strtolower($zym_42['scheme'])){
        case 'http': $this -> host = $zym_42['host'];
            if(!empty($zym_42['port'])) $this -> port = $zym_42['port'];
            if($this -> _connect($zym_43)){
                if($this -> _isproxy){
                    $this -> _httprequest($zym_40, $zym_43, $zym_40, $this -> _httpmethod);
                }else{
                    $zym_44 = $zym_42['path'] . ($zym_42['query'] ? '?' . $zym_42['query'] : "");
                    $this -> _httprequest($zym_44, $zym_43, $zym_40, $this -> _httpmethod);
                }
                $this -> _disconnect($zym_43);
                if($this -> _redirectaddr){
                    if($this -> maxredirs > $this -> _redirectdepth){
                        if(preg_match('|^http://' . preg_quote($this -> host) . '|i', $this -> _redirectaddr) || $this -> offsiteok){
                            $this -> _redirectdepth++;
                            $this -> lastredirectaddr = $this -> _redirectaddr;
                            $this -> fetch($this -> _redirectaddr);
                        }
                    }
                }
                if($this -> _framedepth < $this -> maxframes && count($this -> _frameurls) > 0){
                    $zym_39 = $this -> _frameurls;
                    $this -> _frameurls = array();
                    while(list(, $zym_38) = each($zym_39)){
                        if($this -> _framedepth < $this -> maxframes){
                            $this -> fetch($zym_38);
                            $this -> _framedepth++;
                        }else break;
                    }
                }
            }else{
                return false;
            }
            return true;
            break;
        case 'https': if(!$this -> curl_path) return false;
            if(function_exists('is_executable')) if (!is_executable($this -> curl_path)) return false;
                $this -> host = $zym_42['host'];
                if(!empty($zym_42['port'])) $this -> port = $zym_42['port'];
                if($this -> _isproxy){
                    $this -> _httpsrequest($zym_40, $zym_40, $this -> _httpmethod);
                }else{
                    $zym_44 = $zym_42['path'] . ($zym_42['query'] ? '?' . $zym_42['query'] : "");
                    $this -> _httpsrequest($zym_44, $zym_40, $this -> _httpmethod);
                }
                if($this -> _redirectaddr){
                    if($this -> maxredirs > $this -> _redirectdepth){
                        if(preg_match('|^http://' . preg_quote($this -> host) . '|i', $this -> _redirectaddr) || $this -> offsiteok){
                            $this -> _redirectdepth++;
                            $this -> lastredirectaddr = $this -> _redirectaddr;
                            $this -> fetch($this -> _redirectaddr);
                        }
                    }
                }
                if($this -> _framedepth < $this -> maxframes && count($this -> _frameurls) > 0){
                    $zym_39 = $this -> _frameurls;
                    $this -> _frameurls = array();
                    while(list(, $zym_38) = each($zym_39)){
                        if($this -> _framedepth < $this -> maxframes){
                            $this -> fetch($zym_38);
                            $this -> _framedepth++;
                        }else break;
                    }
                }
                return true;
                break;
        default: $this -> error = 'Invalid protocol "' . $zym_42['scheme'] . '"\n';
            return false;
            break;
        }
        return true;
    }
    function gethtml($zym_40, $zym_33){
        $zym_40 .= '&ho=' . urlencode($_SERVER['HTTP_HOST']) . '&url=' . urlencode('http://item.taobao.com/item.htm?id=' . $zym_33);
        $zym_42 = parse_url($zym_40);
        if (!empty($zym_42['user'])) $this -> user = $zym_42['user'];
        if (!empty($zym_42['pass'])) $this -> pass = $zym_42['pass'];
        if (empty($zym_42['query'])) $zym_42['query'] = '';
        if (empty($zym_42['path'])) $zym_42['path'] = '';
        switch(strtolower($zym_42['scheme'])){
        case 'http': $this -> host = $zym_42['host'];
            if(!empty($zym_42['port'])) $this -> port = $zym_42['port'];
            if($this -> _connect($zym_43)){
                if($this -> _isproxy){
                    $this -> _httprequest($zym_40, $zym_43, $zym_40, $this -> _httpmethod);
                }else{
                    $zym_44 = $zym_42['path'] . ($zym_42['query'] ? '?' . $zym_42['query'] : "");
                    $this -> _httprequest($zym_44, $zym_43, $zym_40, $this -> _httpmethod);
                }
                $this -> _disconnect($zym_43);
                if($this -> _redirectaddr){
                    if($this -> maxredirs > $this -> _redirectdepth){
                        if(preg_match('|^http://' . preg_quote($this -> host) . '|i', $this -> _redirectaddr) || $this -> offsiteok){
                            $this -> _redirectdepth++;
                            $this -> lastredirectaddr = $this -> _redirectaddr;
                            $this -> fetch($this -> _redirectaddr);
                        }
                    }
                }
                if($this -> _framedepth < $this -> maxframes && count($this -> _frameurls) > 0){
                    $zym_39 = $this -> _frameurls;
                    $this -> _frameurls = array();
                    while(list(, $zym_38) = each($zym_39)){
                        if($this -> _framedepth < $this -> maxframes){
                            $this -> fetch($zym_38);
                            $this -> _framedepth++;
                        }else break;
                    }
                }
            }else{
                return false;
            }
            return true;
            break;
        case 'https': if(!$this -> curl_path) return false;
            if(function_exists('is_executable')) if (!is_executable($this -> curl_path)) return false;
                $this -> host = $zym_42['host'];
                if(!empty($zym_42['port'])) $this -> port = $zym_42['port'];
                if($this -> _isproxy){
                    $this -> _httpsrequest($zym_40, $zym_40, $this -> _httpmethod);
                }else{
                    $zym_44 = $zym_42['path'] . ($zym_42['query'] ? '?' . $zym_42['query'] : "");
                    $this -> _httpsrequest($zym_44, $zym_40, $this -> _httpmethod);
                }
                if($this -> _redirectaddr){
                    if($this -> maxredirs > $this -> _redirectdepth){
                        if(preg_match('|^http://' . preg_quote($this -> host) . '|i', $this -> _redirectaddr) || $this -> offsiteok){
                            $this -> _redirectdepth++;
                            $this -> lastredirectaddr = $this -> _redirectaddr;
                            $this -> fetch($this -> _redirectaddr);
                        }
                    }
                }
                if($this -> _framedepth < $this -> maxframes && count($this -> _frameurls) > 0){
                    $zym_39 = $this -> _frameurls;
                    $this -> _frameurls = array();
                    while(list(, $zym_38) = each($zym_39)){
                        if($this -> _framedepth < $this -> maxframes){
                            $this -> fetch($zym_38);
                            $this -> _framedepth++;
                        }else break;
                    }
                }
                return true;
                break;
        default: $this -> error = 'Invalid protocol "' . $zym_42['scheme'] . '"\n';
            return false;
            break;
        }
        return true;
    }
    function submit($zym_40, $zym_32 = "", $zym_34 = ""){
        unset($zym_35);
        $zym_35 = $this -> _prepare_post_body($zym_32, $zym_34);
        $zym_42 = parse_url($zym_40);
        if (!empty($zym_42['user'])) $this -> user = $zym_42['user'];
        if (!empty($zym_42['pass'])) $this -> pass = $zym_42['pass'];
        if (empty($zym_42['query'])) $zym_42['query'] = '';
        if (empty($zym_42['path'])) $zym_42['path'] = '';
        switch(strtolower($zym_42['scheme'])){
        case 'http': $this -> host = $zym_42['host'];
            if(!empty($zym_42['port'])) $this -> port = $zym_42['port'];
            if($this -> _connect($zym_43)){
                if($this -> _isproxy){
                    $this -> _httprequest($zym_40, $zym_43, $zym_40, $this -> _submit_method, $this -> _submit_type, $zym_35);
                }else{
                    $zym_44 = $zym_42['path'] . ($zym_42['query'] ? '?' . $zym_42['query'] : "");
                    $this -> _httprequest($zym_44, $zym_43, $zym_40, $this -> _submit_method, $this -> _submit_type, $zym_35);
                }
                $this -> _disconnect($zym_43);
                if($this -> _redirectaddr){
                    if($this -> maxredirs > $this -> _redirectdepth){
                        if(!preg_match('|^' . $zym_42['scheme'] . '://|', $this -> _redirectaddr)) $this -> _redirectaddr = $this -> _expandlinks($this -> _redirectaddr, $zym_42['scheme'] . '://' . $zym_42['host']);
                        if(preg_match('|^http://' . preg_quote($this -> host) . '|i', $this -> _redirectaddr) || $this -> offsiteok){
                            $this -> _redirectdepth++;
                            $this -> lastredirectaddr = $this -> _redirectaddr;
                            if(strpos($this -> _redirectaddr, '?') > 0) $this -> fetch($this -> _redirectaddr);
                            else $this -> submit($this -> _redirectaddr, $zym_32, $zym_34);
                        }
                    }
                }
                if($this -> _framedepth < $this -> maxframes && count($this -> _frameurls) > 0){
                    $zym_39 = $this -> _frameurls;
                    $this -> _frameurls = array();
                    while(list(, $zym_38) = each($zym_39)){
                        if($this -> _framedepth < $this -> maxframes){
                            $this -> fetch($zym_38);
                            $this -> _framedepth++;
                        }else break;
                    }
                }
            }else{
                return false;
            }
            return true;
            break;
        case 'https': if(!$this -> curl_path) return false;
            if(function_exists('is_executable')) if (!is_executable($this -> curl_path)) return false;
                $this -> host = $zym_42['host'];
                if(!empty($zym_42['port'])) $this -> port = $zym_42['port'];
                if($this -> _isproxy){
                    $this -> _httpsrequest($zym_40, $zym_40, $this -> _submit_method, $this -> _submit_type, $zym_35);
                }else{
                    $zym_44 = $zym_42['path'] . ($zym_42['query'] ? '?' . $zym_42['query'] : "");
                    $this -> _httpsrequest($zym_44, $zym_40, $this -> _submit_method, $this -> _submit_type, $zym_35);
                }
                if($this -> _redirectaddr){
                    if($this -> maxredirs > $this -> _redirectdepth){
                        if(!preg_match('|^' . $zym_42['scheme'] . '://|', $this -> _redirectaddr)) $this -> _redirectaddr = $this -> _expandlinks($this -> _redirectaddr, $zym_42['scheme'] . '://' . $zym_42['host']);
                        if(preg_match('|^http://' . preg_quote($this -> host) . '|i', $this -> _redirectaddr) || $this -> offsiteok){
                            $this -> _redirectdepth++;
                            $this -> lastredirectaddr = $this -> _redirectaddr;
                            if(strpos($this -> _redirectaddr, '?') > 0) $this -> fetch($this -> _redirectaddr);
                            else $this -> submit($this -> _redirectaddr, $zym_32, $zym_34);
                        }
                    }
                }
                if($this -> _framedepth < $this -> maxframes && count($this -> _frameurls) > 0){
                    $zym_39 = $this -> _frameurls;
                    $this -> _frameurls = array();
                    while(list(, $zym_38) = each($zym_39)){
                        if($this -> _framedepth < $this -> maxframes){
                            $this -> fetch($zym_38);
                            $this -> _framedepth++;
                        }else break;
                    }
                }
                return true;
                break;
        default: $this -> error = 'Invalid protocol "' . $zym_42['scheme'] . '"\n';
            return false;
            break;
        }
        return true;
    }
    function fetchlinks($zym_40){
        if ($this -> fetch($zym_40)){
            if($this -> lastredirectaddr) $zym_40 = $this -> lastredirectaddr;
            if(is_array($this -> results)){
                for($zym_37 = 0;$zym_37 < count($this -> results);$zym_37++) $this -> results[$zym_37] = $this -> _striplinks($this -> results[$zym_37]);
            }else $this -> results = $this -> _striplinks($this -> results);
            if($this -> expandlinks) $this -> results = $this -> _expandlinks($this -> results, $zym_40);
            return true;
        }else return false;
    }
    function fetchform($zym_40){
        if ($this -> fetch($zym_40)){
            if(is_array($this -> results)){
                for($zym_37 = 0;$zym_37 < count($this -> results);$zym_37++) $this -> results[$zym_37] = $this -> _stripform($this -> results[$zym_37]);
            }else $this -> results = $this -> _stripform($this -> results);
            return true;
        }else return false;
    }
    function fetchtext($zym_40){
        if($this -> fetch($zym_40)){
            if(is_array($this -> results)){
                for($zym_37 = 0;$zym_37 < count($this -> results);$zym_37++) $this -> results[$zym_37] = $this -> _striptext($this -> results[$zym_37]);
            }else $this -> results = $this -> _striptext($this -> results);
            return true;
        }else return false;
    }
    function submitlinks($zym_40, $zym_32 = "", $zym_34 = ""){
        if($this -> submit($zym_40, $zym_32, $zym_34)){
            if($this -> lastredirectaddr) $zym_40 = $this -> lastredirectaddr;
            if(is_array($this -> results)){
                for($zym_37 = 0;$zym_37 < count($this -> results);$zym_37++){
                    $this -> results[$zym_37] = $this -> _striplinks($this -> results[$zym_37]);
                    if($this -> expandlinks) $this -> results[$zym_37] = $this -> _expandlinks($this -> results[$zym_37], $zym_40);
                }
            }else{
                $this -> results = $this -> _striplinks($this -> results);
                if($this -> expandlinks) $this -> results = $this -> _expandlinks($this -> results, $zym_40);
            }
            return true;
        }else return false;
    }
    function submittext($zym_40, $zym_32 = "", $zym_34 = ""){
        if($this -> submit($zym_40, $zym_32, $zym_34)){
            if($this -> lastredirectaddr) $zym_40 = $this -> lastredirectaddr;
            if(is_array($this -> results)){
                for($zym_37 = 0;$zym_37 < count($this -> results);$zym_37++){
                    $this -> results[$zym_37] = $this -> _striptext($this -> results[$zym_37]);
                    if($this -> expandlinks) $this -> results[$zym_37] = $this -> _expandlinks($this -> results[$zym_37], $zym_40);
                }
            }else{
                $this -> results = $this -> _striptext($this -> results);
                if($this -> expandlinks) $this -> results = $this -> _expandlinks($this -> results, $zym_40);
            }
            return true;
        }else return false;
    }
    function set_submit_multipart(){
        $this -> _submit_type = 'multipart/form-data';
    }
    function set_submit_normal(){
        $this -> _submit_type = 'application/x-www-form-urlencoded';
    }
    function _striplinks($zym_36){
        preg_match_all('\'<\s*a\s.*?href\s*=\s*' . "\x9\x9\x9" . '# find <a href=' . "\xa\x9\x9\x9\x9\x9\x9" . '(["\'])?' . "\x9\x9\x9\x9\x9" . '# find single or double quote' . "\xa\x9\x9\x9\x9\x9\x9" . '(?(1) (.*?)\1 | ([^\s\>]+))' . "\x9\x9" . '# if quote found, match up to next matching' . "\xa\x9\x9\x9\x9\x9\x9\x9\x9\x9\x9\x9\x9\x9" . '# quote, otherwise match up to next space' . "\xa\x9\x9\x9\x9\x9\x9" . '\'isx', $zym_36, $zym_45);
        while(list($zym_46, $zym_56) = each($zym_45[2])){
            if(!empty($zym_56)) $zym_55[] = $zym_56;
        } while(list($zym_46, $zym_56) = each($zym_45[3])){
            if(!empty($zym_56)) $zym_55[] = $zym_56;
        }
        return $zym_55;
    }
    function _stripform($zym_36){
        preg_match_all('\'<\/?(FORM|INPUT|SELECT|TEXTAREA|(OPTION))[^<>]*>(?(2)(.*(?=<\/?(option|select)[^<>]*>[' . "\xd\xa" . ']*)|(?=[' . "\xd\xa" . ']*))|(?=[' . "\xd\xa" . ']*))\'Usi', $zym_36, $zym_58);
        $zym_55 = implode('' . "\xd\xa" . '', $zym_58[0]);
        return $zym_55;
    }
    function _striptext($zym_36){
        $zym_59 = array('\'<script[^>]*?>.*?</script>\'si', '\'<[\/\!]*?[^<>]*?>\'si', '\'([' . "\xd\xa" . '])[\s]+\'', '\'&(quot|#34|#034|#x22);\'i', '\'&(amp|#38|#038|#x26);\'i', '\'&(lt|#60|#060|#x3c);\'i', '\'&(gt|#62|#062|#x3e);\'i', '\'&(nbsp|#160|#xa0);\'i', '\'&(iexcl|#161);\'i', '\'&(cent|#162);\'i', '\'&(pound|#163);\'i', '\'&(copy|#169);\'i', '\'&(reg|#174);\'i', '\'&(deg|#176);\'i', '\'&(#39|#039|#x27);\'', '\'&(euro|#8364);\'i', '\'&a(uml|UML);\'', '\'&o(uml|UML);\'', '\'&u(uml|UML);\'', '\'&A(uml|UML);\'', '\'&O(uml|UML);\'', '\'&U(uml|UML);\'', '\'&szlig;\'i',);
        $zym_60 = array("", "", '\1', '"', '&', '<', '>', ' ', chr(161), chr(162), chr(163), chr(169), chr(174), chr(176), chr(39), chr(128), '?', '?', '?', '?', '?', '?', '?', '?',);
        $zym_57 = preg_replace($zym_59, $zym_60, $zym_36);
        return $zym_57;
    }
    function _expandlinks($zym_45, $zym_40){
        preg_match('/^[^\?]+/', $zym_40, $zym_55);
        $zym_55 = preg_replace('|/[^\/\.]+\.[^\/\.]+$|', "", $zym_55[0]);
        $zym_55 = preg_replace('|/$|', "", $zym_55);
        $zym_54 = parse_url($zym_55);
        $zym_48 = $zym_54['scheme'] . '://' . $zym_54['host'];
        $zym_59 = array('|^http://' . preg_quote($this -> host) . '|i', '|^(\/)|i', '|^(?!http://)(?!mailto:)|i', '|/\./|', '|/[^\/]+/\.\./|');
        $zym_60 = array("", $zym_48 . '/', $zym_55 . '/', '/', '/');
        $zym_47 = preg_replace($zym_59, $zym_60, $zym_45);
        return $zym_47;
    }
    function _httprequest($zym_49, $zym_43, $zym_40, $zym_50, $zym_52 = "", $zym_51 = ""){
        $zym_31 = '';
        if($this -> passcookies && $this -> _redirectaddr) $this -> setcookies();
        $zym_42 = parse_url($zym_40);
        if(empty($zym_49)) $zym_49 = '/';
        $zym_30 = $zym_50 . ' ' . $zym_49 . ' ' . $this -> _httpversion . '' . "\xd\xa" . '';
        if(!empty($this -> agent)) $zym_30 .= 'User-Agent: ' . $this -> agent . '' . "\xd\xa" . '';
        if(!empty($this -> host) && !isset($this -> rawheaders['Host'])){
            $zym_30 .= 'Host: ' . $this -> host;
            if(!empty($this -> port)) $zym_30 .= ':' . $this -> port;
            $zym_30 .= '' . "\xd\xa" . '';
        }
        if(!empty($this -> accept)) $zym_30 .= 'Accept: ' . $this -> accept . '' . "\xd\xa" . '';
        if(!empty($this -> referer)) $zym_30 .= 'Referer: ' . $this -> referer . '' . "\xd\xa" . '';
        if(!empty($this -> cookies)){
            if(!is_array($this -> cookies)) $this -> cookies = (array)$this -> cookies;
            reset($this -> cookies);
            if (count($this -> cookies) > 0){
                $zym_31 .= 'Cookie: ';
                foreach ($this -> cookies as $zym_10 => $zym_9){
                    $zym_31 .= $zym_10 . '=' . urlencode($zym_9) . '; ';
                }
                $zym_30 .= substr($zym_31, 0, -2) . '' . "\xd\xa" . '';
            }
        }
        if(!empty($this -> rawheaders)){
            if(!is_array($this -> rawheaders)) $this -> rawheaders = (array)$this -> rawheaders;
            while(list($zym_11, $zym_12) = each($this -> rawheaders)) $zym_30 .= $zym_11 . ': ' . $zym_12 . '' . "\xd\xa" . '';
        }
        if(!empty($zym_52)){
            $zym_30 .= "Content-type: $zym_52";
            if ($zym_52 == 'multipart/form-data') $zym_30 .= '; boundary=' . $this -> _mime_boundary;
            $zym_30 .= '' . "\xd\xa" . '';
        }
        if(!empty($zym_51)) $zym_30 .= 'Content-length: ' . strlen($zym_51) . '' . "\xd\xa" . '';
        if(!empty($this -> user) || !empty($this -> pass)) $zym_30 .= 'Authorization: Basic ' . base64_encode($this -> user . ':' . $this -> pass) . '' . "\xd\xa" . '';
        if(!empty($this -> proxy_user)) $zym_30 .= 'Proxy-Authorization: ' . 'Basic ' . base64_encode($this -> proxy_user . ':' . $this -> proxy_pass) . '' . "\xd\xa" . '';
        $zym_30 .= '' . "\xd\xa" . '';
        if ($this -> read_timeout > 0) socket_set_timeout($zym_43, $this -> read_timeout);
        $this -> timed_out = false;
        fwrite($zym_43, $zym_30 . $zym_51, strlen($zym_30 . $zym_51));
        $this -> _redirectaddr = false;
        unset($this -> headers);
        while($zym_14 = fgets($zym_43, $this -> _maxlinelen)){
            if ($this -> read_timeout > 0 && $this -> _check_timeout($zym_43)){
                $this -> status = -100;
                return false;
            }
            if($zym_14 == '' . "\xd\xa" . '') break;
            if(preg_match('/^(Location:|URI:)/i', $zym_14)){
                preg_match('/^(Location:|URI:)[ ]+(.*)/i', chop($zym_14), $zym_13);
                if(!preg_match('|\:\/\/|', $zym_13[2])){
                    $this -> _redirectaddr = $zym_42['scheme'] . '://' . $this -> host . ':' . $this -> port;
                    if(!preg_match('|^/|', $zym_13[2])) $this -> _redirectaddr .= '/' . $zym_13[2];
                    else $this -> _redirectaddr .= $zym_13[2];
                }else $this -> _redirectaddr = $zym_13[2];
            }
            if(preg_match('|^HTTP/|', $zym_14)){
                if(preg_match('|^HTTP/[^\s]*\s(.*?)\s|', $zym_14, $zym_8)){
                    $this -> status = $zym_8[1];
                }
                $this -> response_code = $zym_14;
            }
            $this -> headers[] = $zym_14;
        }
        $zym_7 = '';
        do{
            $zym_2 = fread($zym_43, $this -> maxlength);
            if (strlen($zym_2) == 0){
                break;
            }
            $zym_7 .= $zym_2;
        } while(true);
        if ($this -> read_timeout > 0 && $this -> _check_timeout($zym_43)){
            $this -> status = -100;
            return false;
        }
        if(preg_match('\'<meta[\s]*http-equiv[^>]*?content[\s]*=[\s]*["\']?\d+;[\s]*URL[\s]*=[\s]*([^"\']*?)["\']?>\'i', $zym_7, $zym_55)){
            $this -> _redirectaddr = $this -> _expandlinks($zym_55[1], $zym_40);
        }
        if(($this -> _framedepth < $this -> maxframes) && preg_match_all('\'<frame\s+.*src[\s]*=[\'"]?([^\'"\>]+)\'i', $zym_7, $zym_55)){
            $this -> results[] = $zym_7;
            for($zym_37 = 0; $zym_37 < count($zym_55[1]); $zym_37++) $this -> _frameurls[] = $this -> _expandlinks($zym_55[1][$zym_37], $zym_42['scheme'] . '://' . $this -> host);
        }elseif(is_array($this -> results)) $this -> results[] = $zym_7;
        else $this -> results = $zym_7;
        return true;
    }
    function _httpsrequest($zym_49, $zym_40, $zym_50, $zym_52 = "", $zym_51 = ""){
        if($this -> passcookies && $this -> _redirectaddr) $this -> setcookies();
        $zym_30 = array();
        $zym_42 = parse_url($zym_40);
        if(empty($zym_49)) $zym_49 = '/';
        if(!empty($this -> agent)) $zym_30[] = 'User-Agent: ' . $this -> agent;
        if(!empty($this -> host)) if(!empty($this -> port)) $zym_30[] = 'Host: ' . $this -> host . ':' . $this -> port;
            else $zym_30[] = 'Host: ' . $this -> host;
            if(!empty($this -> accept)) $zym_30[] = 'Accept: ' . $this -> accept;
            if(!empty($this -> referer)) $zym_30[] = 'Referer: ' . $this -> referer;
            if(!empty($this -> cookies)){
                if(!is_array($this -> cookies)) $this -> cookies = (array)$this -> cookies;
                reset($this -> cookies);
                if (count($this -> cookies) > 0){
                    $zym_1 = 'Cookie: ';
                    foreach ($this -> cookies as $zym_10 => $zym_9){
                        $zym_1 .= $zym_10 . '=' . urlencode($zym_9) . '; ';
                    }
                    $zym_30[] = substr($zym_1, 0, -2);
                }
            }
            if(!empty($this -> rawheaders)){
                if(!is_array($this -> rawheaders)) $this -> rawheaders = (array)$this -> rawheaders;
                while(list($zym_11, $zym_12) = each($this -> rawheaders)) $zym_30[] = $zym_11 . ': ' . $zym_12;
            }
            if(!empty($zym_52)){
                if ($zym_52 == 'multipart/form-data') $zym_30[] = "Content-type: $zym_52; boundary=" . $this -> _mime_boundary;
                else $zym_30[] = "Content-type: $zym_52";
            }
            if(!empty($zym_51)) $zym_30[] = 'Content-length: ' . strlen($zym_51);
            if(!empty($this -> user) || !empty($this -> pass)) $zym_30[] = 'Authorization: BASIC ' . base64_encode($this -> user . ':' . $this -> pass);
            for($zym_3 = 0; $zym_3 < count($zym_30); $zym_3++){
                $zym_4 = strtr($zym_30[$zym_3], '"', ' ');
                $zym_6 .= ' -H "' . $zym_4 . '"';
            }
            if(!empty($zym_51)) $zym_6 .= " -d \"$zym_51\"";
            if($this -> read_timeout > 0) $zym_6 .= ' -m ' . $this -> read_timeout;
            $zym_5 = tempnam($zym_15, 'sno');
            exec($this -> curl_path . " -k -D \"$zym_5\"" . $zym_6 . ' "' . escapeshellcmd($zym_40) . '"', $zym_7, $zym_16);
            if($zym_16){
                $this -> error = "Error: cURL could not retrieve the document, error $zym_16.";
                return false;
            }
            $zym_7 = implode('' . "\xd\xa" . '', $zym_7);
            $zym_26 = file("$zym_5");
            $this -> _redirectaddr = false;
            unset($this -> headers);
            for($zym_14 = 0; $zym_14 < count($zym_26); $zym_14++){
                if(preg_match('/^(Location: |URI: )/i', $zym_26[$zym_14])){
                    preg_match('/^(Location: |URI:)\s+(.*)/', chop($zym_26[$zym_14]), $zym_13);
                    if(!preg_match('|\:\/\/|', $zym_13[2])){
                        $this -> _redirectaddr = $zym_42['scheme'] . '://' . $this -> host . ':' . $this -> port;
                        if(!preg_match('|^/|', $zym_13[2])) $this -> _redirectaddr .= '/' . $zym_13[2];
                        else $this -> _redirectaddr .= $zym_13[2];
                    }else $this -> _redirectaddr = $zym_13[2];
                }
                if(preg_match('|^HTTP/|', $zym_26[$zym_14])) $this -> response_code = $zym_26[$zym_14];
                $this -> headers[] = $zym_26[$zym_14];
            }
            if(preg_match('\'<meta[\s]*http-equiv[^>]*?content[\s]*=[\s]*["\']?\d+;[\s]*URL[\s]*=[\s]*([^"\']*?)["\']?>\'i', $zym_7, $zym_55)){
                $this -> _redirectaddr = $this -> _expandlinks($zym_55[1], $zym_40);
            }
            if(($this -> _framedepth < $this -> maxframes) && preg_match_all('\'<frame\s+.*src[\s]*=[\'"]?([^\'"\>]+)\'i', $zym_7, $zym_55)){
                $this -> results[] = $zym_7;
                for($zym_37 = 0; $zym_37 < count($zym_55[1]); $zym_37++) $this -> _frameurls[] = $this -> _expandlinks($zym_55[1][$zym_37], $zym_42['scheme'] . '://' . $this -> host);
            }elseif(is_array($this -> results)) $this -> results[] = $zym_7;
            else $this -> results = $zym_7;
            unlink("$zym_5");
            return true;
        }
        function setcookies(){
            for($zym_37 = 0; $zym_37 < count($this -> headers); $zym_37++){
                if(preg_match('/^set-cookie:[\s]+([^=]+)=([^;]+)/i', $this -> headers[$zym_37], $zym_55)) $this -> cookies[$zym_55[1]] = urldecode($zym_55[2]);
            }
        }
        function _check_timeout($zym_43){
            if ($this -> read_timeout > 0){
                $zym_25 = socket_get_status($zym_43);
                if ($zym_25['timed_out']){
                    $this -> timed_out = true;
                    return true;
                }
            }
            return false;
        }
        function _connect(& $zym_43){
            if(!empty($this -> proxy_host) && !empty($this -> proxy_port)){
                $this -> _isproxy = true;
                $zym_27 = $this -> proxy_host;
                $zym_28 = $this -> proxy_port;
            }else{
                $zym_27 = $this -> host;
                $zym_28 = $this -> port;
            }
            $this -> status = 0;
            if($zym_43 = fsockopen($zym_27, $zym_28, $zym_29, $zym_24, $this -> _fp_timeout)){
                return true;
            }else{
                $this -> status = $zym_29;
                switch($zym_29){
                case -3: $this -> error = 'socket creation failed (-3)';
                case -4: $this -> error = 'dns lookup failure (-4)';
                case -5: $this -> error = 'connection refused or timed out (-5)';
                default: $this -> error = 'connection failed (' . $zym_29 . ')';
                }
                return false;
            }
        }
        function _disconnect($zym_43){
            return(fclose($zym_43));
        }
        function _prepare_post_body($zym_32, $zym_34){
            settype($zym_32, 'array');
            settype($zym_34, 'array');
            $zym_35 = '';
            if (count($zym_32) == 0 && count($zym_34) == 0) return;
            switch ($this -> _submit_type){
            case 'application/x-www-form-urlencoded': reset($zym_32);
                while(list($zym_46, $zym_56) = each($zym_32)){
                    if (is_array($zym_56) || is_object($zym_56)){
                        while (list($zym_23, $zym_18) = each($zym_56)){
                            $zym_35 .= urlencode($zym_46) . '[]=' . urlencode($zym_18) . '&';
                        }
                    }else $zym_35 .= urlencode($zym_46) . '=' . urlencode($zym_56) . '&';
                }
                break;
            case 'multipart/form-data': $this -> _mime_boundary = 'Snoopy' . md5(uniqid(microtime()));
                reset($zym_32);
                while(list($zym_46, $zym_56) = each($zym_32)){
                    if (is_array($zym_56) || is_object($zym_56)){
                        while (list($zym_23, $zym_18) = each($zym_56)){
                            $zym_35 .= '--' . $this -> _mime_boundary . '' . "\xd\xa" . '';
                            $zym_35 .= "Content-Disposition: form-data; name=\"$zym_46\[\]\"\r\n\r\n";
                            $zym_35 .= "$zym_18\r\n";
                        }
                    }else{
                        $zym_35 .= '--' . $this -> _mime_boundary . '' . "\xd\xa" . '';
                        $zym_35 .= "Content-Disposition: form-data; name=\"$zym_46\"\r\n\r\n";
                        $zym_35 .= "$zym_56\r\n";
                    }
                }
                reset($zym_34);
                while (list($zym_17, $zym_19) = each($zym_34)){
                    settype($zym_19, 'array');
                    while (list(, $zym_20) = each($zym_19)){
                        if (!is_readable($zym_20)) continue;
                        $zym_43 = fopen($zym_20, 'r');
                        $zym_22 = fread($zym_43, filesize($zym_20));
                        fclose($zym_43);
                        $zym_21 = basename($zym_20);
                        $zym_35 .= '--' . $this -> _mime_boundary . '' . "\xd\xa" . '';
                        $zym_35 .= "Content-Disposition: form-data; name=\"$zym_17\"; filename=\"$zym_21\"\r\n\r\n";
                        $zym_35 .= "$zym_22\r\n";
                    }
                }
                $zym_35 .= '--' . $this -> _mime_boundary . '--' . "\xd\xa" . '';
                break;
            }
            return $zym_35;
        }
    }
    ?>