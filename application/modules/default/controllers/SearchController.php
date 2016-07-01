<?php
class Default_SearchController extends Choose_Controller_Action_Default
{
    protected $obj;
    public $numItemPerPage = 2; // ページごとの項目数
    public $search_condition = array(
			    		'prefecture' => '',
			    		'city' => '',
			    		'keyword' => '',
			    		'line' => '',
			    		'station' => '',
			    		'categories' => array(),
			    		'features' => array(),
			    		'kinds' => array(),
			    		'salary_fr' => '',
			    		'salary_to' => '',
			    		'age' => '',
			    		'sex' => '',
			    		'experience' => ''
			    );
    public $category = 'all'; // カテゴリ
    public $str_search_msg = ''; // 検索条件の文字列
    
	public function init()
	{
	    $this->obj = new App_Model_Search();
	    
	    $cat = $this->getRequest()->getParam('category', 'all');
	    if ($cat != 'all'){
	        $rs_tag = $this->obj->getTagByUrl($cat);
	        $parent_tag_id = $rs_tag['parent_id']; 
	    }else {
	        $parent_tag_id = 1;
	    }
	    $this->view->assign('parent_tag_id', $parent_tag_id);
	    
		parent::init();
	}
	
	public function indexAction(){
		$this->_setDataSearchForm();
		
		
		$params = $this->getRequest()->getParams();
		if (isset($params['prefecture']) && $params['prefecture']!=''){
			$rs_pref = $this->obj->getPrefByName($params['prefecture']);
			$this->str_search_msg .= $rs_pref['name'].' ';
			
			$this->search_condition['prefecture'] = $rs_pref['pref_id'];
			
			$cities = $this->obj->getCitiesByPrefName($params['prefecture']);
			$this->view->assign('cities', $cities);
		}
		
		if (isset($params['lines']) && $params['lines']!=''){
			$rs_line = $this->obj->getLineByLineName($params['lines']);
			$this->str_search_msg .= $rs_line['name'].' ';
			
			$this->search_condition['line'] = $rs_line['line_id'];
			
			$lines = $this->obj->getAllLinesByLineId($rs_line['line_id']);
			$this->view->assign('lines', $lines);
			
			$stations = $this->obj->getAllStationsByLineId($rs_line['line_id']);
			$this->view->assign('stations', $stations);
		}
		
		if (isset($params['cities']) && $params['cities']!=''){
			$this->search_condition['city'] = $params['cities'];
			
			$rs_city = $this->obj->getCityByCityCode($params['cities']);
			$this->str_search_msg .= $rs_city['city_name'].' ';
		}
		
		if (isset($params['stations']) && $params['stations']!=''){
			$this->search_condition['station'] = $params['stations'];
			
			$rs_station = $this->obj->getStationByStationId($params['stations']);
			$this->str_search_msg .= $rs_station['station_name'].' ';
		}
		
		if (isset($params['keywords']) && $params['keywords']!=''){
			$this->search_condition['keyword'] = $params['keywords'];
		}
		
		if (isset($params['category'])){// Categoryページからの業種
			$rs_category = $this->obj->getTagByUrl($params['category']);
			$params['categories'][$rs_category['tag_id']] = $rs_category['tag_id'];
			$this->category = $params['category'];
		}
		
		if (isset($params['categories']) && is_array($params['categories'])){// 検索フォームからの業種
			foreach ($params['categories'] as $k=>$v){
				$rs_tag = $this->obj->getTagByTagId($v);
				$this->str_search_msg .= $rs_tag['contents'].' ';
			}
			$categories = $params['categories'];
			$params['categories'] = array_flip($categories);
			
			$this->search_condition['categories'] = $params['categories'];
		}
		
		if (isset($params['features']) && is_array($params['features'])){
			foreach ($params['features'] as $k=>$v){
				$rs_tag = $this->obj->getTagByTagId($v);
				$this->str_search_msg .= $rs_tag['contents'].' ';
			}
			$features = $params['features'];
			$params['features'] = array_flip($features);
			
			$this->search_condition['features'] = $params['features'];
		}
		
		if (isset($params['kinds']) && is_array($params['kinds'])){
			foreach ($params['tag_kinds'] as $k=>$v){
				$rs_tag = $this->obj->getTagByTagId($v);
				$this->str_search_msg .= $rs_tag['contents'].' ';
			}
			$kinds = $params['kinds'];
			$params['kinds'] = array_flip($kinds);
			
			$this->search_condition['kinds'] = $params['kinds'];
		}
		
		if (isset($params['salary_fr']) && ($params['salary_fr']!='')){
			$rs_tag = $this->obj->getTagByTagId($params['salary_fr']);
			//$this->str_search_msg .= $rs_tag['contents'].'以上  ';
			
			$this->search_condition['salary_fr'] = $params['salary_fr'];
		}
		
		if (isset($params['salary_to']) && ($params['salary_to']!='')){			
			$this->search_condition['salary_to'] = $params['salary_to'];
		}
		
		if (isset($params['age']) && ($params['age']!='')){
			$rs_tag = $this->obj->getTagByTagId($params['age']);
			//$this->str_search_msg .= $rs_tag['contents'].'歳  ';
			$this->search_condition['age'] = $params['age'];
		}
		
		if (isset($params['sex']) && ($params['sex']!='')){
			$rs_tag = $this->obj->getTagByTagId($params['sex']);
			//$this->str_search_msg .= $rs_tag['contents'].' ';
			
			$this->search_condition['sex'] = $params['sex'];
		}
		
		if (isset($params['experience']) && ($params['experience']!='')){
			$rs_tag = $this->obj->getTagByTagId($params['experience']);
			//$this->str_search_msg .= $rs_tag['contents'].' ';
			
			$this->search_condition['experience'] = $params['experience'];
		}
		
		$this->_setDataPaging($params, 'search');
	}
	/**
	 * 
	 * @param string $kind(feature, line, station, area, search)
	 */
	public function _setDataPaging($params, $kind=''){
		$rs_search = $this->obj->getOfferBySearch($this->search_condition);
		$rs_search = $this->_setDataForView($rs_search);
		
		$paginator = Zend_Paginator::factory($rs_search);
		$paginator->setCurrentPageNumber(isset($params['page'])? $params['page'] : 1);
		$paginator->setItemCountPerPage($this->numItemPerPage);
		$this->view->assign('rs_count', $paginator->getTotalItemCount());
		$item_index = array(
				'from' => ($paginator->getCurrentPageNumber()-1)*$this->numItemPerPage+1,
				'to' => (($paginator->getCurrentPageNumber()-1)*$this->numItemPerPage)+$paginator->getCurrentItemCount(),
		);
		$this->view->assign('item_index', $item_index);
		
		$this->view->assign('paginator', $paginator);
		
		if ($this->str_search_msg == '') $this->str_search_msg = '全業種';
		$this->view->assign('str_search_msg', $this->str_search_msg);
		$this->view->assign('params', $params);
		
		if ($kind!='search'){
			switch ($kind){
				case 'feature':
					$url_search = '/'.$this->category.'/feature/'.$params['tag'];
					break;
				case 'area':
					$url_search = '/'.$this->category.'/area/'.$params['prefecture'].'/'.$params['cities'];
					break;
				case 'line':
					$url_search = '/'.$this->category.'/line/'.$params['lines'];
					break;
				case 'station':
					$url_search = '/'.$this->category.'/station/'.$params['stations'];
					break;
			}
			$this->view->assign('url_search', $url_search);
		}
		
		$str_url = array();
		if ($kind == 'search'){
			$str_url[] = array('', $this->str_search_msg.'の求人');
			$str_header_title = $this->str_search_msg.'の検索結果｜チューズ';
			$url_header_lnk = '/all';
		}else{
			if ($this->category != 'all'){
				$tag_info = $this->obj->getTagByUrl($this->category);
				$str_url[] = array('/'.$this->category, $tag_info['contents'].'|'.$this->arr_category_head[$tag_info['url']].'の求人');
			}else{
				$str_url[] = array('/all', '全業種/'.$this->arr_category_head[$this->category].'の求人');
			}
			$str_url[] = array('', $this->str_search_msg.'の求人');
			$str_header_title = $this->str_search_msg.'|'.$this->arr_category_head[$this->category].'の求人・転職・募集';
			$url_header_lnk = '/'.$this->category;
		}
		$this->view->assign('arr_url', $str_url);
		$this->view->assign('str_header_title', $str_header_title); // ヘッダにタイトル
		$this->view->assign('url_header_lnk', $url_header_lnk); // ヘッダのロゴのリンク
	}
	
