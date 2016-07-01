<?php

/**
 * テーブルアクセス基礎クラス
 *
 * @category   Choose
 * @package    Choose_Db
 * @subpackage Table
 * @copyright  Copyright (c) 2013- Groove Gear LTD.
 */
class Choose_Db_Table_Abstract extends Zend_Db_Table
{
    protected $_schema = 'choose';

    /**
     *
     */
	public function getAll()
    {
	    return $this->fetchAll();
	}


    /**
     * ID指定で１カラム取得
     *
     * @param string    $name   カラム名
     * @param mixed     $key    プライマリキー
     * @return string   検索結果
     */
    public function findOne($key, $name)
    {
        $row = $this->find($key)->current();

        if (!$row) return NULL;

        return $row->$name;
    }

    /**
     * Zend_Dbにあるやつのクローン的なもの
     *
     * @param string|array|Zend_Db_Table_Select $where  OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
     * @param string|array                      $order  OPTIONAL An SQL ORDER clause.
     * @param int                               $count  OPTIONAL An SQL LIMIT count.
     * @param int                               $offset OPTIONAL An SQL LIMIT offset.
     * @return array
     */
    public function fetchAssoc($where = null, $order = null, $count = null, $offset = null)
    {
        $rows = parent::fetchAll($where, $order, $count, $offset);

        $list = null;
        if ($rows) {
            foreach ($rows as $row) {
                $row = $row->toArray();
                $tmp = array_values(array_slice($row, 0, 1));
                $list[$tmp[0]] = $row;
            }
        }

        return $list;
    }

    /**
     * Zend_Dbにあるやつのクローン的なもの
     *
     * @param string|array|Zend_Db_Table_Select $where  OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
     * @param string|array                      $order  OPTIONAL An SQL ORDER clause.
     * @param int                               $count  OPTIONAL An SQL LIMIT count.
     * @param int                               $offset OPTIONAL An SQL LIMIT offset.
     * @return array
     */
    public function fetchPairs($where = null, $order = null, $count = null, $offset = null)
    {
        $rows = parent::fetchAll($where, $order, $count, $offset);

        if ($rows) {
            foreach ($rows as $row) {
                $row = $row->toArray();
                $tmp = array_values(array_slice($row, 0, 2));
                $list[$tmp[0]] = $tmp[1];
            }
        }

        return $list;
    }

    /**
     * insertDuplicate a row.
     *
     * @param  array  $data  Column-value pairs.
     * @return mixed         The primary key of the row inserted.
     */
    public function insertDuplicate(array $insert, array $update = NULL)
    {
        if (!$update) $update = $insert;

        // 空文字はNULLに
        $insert = $this->_blanks2Null($insert);
        $update = $this->_blanks2Null($update);

        $tableSpec = ($this->_schema ? $this->_schema . '.' : '') . $this->_name;

        return $this->_db->insertDuplicate($tableSpec, $insert, $update);
    }

    /**
     * 空文字をNULLに置き換える
     *
     * @param type $param
     * return array
     */
    protected function _blanks2Null(array $array)
    {
        foreach ($array as &$val) {
            if (!strlen($val))
                $val = NULL;
        }

        return $array;
    }
}

