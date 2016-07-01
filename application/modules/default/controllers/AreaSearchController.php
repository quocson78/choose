<?php
include 'SearchController.php';
class Default_AreaSearchController extends Default_SearchController
{
	public function indexAction(){
		$this->_setDataSearchForm();
		
		$params = $this->getRequest()->getParams();
		
		if (isset($params['prefecture']) && ($params['prefecture']!='')){
			$rs_pref = $this->obj->getPrefByName($params['prefecture']);
			$this->str_search_msg .= $rs_pref['name'].' ';
			
			$this->search_condition['prefecture'] = $rs_pref['pref_id'];// 検索条件
			
			$cities = $this->obj->getCitiesByPrefName($params['prefecture']);
			$this->view->assign('cities', $cities);
		}
		if (isset($params['cities']) && ($params['cities']!='')){
			$this->search_condition['city'] = $params['cities'];// 検索条件
			$rs_city = $this->obj->getCityByCityCode($params['cities']);
			$this->str_search_msg .= $rs_city['city_name'].' ';
		}
			
		
		if (isset($params['keywords']) && ($params['keywords']!='')){
			$this->search_condition['keyword'] = $params['keywords'];// 検索条件
		}
		if (isset($params['category']) && ($params['category']!='')){
			if ($params['category']=='all'){// すべてのカテゴリを編集
				
			}else{
				$rs_category = $this->obj->getTagByUrl($params['category']);
				$rs_tag = $this->obj->getTagByTagId($rs_category['tag_id']);
				$this->str_search_msg .= $rs_tag['contents'].' ';
				$params['categories'][$rs_category['tag_id']] = $rs_category['tag_id'];
				
				$this->search_condition['categories'] = array($rs_category['tag_id']=>$rs_category['tag_id']);// 検索条件
				$this->category = $params['category'];
			}
		}
		
		$this->_setDataPaging($params, 'area');
		
		$this->render('/search/index', null,true);
	}
}