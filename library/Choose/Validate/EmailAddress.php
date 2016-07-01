<?php

class Choose_Validate_EmailAddress extends Zend_Validate_EmailAddress {

    /**
     * @param  string $emailAddress
     * @return boolean validation result
     */
    public function isValid($emailAddress) {
        // 既に登録済み？
        $model_obj = new App_Model_PreUser();
        $is_registed = $model_obj->isExistEmailAddress($emailAddress);
        if ($is_registed) {
            $this->_error("メールアドレスは既に登録されています。");
            return FALSE;
        }

        if (!parent::isValid($emailAddress)) {
            //$this->_error("メールアドレスが正しくありません。");
            return FALSE;
        }
        return TRUE;
    }
}
?>
