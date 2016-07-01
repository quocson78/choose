<?php
include 'SearchController.php';
class Default_StationSearchController extends Default_SearchController
{
	public function indexAction(){
		$this->_setDataSearchForm();
		
		$params = $this->getRequest()->getParams();
		
		if (isset($params['stations']) && ($params['stations']!='')){
			$this->search_condition['station'] = $params['stations']; // 検索条件
			$rs_station = $this->obj->getStationByStationId($params['stations']);
			$this->str_search_msg .= $rs_station['station_name'].' ';
			
			$lines = $this->obj->getAllLinesByLineId($rs_station['line_id']);
			$params['lines'] = $rs_station['line_id'];
			$this->view->assign('lines', $lines);
				
			$stations = $this->obj->getAllStationsByLineId($rs_station['line_id']);
			$this->view->assign('stations', $stations);
		}
		
		if (isset($params['category']) && ($params['category']!='')){
			if ($params['category']=='all'){// すべてのカテゴリを編集
				
			}else{
				$rs_category = $this->obj->getTagByUrl($params['category']);
				$rs_tag = $this->obj->getTagByTagId($rs_category['tag_id']);
				$this->str_search_msg .= $rs_tag['contents'].' ';
				$params['categories'][$rs_category['tag_id']] = $rs_category['tag_id'];
				
				$this->search_condition['categories'] = array($rs_category['tag_id']=>$rs_category['tag_id']); // 検索条件
				$this->category = $params['category'];
			}
		}
		
		$this->_setDataPaging($params, 'feature');
		
		$this->render('/search/index', null,true);
	}
}