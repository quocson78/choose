<?php
class Choose_Controller_Action_Default extends Choose_Controller_Action
{
	protected $arr_category_head = array(
					'esthe' => 'エステティシャン',
					'nail' => 'ネイルサロン',
					'eyelist' => 'まつげエクステ',
					'biyoshi' => '美容室',
					'biyobuin' => 'コスメ',
					'therapist' => 'セラピスト',
					'aroma' => 'アロマセラピー',
					'massage' => 'マッサージ',
					'reflexology' => 'リフレクソロジスト',
					'seitai' => '整体',
					'chiropractic' => 'カイロプラクター',
					'jusei' => '整骨院・接骨院',
					'massageshi' => 'あん摩マッサージ指圧師',
					'shinkyu' => '鍼灸',
					'yoga' => 'ヨガインストラクター',
					'fitness' => 'スポーツインストラクター',
	                'all'    => 'リラクゼーション'
				);
	public function init()
	{
	    
	    
		$ua = new Zend_Http_UserAgent;
		$device = $ua->getDevice();

		if ($device->getFeature('is_mobile')
				|| $device->getFeature('is_tablet')) {
			$this->_deviceSuffix = 'mobile';
		} else {
			$this->_deviceSuffix = 'pc';
		}

		if ($this->_deviceSuffix == 'pc'){
			 
			$this->view->assign('urlImg', '/pc/images');
			 
			$this->_setRegions();

			$this->_setMenu_PC();

		}
		
		parent::init();
	}

	protected function _setRegions(){
		$obj = new App_Model_Category();
		$regions = $obj->getAllRegion();
		 
		$regions[0]['name'] = '北海度・東北';
		$regions[0]['prefs'] = array_merge($regions[0]['prefs'], $regions[1]['prefs']);
		unset($regions[1]);
		 
		$regions[5]['name'] = '中国・四国';
		$regions[5]['prefs'] = array_merge($regions[5]['prefs'], $regions[6]['prefs']);
		unset($regions[6]);
		 
		$regions[7]['name'] = '九州・沖縄';
		$regions[7]['prefs'] = array_merge($regions[7]['prefs'], $regions[8]['prefs']);
		unset($regions[8]);
		$tmp_region = array();
		foreach ($regions as $v){
			$tmp_region[] = $v;
		}
		$this->view->assign('regions', $tmp_region);
		$arr_cities = array(
				"北海道" => "札幌/琴似/麻生/新さっぽろ/白石駅",
				"宮城県" => "仙台,泉中央,あおば通,勾当台公園,長町駅",
				"福島県" => "郡山,福島,いわき,会津若松,新白河駅",
				"青森県" => "青森,八戸,弘前,本八戸,三沢駅",
				"岩手県" => "盛岡,一ノ関,北上,花巻,矢幅駅",
				"山形県" => "山形,かみのやま温泉,天童,新庄,酒田駅",
				"秋田県" => "秋田,大曲,土崎,追分,横手駅",
				"愛知県" => "名古屋,金山,名鉄名古屋,栄,大曽根駅",
				"静岡県" => "静岡,三島,浜松,沼津,草薙駅",
				"新潟県" => "新潟,長岡,六日町,六日町,白山駅",
				"長野県" => "長野,上諏訪,塩尻,茅野,須坂駅",
				"岐阜県" => "岐阜,名鉄岐阜,多治見,穂積,土岐市駅",
				"石川県" => "金沢,北鉄金沢,小松,松任,新西金沢駅",
				"富山県" => "富山,富山駅前,電鉄富山,富山駅北,高岡駅",
				"山梨県" => "甲府,上野原,石和温泉,韮崎,竜王駅",
				"福井県" => "福井,福井駅前,敦賀,武生,芦原温泉駅",
				"広島県" => "広島,福山,横川,呉,五日市駅",
				"岡山県" => "岡山,岡山駅前,倉敷,新倉敷,中庄駅",
				"山口県" => "下関,新山口,徳山,岩国,新下関駅",
				"島根県" => "松江,出雲市,電鉄出雲市,松江しんじ湖温泉,浜田駅",
				"鳥取県" => "米子,三本松口,鳥取,青谷,因幡社駅",
				"愛媛県" => "松山市,松山市駅前,松山,松山駅前,三津駅",
				"香川県" => "高松,坂出,丸亀,宇多津,多度津駅",
				"徳島県" => "徳島,阿南,阿波池田,鳴門,板野駅",
				"高知県" => "高知,高知駅前,後免,はりまや橋,朝倉駅",
				"大阪府" => "梅田,大阪,難波,なんば,天王寺駅",
				"兵庫県" => "三宮,三ノ宮,神戸,尼崎,元町駅",
				"京都府" => "京都,山科,四条,烏丸,河原町駅",
				"三重県" => "近鉄四日市,名張,白子,津新町,久居駅",
				"奈良県" => "王寺,新王寺,学園前,近鉄奈良,大和西大寺駅",
				"滋賀県" => "草津,石山,南草津,大津,近江八幡駅",
				"和歌山県" => "和歌山,林間田園都市,六十谷,紀伊,海南駅",
				"東京都" => "新宿,池袋,渋谷,東京,品川駅",
				"神奈川県" => "横浜,藤沢,川崎,登戸,戸塚駅",
				"埼玉県" => "大宮,大宮,川越,川口,浦和駅",
				"千葉県" => "柏,船橋,松戸,千葉,津田沼駅",
				"茨城県" => "取手,守谷,土浦,牛久,古河駅",
				"群馬県" => "高崎,前橋,伊勢崎,新前橋,館林駅",
				"栃木県" => "宇都宮/小山/栃木/野木/東武宇都宮駅",
				"福岡県" => "西鉄福岡,天神,博多,薬院,西鉄久留米駅",
				"熊本県" => "熊本,熊本駅前,新水前寺,水前寺駅通,上熊本駅",
				"鹿児島県" => "鹿児島中央,鹿児島中央駅前,坂之上,谷山,国分駅",
				"長崎県" => "長崎,長崎駅前,諫早,浦上,浦上駅前駅",
				"大分県" => "大分,中津,別府,亀川,日田駅",
				"宮崎県" => "宮崎,南宮崎,日向市,都城,佐土原駅",
				"佐賀県" => "佐賀,鳥栖,基山,唐津,神埼駅",
				"沖縄県" => "県庁前,那覇空港,おもろまち,小禄,首里駅"
		);
		$this->view->assign('arr_cities', $arr_cities);
	}

