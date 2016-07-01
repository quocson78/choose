<?php

class Choose_Form_Admin_CrawlShop extends Choose_Form_Admin
{
    public function init()
    {
        $tags = new App_Model_DbTable_MPrefs;
        $prefectures = array('0' => '-----')
            + $tags->getPairs();

        $tags = new App_Model_DbTable_Tags;
        $categories = $tags->getPairsByLowerId(App_Model_DbTable_Tags::LOWER_CATEGORY);
        $types = $tags->getPairsByLowerId(App_Model_DbTable_Tags::LOWER_TYPE);
/*
        $this->addElementPrefixPath(
                    'Choose_Decorator_',
                    'Choose/Decorator/',
                    'decorator');
        $this->setDecorators(array('Admin'));
*/
        $this->setAction('/crawl/shop/save')
             ->setMethod('post');

        $elem = new Zend_Form_Element_Hidden('no');
        $elem->setDecorators(array('ViewHelper'))
            ->setRequired()
            ->addValidator(new Zend_Validate_Digits);
        $this->addElement($elem);
/*
        $elem = new Zend_Form_Element_Hidden('type');
        $elem->setDecorators(array('ViewHelper'))
            ->setRequired();
        $this->addElement($elem);
*/
        $elem = new Zend_Form_Element_Text('shopname');
        $elem->setLabel('店舗名')
            ->setRequired();
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('shopname_kana');
        $elem->setLabel('店舗名 カナ')
            ->addValidator('Regex', true, array('/^[ァ-ヾ ]+$/u'));
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('zip_code');
        $elem->setLabel('郵便番号')
            ->setRequired()
            ->addValidator('Regex', true, array('/^\d{3}-\d{4}$/'));
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Select('pref_id');
        $elem->setLabel('都道府県')
            ->setRequired()
            ->addMultiOptions($prefectures);
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Select('city_code');
        $elem->setLabel('市区町村');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('address');
        $elem->setLabel('住所')
            ->setRequired();
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('tel');
        $elem->setLabel('電話番号')
            ->addValidator('Regex', true, array('/^\d+-\d+-?\d+$/'));
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('url');
        $elem->setLabel('URL')
            ->setRequired();
        $this->addElement($elem);
    }


    /**
     * Set default value for an element
     *
     * @param  string $name
     * @param  mixed $value
     * @return Choose_Form_Admin_CrawlShop
     */
    public function setDefault($name, $value)
    {
        parent::setDefault($name, $value);

        $name = (string) $name;

        if ('pref_id' == $name) {
            $tags = new App_Model_DbTable_MCities;
            $cities = array('' => '-----')
                + $tags->getPairsByPrefId($value);

            $elm = $this->getElement('city_code');
            $elm->addMultiOptions($cities);
        }

        return $this;
    }

    public function isValid($data)
    {
        if ($data['pref_id']) {
            $tags = new App_Model_DbTable_MCities;
            $cities = array('' => '-----')
                + $tags->getPairsByPrefId($data['pref_id']);

            $elm = $this->getElement('city_code');
            $elm->addMultiOptions($cities);
        }

        return parent::isValid($data);
    }
}