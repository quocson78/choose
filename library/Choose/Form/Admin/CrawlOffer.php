<?php

class Choose_Form_Admin_CrawlOffer extends Choose_Form_Admin
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
        $tagDisplay = new App_Model_DbTable_TagDisplay;
        $types = null;
        foreach ($categories as $id => $cat) {
            $types[$id] = $tagDisplay->getChildren($id);
        }
*/
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

        $elem = new Zend_Form_Element_Radio('category');
        $elem->setLabel('業種')
            ->setRequired()
            ->addMultiOptions($categories);
        $this->addElement($elem);
/*
        $elems = null;
        foreach ($types as $catid => $cat) {

            $categories[$catid];

            $type = null;
            foreach ($cat as $tmp) {
                $type[$tmp['tag_id']] = $tmp['contents'];
            }

            $elem = new Zend_Form_Element_MultiCheckbox('types');
            $elem->setLabel('職種')
                ->setRequired()
                ->addMultiOptions($type);
            //$this->addElement($elem);
            $elems[] = $elem;
        }
        $this->addDisplayGroup($elems, 'typesGroup');
*/

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

        $elem = new Zend_Form_Element_Textarea('work_time');
        $elem->setLabel('勤務時間');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Textarea('holidays');
        $elem->setLabel('休日');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Textarea('offer_age');
        $elem->setLabel('応募年齢');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Textarea('app_qualification');
        $elem->setLabel('応募資格');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Textarea('reception');
        $elem->setLabel('歓迎項目');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Textarea('note');
        $elem->setLabel('その他');
        $this->addElement($elem);

        $buttons = null;

        $elem = new Zend_Form_Element_Submit('accept');
        $elem->setLabel('登録')
            ->setDecorators(array('ViewHelper'));
        $buttons[] = $elem;

        $elem = new Zend_Form_Element_Submit('next');
        $elem->setLabel('次のサイトに！')
            ->setDecorators(array('ViewHelper'));
        $buttons[] = $elem;

        $this->addDisplayGroup($buttons, 'acceptButton');

        $buttons = null;

        $elem = new Zend_Form_Element_Submit('shop');
        $elem->setLabel('店舗情報のみ登録')
            ->setDecorators(array('ViewHelper'));
        $buttons[] = $elem;

        $elem = new Zend_Form_Element_Submit('reject');
        $elem->setLabel('クロール停止')
            ->setDecorators(array('ViewHelper'));
        $buttons[] = $elem;

        $elem = new Zend_Form_Element_Submit('suspend');
        $elem->setLabel('保 留')
            ->setDecorators(array('ViewHelper'));
        $buttons[] = $elem;

        $this->addDisplayGroup($buttons, 'relectButton');
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

        if ('pref_id' == $name && $value) {
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
    
    public function render($view = null)
    {
        $elm = $this->getElement('category');
        $elm->setLabel('業種（カテゴリー）:' . $this->getValue('type'));

        return parent::render($param);
    }
}