	protected function _setMenu_PC(){
		$str_header_title = '美容・治療・リラクゼーションのお仕事ポータル'; // ヘッダーにページのタイトルを表示
		$url_header_lnk = '/'; // ヘッダーにURLリンク
		$obj = new App_Model_Category();
		$controller_name = $this->getRequest()->getControllerName();
		 
		$arr_item_menu = array(
				"エステ"			=> "g_navi01",
				"ネイル" 			=> "g_navi02",
				"美容師" 		=> "g_navi03",
				"アイリスト" 		=> "g_navi04",
				"美容部員" 		=> "g_navi05",
				"マッサージ" 		=> "g_navi06",
				"リフレ" 			=> "g_navi07",
				"セラピスト" 		=> "g_navi08",
				"アロマ" 			=> "g_navi09",
				"整体師" 		=> "g_navi10",
				"カイロ" 			=> "g_navi11",
				"マッサージ師" 		=> "g_navi12",
				"柔道整体師" 	=> "g_navi13",
				"鍼灸師" 		=> "g_navi14",
				"ヨガ" 			=> "g_navi15",
				"フィットネス" 		=> "g_navi16"
		);
		$this->view->assign('arr_item_menu', $arr_item_menu);
		$data_menu = $obj->getGyousu();
		$category = $this->getParam('category', 'all');
		
		if ($controller_name == 'category'){
			if ($category != 'all'){ // カテゴリがある場合には
				$data_menu = $obj->getGyousu();
				 
				$tag_info = $obj->getByUrl($category);

				$data_menu = $obj->_sortData($data_menu, $tag_info['tag_id']);
				 
				$this->view->assign('data_menu', $data_menu);
				 
				$str_search_msg = $tag_info['contents'].'|'.$this->arr_category_head[$tag_info['url']];
				$url_header_lnk = '/'.$tag_info['url'];
			}else{
				$str_search_msg = '全業種/'.$this->arr_category_head[$category];
				$url_header_lnk = '/all';
			}
			$this->view->assign('category', $category);

			$str_url = array();
			$str_url[] = array('', $str_search_msg.'の求人');
			$str_header_title = $str_search_msg.'の求人・転職・募集';
			
			$this->view->assign('arr_url', $str_url);
		}if ($controller_name == 'search'){
			$obj_search = new App_Model_Search();

			$prefecture = $this->getParam('prefecture');
			$pref = $obj_search->getPrefByName($prefecture);
			
			if ($category != 'all'){
				$tag_info = $obj->getByUrl($category);
				$str_header_title = $pref['name'].'の'.$tag_info['contents'].'|'.$this->arr_category_head[$tag_info['url']].'の求人・転職・募集';
				$url_header_lnk = '/'.$tag_info['url'];
			}else{
				$str_header_title = $pref['name'].'の全業種|'.$this->arr_category_head[$category].'の求人・転職・募集';
				$url_header_lnk = '/all';
			}
		}elseif ($controller_name == 'shop' || $controller_name == 'offer'){
		    $url_header_lnk = '/all';
		}
		$this->view->assign('data_menu', $data_menu);
		$this->view->assign('str_header_title', $str_header_title);
		$this->view->assign('url_header_lnk', $url_header_lnk);
		$this->view->headTitle($str_header_title);
	}
}
