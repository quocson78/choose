<?php
include 'SearchController.php';
class Default_FeatureSearchController extends Default_SearchController
{
	public function indexAction(){
		$this->_setDataSearchForm();
		
		$params = $this->getRequest()->getParams();
		
		if (isset($params['tag']) && ($params['tag']!='')){
			$rs_tag = $this->obj->getTagbyTagContent($params['tag']);
			$this->str_search_msg .= $rs_tag['contents'].' ';
			$params['features'][$rs_tag['tag_id']] = $rs_tag['tag_id'];

			$this->search_condition['features'] = array($rs_tag['tag_id']=>$rs_tag['tag_id']);
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
		$this->_setDataPaging($params, 'feature');
		
		$this->render('/search/index', null,true);
	}
}