	public function getCitiesAction(){
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		 
		$pref_name = $this->getParam('pref_name', '');
		$cities = $this->obj->getCitiesByPrefName($pref_name);
		echo Zend_Json::encode($cities);
	}
	
	public function getStationsAction(){
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
			
		$line_rome = $this->getParam('line_rome', '');
		$rs_line = $this->obj->getLineByLineRome($line_rome);
		$stations = $this->obj->getAllStationsByLineId($rs_line['line_id']);
		echo Zend_Json::encode($stations);
	}
	

	public function _setDataSearchForm(){
		$data_pref = $this->obj->getAllPrefs();
		$this->view->data_pref = $data_pref;
	
		$data_tag = $this->obj->getGyousu();
		$this->view->data_tag = $data_tag;
	
		$data_features = $this->obj->getFeatures();
		$this->view->data_features = $data_features;
	
		$data_kinds = $this->obj->getKinds();
		$this->view->data_kinds = $data_kinds;
	
		$data_ages = $this->obj->getAges();
		$this->view->data_ages = $data_ages;
	
		$data_sex = $this->obj->getSex();
		$this->view->data_sex = $data_sex;
	
		$data_salary = $this->obj->getSalary();
		$this->view->data_salary = $data_salary;
	
		$data_experience = $this->obj->getExperience();
		$this->view->data_experience = $data_experience;
	}
	
