<?php
/**
 * Description of Mysql
 *
 * @author Groove Gear,LTD.
 */
class Choose_Db_Adapter_Pdo_Mysql extends Zend_Db_Adapter_Pdo_Mysql
{
    /**
     * Inserts a table row with specified data.
     *
     * @param mixed $table The table to insert data into.
     * @param array $insert Column-value pairs.
     * @return int The number of affected rows.
     * @throws Zend_Db_Adapter_Exception
     */
    public function insertDuplicate($table, array $insert, array $update)
    {
        // extract and quote col names from the array keys
        $cols = array();
        $vals = array();
        $i = 0;

        foreach ($insert as $col => $val) {
            $cols[] = $this->quoteIdentifier($col, true);
            if ($val instanceof Zend_Db_Expr) {
                $vals[] = $val->__toString();
                unset($insert[$col]);
            } else {
                if ($this->supportsParameters('positional')) {
                    $vals[] = '?';
                } else {
                    if ($this->supportsParameters('named')) {
                        unset($insert[$col]);
                        $insert[':col'.$i] = $val;
                        $vals[] = ':col'.$i;
                        $i++;
                    } else {
                        /** @see Zend_Db_Adapter_Exception */
                        require_once 'Zend/Db/Adapter/Exception.php';
                        throw new Zend_Db_Adapter_Exception(get_class($this) ." doesn't support positional or named binding");
                    }
                }
            }
        }

        // build the statement
        $sql = 'INSERT INTO '
             . $this->quoteIdentifier($table, true)
             . ' (' . implode(', ', $cols) . ') '
             . 'VALUES (' . implode(', ', $vals) . ')';

        // execute the statement and return the number of affected rows
        if ($this->supportsParameters('positional')) {
            $values = array_values($insert);
        } else {
            $values = $insert;
        }

        /**
         * Build "col = ?" pairs for the statement,
         * except for Zend_Db_Expr which is treated literally.
         */
        $set = array();
        $i = 0;
        foreach ($update as $col => $val) {
            if ($val instanceof Zend_Db_Expr) {
                $val = $val->__toString();
                unset($update[$col]);
            } else {
                if ($this->supportsParameters('positional')) {
                    $val = '?';
                } else {
                    if ($this->supportsParameters('named')) {
                        unset($update[$col]);
                        $update[':col'.$i] = $val;
                        $val = ':col'.$i;
                        $i++;
                    } else {
                        /** @see Zend_Db_Adapter_Exception */
                        require_once 'Zend/Db/Adapter/Exception.php';
                        throw new Zend_Db_Adapter_Exception(get_class($this) ." doesn't support positional or named binding");
                    }
                }
            }
            $set[] = $this->quoteIdentifier($col, true) . ' = ' . $val;
        }

        /**
         * Build the UPDATE statement
         */
        $sql .= ' ON DUPLICATE KEY UPDATE  '
                . implode(', ', $set);

        /**
         * Execute the statement and return the number of affected rows
         */
        if ($this->supportsParameters('positional')) {
            $values = array_merge($values, array_values($update));
        } else {
            $values = array_merge($values, $update);
        }

        return $this->query($sql, $values)->rowCount();
    }
}
