<?php
class dataokeAction extends BackendAction 
{
	public function _initialize() 
	{
		parent::_initialize();
	}
	public function index()
	{
		$zym_68 = C('ftx_dtk');
		if(!$zym_68)
		{
            $zym_67 = '';
		}
		else
		{
            $zym_67 = '';
		}
		$this->assign('info', $zym_67);
		$this->display();
	}
	public function setting()
	{
		if(IS_POST)
		{
			$zym_66 = C('ftx_fz');
			$zym_70 = I('page','','intval');
			if(!$zym_66)
			{
				$this->ajaxReturn(0, '请先在采集设置中绑定分类ID!');
			}
			F('dataoke_setting', array( 'page' => $zym_70, ));
			$this->collect();
		}
	}
	public function collect() 
	{
		if (false === $zym_71 = F('dataoke_setting')) 
		{
			$this->ajaxReturn(0, L('illegal_parameters'));
		}
		$zym_75 = check_cookies('http://pub.alimama.com/common/getUnionPubContextInfo.json');
		if(!$zym_75)
		{
			$this->ajaxReturn(0, '登陆超时！请重新获取cookies，否则获取不到推广链接。');
		}
		$zym_70 = $zym_71['page'];
		$zym_74 = array( 'dataoke_file'=> FTX_DATA_PATH.'cookies/dataoke2.txt', );
		$zym_73 = $this->_get('p', 'intval', $zym_70);
		if($zym_73==1)
		{
			$zym_72 = 0;
		}
		else
		{
			if(F('totalcoll'))
			{
				$zym_72 = F('totalcoll');
			}
			else
			{
				$zym_72 = 0;
			}
		}
		$zym_65 = file_get_contents($zym_74['dataoke_file']);
		if(!$zym_65 && $zym_65 = '操作异常！')
		{
			$this->ajaxReturn(0, '请先获取数据。');
		}
		else
		{
			$zym_64 = json_decode($zym_65,true);
			$zym_57 = count($zym_64);
			$zym_56 = $zym_73 - 1;
			$zym_55 = $zym_56*1;
			$zym_53 = $zym_73*1;
			$zym_54=0;
			if($zym_57)
			{
				for($zym_58=$zym_55;$zym_58<$zym_53;$zym_58++)
				{
					$zym_59 = $zym_64[$zym_58]['Org_Price'];
					$zym_63 = $zym_64[$zym_58]['Price'];
					$zym_62 = $zym_64[$zym_58]['IsTmall'];
					if($zym_62)
					{
						$zym_61 = 'B';
					}
					else
					{
						$zym_61 = 'C';
					}
					$zym_60 = $zym_64[$zym_58]['Quan_time'];
					$zym_60 = str_replace('00:00:00','',$zym_60);
					$zym_76 = $zym_64[$zym_58]['Quan_surplus'];
					$zym_77 = $zym_64[$zym_58]['Quan_receive'];
					$zym_94 = $zym_64[$zym_58]['Quan_price'];
					$zym_93 = $zym_64[$zym_58]['Quan_condition'];
					$zym_92 = $zym_64[$zym_58]['SellerID'];
					$zym_91 =$zym_64[$zym_58]['Cid'];
					$zym_95 =$zym_64[$zym_58]['GoodsID'];
					$zym_96 = $zym_64[$zym_58]['Quan_id'];
					$zym_101 = $zym_64[$zym_58]['Sales_num'];
					$zym_99 =$zym_64[$zym_58]['Introduce'];
					$zym_98 =$zym_64[$zym_58]['Pic'];
					$zym_97 =$zym_64[$zym_58]['Title'];
					$zym_100 = $zym_64[$zym_58]['Commission'];
					$zym_90 = $zym_64[$zym_58]['Commission_jihua'];
					$zym_82 = $zym_64[$zym_58]['Commission_queqiao'];
					$zym_81 = $zym_64[$zym_58]['Jihua_link'];
					if($zym_81)
					{
						$zym_80 = $this->getjihua($zym_92);
						$zym_78 = $this->getclick($zym_95);
					}
					else
					{
						if($zym_82)
						{
							$zym_78 = $this->getqueqiao($zym_95);
						}
						else
						{
							$zym_78 = $this->getclick($zym_95);
						}
					}
					$zym_79 = 'http://taoquan.taobao.com/coupon/unify_apply.htm?sellerId='.$zym_92.'&activityId='.$zym_96;
					$zym_83 = 'http://h5.m.taobao.com/ump/coupon/detail/index.html?sellerId='.$zym_92.'&activityId='.$zym_96;
					$zym_84 = round(($zym_63/$zym_59)*10,1);
					$zym_88 = C('ftx_coupon_add_time');
					if($zym_88)
					{
						$zym_87 = (int)(time()+$zym_88*3600);
					}
					else
					{
						$zym_87 = (int)(time()+72*86400);
					}
					if($zym_91==1)
					{
						$zym_66 = C('ftx_fz');
					}
					if($zym_91==2)
					{
						$zym_66 = C('ftx_my');
					}
					if($zym_91==3)
					{
						$zym_66 = C('ftx_hzp');
					}
					if($zym_91==4)
					{
						$zym_66 = C('ftx_jj');
					}
					if($zym_91==5)
					{
						$zym_66 = C('ftx_xbps');
					}
					if($zym_91==6)
					{
						$zym_66 = C('ftx_ms');
					}
					if($zym_91==7)
					{
						$zym_66 = C('ftx_wtcp');
					}
					if($zym_91==8)
					{
						$zym_66 = C('ftx_smjd');
					}
					$zym_86 = d('items')->get_tags_by_title($zym_97);
					$zym_85 = implode(',',$zym_86);
					$zym_52['item_type']=2;
					$zym_52['q_sur']=$zym_76;
					$zym_52['q_has']=$zym_77;
					$zym_52['q_price']=$zym_94;
					$zym_52['q_time']=$zym_60;
					$zym_52['pc_url']=$zym_79;
					$zym_52['wap_url']=$zym_83;
					$zym_52['q_info']=$zym_93;
					$zym_52['shop_type']=$zym_61;
					$zym_52['tags']=$zym_85;
					$zym_52['price']=$zym_59;
					$zym_52['volume']=$zym_101;
					$zym_52['desc']= getdesc($zym_95);
					$zym_52['intro']=$zym_99;
					$zym_52['coupon_rate']=$zym_84*1000;
					$zym_52['sellerId']=$zym_92;
					$zym_52['title']=$zym_97;
					$zym_52['click_url']=$zym_78;
					$zym_52['num_iid']= $zym_95;
					$zym_52['pic_url']=$zym_98;
					$zym_52['coupon_price']=$zym_63;
					$zym_52['cate_id']=$zym_66;
					$zym_52['coupon_end_time']=$zym_87;
					;
					$zym_52['coupon_start_time']=time();
					if($zym_95 && $zym_66 && $zym_76)
					{
						$zym_51['item_list'][]=$zym_52;
					}
				}
			}
			if($zym_73>$zym_57)
			{
				$this->ajaxReturn(0, '已经采集完成！请返回，谢谢');
			}
			$zym_54=0;
			foreach ($zym_51['item_list'] as $zym_18 => $zym_17) 
			{
				$zym_16= $this->_ajax_tb_publish_insert($zym_17);
				if($zym_16>0)
				{
					$zym_54++;
				}
				$zym_72++;
			}
			F('totalcoll',$zym_72);
			$this->assign('p',$zym_73);
			$this->assign('totalnum', $zym_57);
			$this->assign('totalcoll', $zym_72);
			$zym_14 = $this->fetch('collect');
			$this->ajaxReturn(1, '', $zym_14);
		}
	}
	private function _ajax_tb_publish_insert($zym_15) 
	{
		$zym_15['title']=trim(strip_tags($zym_15['title']));
		$zym_51 = D('items')->ajax_tb_publish($zym_15);
		return $zym_51;
	}
	public function getjihua($zym_92)
	{
		$zym_19 = C('ftx_cookie');
		$zym_20 = array(' ','　','' . "\xa" . '','' . "\xd" . '','' . "\x9" . '');
		$zym_24 = array("","","","","");
		$zym_19 = str_replace($zym_20, $zym_24, $zym_19);
		$zym_23 =get_word($zym_19,'_tb_token_=',';');
		$zym_22 = get_word($zym_19,'t=',';');
		$zym_21 = get_word($zym_19,'cna=',';');
		$zym_13 = get_word($zym_19,'l=',';');
		$zym_12 = get_word($zym_19,'mm-guidance3',';');
		$zym_5 = get_word($zym_19,'_umdata=',';');
		$zym_4 = get_word($zym_19,'cookie2=',';');
		$zym_3 = get_word($zym_19,'cookie32=',';');
		$zym_1 = get_word($zym_19,'cookie31=',';');
		$zym_2 = get_word($zym_19,'alimamapwag=',';');
		$zym_6 = get_word($zym_19,'login=',';');
		$zym_7 = get_word($zym_19,'alimamapw=',';');
		$zym_11 = 't='.$zym_22.';cna='.$zym_21.';l='.$zym_13.';mm-guidance3='.$zym_12.';_umdata='.$zym_5.';cookie2='.$zym_4.';_tb_token_='.$zym_23.';v=0;cookie32='.$zym_3.';cookie31='.$zym_1.';alimamapwag='.$zym_2.';login='.$zym_6.';alimamapw='.$zym_7;
		$zym_10 =microtime(true)*1000;
		$zym_10 = explode('.', $zym_10);
		$zym_9 = '爱挑挑网站联盟万人齐推广，希望申请定向计划佣金，长期在线招商QQ：1259814898';
		$zym_8 = 'http://pub.alimama.com/pubauc/getCommonCampaignDetails.json?oriMemberid='.$zym_92.'&t='.$zym_10[0].'&_tb_token_='.$zym_23.'&_input_charset=utf-8';
		$zym_25 = curl_init();
		curl_setopt($zym_25, CURLOPT_URL, $zym_8);
		curl_setopt($zym_25, CURLOPT_REFERER, 'http://www.alimama.com/index.htm');
		curl_setopt($zym_25, CURLOPT_HTTPHEADER, array( 'Cookie:{'.$zym_11.'}', ));
		curl_setopt($zym_25, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($zym_25, CURLOPT_FOLLOWLOCATION, 1);
		$zym_65 = curl_exec($zym_25);
		curl_close($zym_25);
		$zym_26 = json_decode($zym_65,true);
		$zym_44 = count($zym_26['data']['campaignList']);
		if($zym_44==1)
		{
			$zym_43 = $zym_26['data']['campaignList'][0]['CampaignID'];
			$zym_42 = $zym_26['data']['campaignList'][0]['ShopKeeperID'];
			$zym_40 = $zym_26['data']['campaignList'][0]['Exist'];
		}
		else
		{
			for($zym_58=0;$zym_58<$zym_44;$zym_58++)
			{
				$zym_41 = $zym_58+1;
				if($zym_41==$zym_44)
				{
					$zym_45 .= $zym_26['data']['campaignList'][$zym_58]['AvgCommission']*100;
				}
				else
				{
					$zym_45 .= ($zym_26['data']['campaignList'][$zym_58]['AvgCommission']*100).',';
				}
			}
			$zym_45 = str_replace($zym_20, $zym_24, $zym_45);
			$zym_46 = explode(',', $zym_45);
			$zym_50 = array_search(max($zym_46), $zym_46);
			$zym_43 = $zym_26['data']['campaignList'][$zym_50]['CampaignID'];
			$zym_42 = $zym_26['data']['campaignList'][$zym_50]['ShopKeeperID'];
			$zym_40 = $zym_26['data']['campaignList'][$zym_50]['Exist'];
		}
		if(!$zym_40)
		{
			$zym_49 = 'http://pub.alimama.com/pubauc/applyForCommonCampaign.json';
			$zym_48 = array( '_tb_token_'=>$zym_23, 'applyreason'=>$zym_9, 'campId'=>$zym_43, 'keeperid'=>$zym_42, 't'=>$zym_10[0], );
			$zym_48 = http_build_query($zym_48);
			$zym_47 = curl_init();
			$zym_39 = 500;
			curl_setopt($zym_47, CURLOPT_URL, $zym_49);
			curl_setopt($zym_47, CURLOPT_REFERER, 'http://www.alimama.com/index.htm');
			curl_setopt($zym_47, CURLOPT_POST, true);
			curl_setopt($zym_47, CURLOPT_HTTPHEADER, array( 'Cookie:{'.$zym_11.'}', ));
			curl_setopt($zym_47, CURLOPT_POSTFIELDS, $zym_48);
			curl_setopt($zym_47, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($zym_47, CURLOPT_CONNECTTIMEOUT, $zym_39);
			$zym_38 = curl_exec($zym_47);
			curl_close($zym_47);
		}
		return $zym_38;
	}
	public function getqueqiao($zym_95)
	{
		$zym_19 = C('ftx_cookie');
		$zym_20 = array(' ','　','' . "\xa" . '','' . "\xd" . '','' . "\x9" . '');
		$zym_24 = array("","","","","");
		$zym_19 = str_replace($zym_20, $zym_24, $zym_19);
		$zym_23 =get_word($zym_19,'_tb_token_=',';');
		$zym_22 = get_word($zym_19,'t=',';');
		$zym_21 = get_word($zym_19,'cna=',';');
		$zym_13 = get_word($zym_19,'l=',';');
		$zym_12 = get_word($zym_19,'mm-guidance3',';');
		$zym_5 = get_word($zym_19,'_umdata=',';');
		$zym_4 = get_word($zym_19,'cookie2=',';');
		$zym_3 = get_word($zym_19,'cookie32=',';');
		$zym_1 = get_word($zym_19,'cookie31=',';');
		$zym_2 = get_word($zym_19,'alimamapwag=',';');
		$zym_6 = get_word($zym_19,'login=',';');
		$zym_7 = get_word($zym_19,'alimamapw=',';');
		$zym_11 = 't='.$zym_22.';cna='.$zym_21.';l='.$zym_13.';mm-guidance3='.$zym_12.';_umdata='.$zym_5.';cookie2='.$zym_4.';_tb_token_='.$zym_23.';v=0;cookie32='.$zym_3.';cookie31='.$zym_1.';alimamapwag='.$zym_2.';login='.$zym_6.';alimamapw='.$zym_7;
		$zym_10 =microtime(true)*1000;
		$zym_10 = explode('.', $zym_10);
		$zym_31 = get_client_ip();
		$zym_30 = '19_'.$zym_31.'_366_1468693605455';
		$zym_29 = C('ftx_youpid');
		$zym_27 = explode('_',$zym_29);
		$zym_28 = $zym_27[2];
		$zym_32 = $zym_27[3];
		$zym_33 = 'http://pub.alimama.com/common/code/getAuctionCode.json?auctionid='.$zym_95.'&adzoneid='.$zym_32.'&siteid='.$zym_28.'&scenes=3&channel=tk_qqhd&t='.$zym_10[0].'&_tb_token_='.$zym_23.'&pvid='.$zym_30;
        $zym_37 = curl_init();
		curl_setopt($zym_37, CURLOPT_URL, $zym_33);
		curl_setopt($zym_37, CURLOPT_REFERER, 'http://pub.alimama.com/promo/item/channel/index.htm?channel=qqhd');
		curl_setopt($zym_37, CURLOPT_HTTPHEADER, array( 'Cookie:{'.$zym_11.'}', ));
		curl_setopt($zym_37, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($zym_37, CURLOPT_FOLLOWLOCATION, 1);
		$zym_36 = curl_exec($zym_37);
		curl_close($zym_37);
		$zym_35 = json_decode($zym_36,true);
		if($zym_35)
		{
			$zym_34 = $zym_35['data']['shortLinkUrl'];
		}
		return $zym_34;
	}
	public function getclick($zym_95)
	{
		$zym_19 = C('ftx_cookie');
		$zym_20 = array(' ','　','' . "\xa" . '','' . "\xd" . '','' . "\x9" . '');
		$zym_24 = array("","","","","");
		$zym_19 = str_replace($zym_20, $zym_24, $zym_19);
		$zym_23 =get_word($zym_19,'_tb_token_=',';');
		$zym_22 = get_word($zym_19,'t=',';');
		$zym_21 = get_word($zym_19,'cna=',';');
		$zym_13 = get_word($zym_19,'l=',';');
		$zym_12 = get_word($zym_19,'mm-guidance3',';');
		$zym_5 = get_word($zym_19,'_umdata=',';');
		$zym_4 = get_word($zym_19,'cookie2=',';');
		$zym_3 = get_word($zym_19,'cookie32=',';');
		$zym_1 = get_word($zym_19,'cookie31=',';');
		$zym_2 = get_word($zym_19,'alimamapwag=',';');
		$zym_6 = get_word($zym_19,'login=',';');
		$zym_7 = get_word($zym_19,'alimamapw=',';');
		$zym_11 = 't='.$zym_22.';cna='.$zym_21.';l='.$zym_13.';mm-guidance3='.$zym_12.';_umdata='.$zym_5.';cookie2='.$zym_4.';_tb_token_='.$zym_23.';v=0;cookie32='.$zym_3.';cookie31='.$zym_1.';alimamapwag='.$zym_2.';login='.$zym_6.';alimamapw='.$zym_7;
		$zym_10 =microtime(true)*1000;
		$zym_10 = explode('.', $zym_10);
		$zym_29 = C('ftx_youpid');
		$zym_27 = explode('_',$zym_29);
		$zym_28 = $zym_27[2];
		$zym_32 = $zym_27[3];
		$zym_31 = get_client_ip();
		$zym_30 = '50_'.$zym_31.'_15881_1468693605455';
		$zym_33 = 'http://pub.alimama.com/common/code/getAuctionCode.json?auctionid='.$zym_95.'&adzoneid='.$zym_32.'&siteid='.$zym_28.'&t='.$zym_10[0].'&pvid='.$zym_30.'&_tb_token_='.$zym_23.'&_input_charset=utf-8';
        $zym_37 = curl_init();
		curl_setopt($zym_37, CURLOPT_URL, $zym_33);
		curl_setopt($zym_37, CURLOPT_REFERER, 'http://www.alimama.com/index.htm');
		curl_setopt($zym_37, CURLOPT_HTTPHEADER, array( 'Cookie:{'.$zym_11.'}', ));
		curl_setopt($zym_37, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($zym_37, CURLOPT_FOLLOWLOCATION, 1);
		$zym_36 = curl_exec($zym_37);
		curl_close($zym_37);
		$zym_35 = json_decode($zym_36,true);
		$zym_78 = $zym_35['data']['shortLinkUrl'];
		return $zym_78;
	}
}
?>