	public function _setDataForView($rs_search){
		foreach ($rs_search as $k=>$offer){
			// 募集職種
			$rs_tag_sokusyu = $this->obj->getTagsByOfferGroup($offer['offer_id'], 3);
			$sokusyu = '';
			foreach ($rs_tag_sokusyu as $k1=>$v1){
				$sokusyu .= $v1['contents'].' ';
			}
			$rs_search[$k]['sokusyu'] = $sokusyu;
		
			// 雇用形態
			$rs_tag_koyokeitai = $this->obj->getTagsByOfferGroup($offer['offer_id'], 4);
			$koyokeitai = '';
			foreach ($rs_tag_koyokeitai as $k1=>$v1){
				$koyokeitai .= $v1['contents'].' ';
			}
			$rs_search[$k]['koyokeitai'] = $koyokeitai;
				
			// 給料
			$rs_tag_kyuryo = $this->obj->getTagsByOfferGroup($offer['offer_id'], 17);
			$kyuryo = '';
			foreach ($rs_tag_kyuryo as $k1=>$v1){
				$kyuryo .= $v1['contents'].' ';
			}
			$rs_search[$k]['kyuryo'] = $kyuryo;

			// 住所
			if ($rs_search[$k]['pref_id']!='' && $rs_search[$k]['city_code']!=''){
			    $pref = $this->obj->getPrefByPrefId($rs_search[$k]['pref_id']);
			    $city = $this->obj->getCityByCityCode($rs_search[$k]['city_code']);
			    $rs_search[$k]['address'] = $pref['name'].$city['city_name'];
			}else{
			    $shop = $this->obj->getShopByShopId($rs_search[$k]['shop_id']);
			    $rs_search[$k]['address'] = '';
			    if ($shop['pref_id']!=''){
			        $pref = $this->obj->getPrefByPrefId($shop['pref_id']);
			        $rs_search[$k]['address'] .= $pref['name'];
			    }
			    if ($shop['city_code']!=''){
			        $city = $this->obj->getCityByCityCode($shop['city_code']);
			        $rs_search[$k]['address'] .= $city['city_name'];
			    }
			}
			
			// 駅名前
			$rs_station = $this->obj->getStationByOfferId($offer['offer_id']);
			$rs_search[$k]['stations'] = $rs_station;
			
			//  番号作成
			$tmp = sprintf('%06d', $offer['offer_id']);
			$offer_bango = substr($tmp, 0, 3).'-'.substr($tmp, 3);
			$rs_search[$k]['offer_bango'] = $offer_bango;
			
			// 関連キーワード
			$keywords = $this->_getKeywordByOfferId($offer['offer_id'], $this->category);
			$rs_search[$k]['keyword'] = $keywords;
			
			// イメージ			
			$shop_list_img = $this->obj->getImagesDisplay($offer['shop_id'], 1);
			$rs_search[$k]['shop_list_img'] = $shop_list_img;
		}
		return $rs_search;
	}
	
	public function _getKeywordByOfferId($offer_id, $category_name){
	    $obj = new App_Model_Search();
	    $keywords = "";
	    
	    $rs_station = $obj->getStationByOfferId($offer_id);
	    $rs_address = $obj->getPrefCityByOfferId($offer_id);
	    
	    $keywords .= "<a href='/".$category_name."/area/".$rs_address['name']."'>".$rs_address['name']."</a>　";
	    $keywords .= "<a href='/".$category_name."/area/".$rs_address['name']."/".$rs_address['city_code']."'>".$rs_address['city_name']."</a>　";
	    foreach ($rs_station as $k1=>$v1){
	        $keywords .= "<a href='/".$category_name."/station/".$v1['station_id']."'>".$v1['station_name']."</a>　";
	    }
	    $rs_line = $obj->getAllLinesByOfferId($offer_id);
	    foreach ($rs_line as $k1=>$v1){
	        $keywords .= "<a href='/".$category_name."/line/".$v1['name']."'>".$v1['name']."</a>　";
	    }
	    $rs_tag = $obj->getAllTagsByOfferId($offer_id);
	    foreach ($rs_tag as $k1=>$v1){
	        $keywords .= "<a href='/".$category_name."/feature/".$v1['contents']."'>".$v1['contents']."</a>　";
	    }
	    return $keywords;
	}
}