<?php

class App_Model_Index{

    public function getGyokai(){
        $obj_tag = new App_Model_DbTable_Tags();
        return $obj_tag->getAllByGroup(1);
    }
    
    public function getGyousu(){
        $obj_tag = new App_Model_DbTable_Tags();
        return $obj_tag->getAllByGroup(2);
    }
    
    public function getChildrenFromTag($tag_id){
        $obj_tag_display = new App_Model_DbTable_TagDisplay();
        return $obj_tag_display->getChildren($tag_id);
    }
}