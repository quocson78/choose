<?php

class Choose_Form_Admin_RegistOffer extends Choose_Form_Admin
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

        $elem = new Zend_Form_Element_Hash('csrf');
        $elem->setDecorators(array('ViewHelper'));
        $this->addElement($elem);

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

        $elem = new Zend_Form_Element_Radio('category');
        $elem->setLabel('業種')
            ->setRequired()
            ->addMultiOptions($categories);
        $this->addElement($elem);

        $elem = new Zend_Form_Element_MultiCheckbox('types');
        $elem->setLabel('職種')
            ->setRequired()
            ->addMultiOptions($types);
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Textarea('work_contents');
        $elem->setLabel('内容');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Textarea('income');
        $elem->setLabel('給与');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Textarea('treats');
        $elem->setLabel('待遇');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Textarea('holidays');
        $elem->setLabel('休日');
        $this->addElement($elem);

        $buttons = null;

        $elem = new Zend_Form_Element_Submit('accept');
        $elem->setLabel('Chooseに公開')
            ->setDecorators(array('ViewHelper'));
        $buttons[] = $elem;

        $elem = new Zend_Form_Element_Submit('more');
        $elem->setLabel('この求人を登録してさらに登録する')
            ->setDecorators(array('ViewHelper'));
        $buttons[] = $elem;

        $elem = new Zend_Form_Element_Submit('suspend');
        $elem->setLabel('保 留')
            ->setDecorators(array('ViewHelper'));
        $buttons[] = $elem;

        $this->addDisplayGroup($buttons, 'buttons');
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

    public function render($view = null)
    {
        $elm = $this->getElement('category');
        $elm->setLabel('カテゴリー:' . $this->getValue('type'));

        return parent::render($param);
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