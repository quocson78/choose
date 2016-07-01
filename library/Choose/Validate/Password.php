<?php

class Choose_Validate_Password extends Zend_Validate_Abstract {

    /**
     * 
     * @param type $value
     */
    public function isValid($value) {
        if(strlen($value) < 8){
            var_dump($value);
            $this->_error("パスワードは8文字以上の半角英数字と記号が利用できます。");
            return FALSE;
        }

        if(!preg_match("/^[!-~]+$/", $value)){
            $this->_error("パスワードに利用できる文字は半角英数字と記号です。");
            return FALSE;
        }

        return TRUE;

    }

}
?>
