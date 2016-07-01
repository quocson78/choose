<?php

/**
 * 画像タグ出力ヘルパー
 *
 * type: 画像縮小タイプ
 *   fit        通常
 *   frame      余白を白で埋める
 *   thumbnail  中央切り取り
 */
class Choose_View_Helper_ShopImage extends Choose_View_Helper_Abstract
//extends Choose_View_Helper_Abstract
{
    protected $_ini;

    public function __construct()
    {
        $this->_ini = new Choose_Config_Ini('image.ini');
    }

    /**
     * 画像タグ出力ヘルパー
     *
     */
    public function shopImage()
    {
        return $this;
    }

    /**
     * 画像タグ出力ヘルパー
     *
     */
    public function tagById($type, $shopId, $imageId, $options = array())
    {
        $src = $this->pathById($type, $shopId, $imageId);

        $attr = null;
        foreach ($options as $key => $value) {
            $attr[] = sprintf('%s="%s"',
                            $key, str_replace('"', '', $value));
        }
        $attr = implode(' ', $attr);

        $tag = sprintf('<img src="%s" %s />' ,$src, $attr);

        return $tag;
    }

    /**
     * 画像タグ出力ヘルパー
     *
     */
    public function tagByFile($type, $shopId, $src, $options = array())
    {
        $config = $this->_ini->{$type};

        $src = $this->pathByfile($type, $shopId, $src);

        $attr = null;
        foreach ($options as $key => $value) {
            $attr[] = sprintf('%s="%s"',
                            $key, str_replace('"', '', $value));
        }
        $attr = implode(' ', $attr);

        $tag = sprintf('<img src="%s" %s />' ,$src , $attr);

        return $tag;
    }

    /**
     * 画像URL 出力ヘルパー
     *
     */
    public function pathById($type, $shopId, $imageId)
    {
        $img = new App_Model_DbTable_ShopImages;

        $row = $img->get($shopId, $imageId);

        if (!$row) {
            // 見つからないときはアテの画像を出す？
            return false;
        }

        return $this->pathByFile($type, $shopId, $row['filename']);
    }

    /**
     * 画像URL 出力ヘルパー
     *
     */
    public function pathByFile($type, $shopId, $src)
    {
        $path = Choose_Lib::getShopImagePath($shopId);
        return $path . sprintf('/%s-%s', $type, $src);
    }
}
