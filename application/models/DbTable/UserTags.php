<?php
class App_Model_DbTable_UserTags extends Choose_Db_Table_Abstract
{
    protected $_name = 'user_tags';
    
    public function insertTags($user_id, $tags_array){
        if (is_array($tags_array) && count($tags_array)){
            $where = $this->_db->quoteInto('user_id = ?', $user_id);
            $this->delete($where);
            foreach ($tags_array as $k=>$v){
                $data = array(
                            'user_id' => $user_id,
                            'tag_id'  => $v
                        );
                $this->insert($data);
            }
        }
    }
    
    public function getAllByUserId($user_id){
        $sql = $this->_db->select()
                         ->from($this->_name, 'tag_id')
                         ->where('user_id =?', $user_id);
        return $this->_db->fetchCol($sql);
    }
    
    public function getTagIdByUserIdGroupId($user_id, $group_id){
        $sql = $this->_db->select()
                        ->from(array('usertag' => $this->_name), 'tag_id')
                        ->joinInner(array('tag' => 'tags'), 'tag.tag_id=usertag.tag_id', array())
                        ->where('usertag.user_id =?', $user_id)
                        ->where('tag.lower_id =?', $group_id);
        return $this->_db->fetchOne($sql);
    }
}