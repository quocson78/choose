<?php

class Choose_Form_Admin_Shop extends Choose_Form_Admin
{
    public function init()
    {
        $tags = new App_Model_DbTable_MPrefs;
        $prefectures = $tags->getPairs();

        $this->setAction('/shop/add/save')
             ->setMethod('post');

        $elem = new Zend_Form_Element_Text('shopname');
        $elem->setLabel('店舗名')
            ->setRequired();
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('shopname_kana');
        $elem->setLabel('店舗名 カナ')
            ->setRequired()
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
        $elem->setLabel('市区町村')
            ->setRequired();
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('address');
        $elem->setLabel('住所')
            ->setRequired();
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('tel');
        $elem->setLabel('電話番号')
            ->addValidator('Regex', true, array('/^\d+-\d+-?\d+$/'));
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('fax');
        $elem->setLabel('FAX番号')
            ->addValidator('Regex', true, array('/^\d+-\d+-?\d+$/'));
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('email_address');
        $elem->setLabel('email');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('url');
        $elem->setLabel('URL');
        $this->addElement($elem);


        $elem = new Zend_Form_Element_Text('shop_sub_title');
        $elem->setLabel('店舗サブタイトル');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('responsible_first_name');
        $elem->setLabel('担当者（性）');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('responsible_last_name');
        $elem->setLabel('担当者（名）');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('responsible_first_name_kana');
        $elem->setLabel('担当者カナ（性）');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('responsible_last_name_kana');
        $elem->setLabel('担当者カナ（名）');
        $this->addElement($elem);

        // 店舗おすすめポイント
        for ($i=0; $i < 4; $i++) {
            $elem = new Zend_Form_Element_Textarea("shop_recommends[{$i}]['recommend_title']");
            $elem->setLabel("店舗おすすめタイトル{$i}");
            $this->addElement($elem);

            $elem = new Zend_Form_Element_Textarea("shop_recommends[{$i}]['recommend_subtitle']");
            $elem->setLabel("店舗おすすめサブタイトル{$i}");
            $this->addElement($elem);
        }

        $elem = new Zend_Form_Element_Textarea('title');
        $elem->setLabel('店舗情報見出し');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Textarea('subtext');
        $elem->setLabel('サブテキスト');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('owner_name');
        $elem->setLabel('オーナー名');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('owner_position_name');
        $elem->setLabel('オーナー肩書き');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Textarea('owner_message');
        $elem->setLabel('オーナーからのメッセージ');
        $this->addElement($elem);


        $elem = new Zend_Form_Element_Text('stuff_name');
        $elem->setLabel('スタッフ名');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Text('stuff_position_name');
        $elem->setLabel('スタッフ肩書き');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Textarea('stuff_message');
        $elem->setLabel('スタッフからのメッセージ');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Submit('save');
        $elem->setLabel('登 録')
            ->setDecorators(array('ViewHelper'));
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