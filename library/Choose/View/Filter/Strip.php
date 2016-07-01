<?php
/**
 * タグの前後の空白削除
 *
 *
 * @category    Choose
 * @package     Choose_View
 * @subpackage  Filter
 * @copyright   aCopyright (c) 2013- GrooveGear LTD.
 */
class Choose_View_Filter_Strip implements Zend_Filter_Interface
{
    public function filter($string)
    {
        return preg_replace('|\s*(<.*?>)\s*|', '$1', $string);
    }
}
