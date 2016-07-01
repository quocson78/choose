<?php
include 'SearchController.php';
class Default_LineSearchController extends Default_SearchController
{
	public function indexAction(){
		$this->_setDataSearchForm();
		
		$params = $this->getRequest()->getParams();
		
		if (isset($params['lines']) && ($params['lines']!='')){
			$rs_line = $this->obj->getLineByLineName($params['lines']);
			$this->search_condition['line'] = $rs_line['line_id']; // 検索条件
			
			$this->str_search_msg .= $rs_line['name'].' ';
				
			$lines = $this->obj->getAllLinesByLineId($rs_line['line_id']);
			$this->view->assign('lines', $lines);
				
			$stations = $this->obj->getAllStationsByLineId($rs_line['line_id']);
			$this->view->assign('stations', $stations);
		}
		if (isset($params['keywords']) && ($params['keywords']!='')){
			$this->search_condition['keyword'] = $params['keywords']; // 検索条件
			//$this->str_search_msg .= $params['keywords'].' ';
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
		
		$this->_setDataPaging($params, 'area');
		
		$this->render('/search/index', null,true);
	}
}