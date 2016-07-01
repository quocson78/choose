<?php
/**
 * WeddingPark
 *
 * @package WeddingPark
 * @copyright  Copyright (c) 2013- Groove Gear LTD.
 * @version    $Id$
 */

/**
 * CSS出力補助ヘルパ
 *
 * 注意！
 * ユーザーが入力したデータなど、変数をそのままappendStylesheetすると
 * セキュリティーホールになります。
 *
 * @category   スマつく共有ライブラリ
 * @package    WeddingPark_View_Helper
 */
class Choose_View_Helper_HeadLink extends Zend_View_Helper_HeadLink
{
    /**
     * ファイルを結合して出力
     *
     * @param string $ext 結合対象ファイル拡張子
     * @return string The element XHTML.
     */
    public function join($ext = 'css')
    {
        $link = null;
        $css = null;

        foreach ($this as $item) {

            if (!preg_match("/{$ext}$/", $item->href)) {
                $link[] = $this->itemToString($item);
                continue;
            }

            $text = file_get_contents('.' . $item->href);

            $text = preg_replace(
                        array(
                            '|/\*[\s\S]*?\*/|',
                            '|@charset.+\n|',
                            '|\r?\n|'),
                        '', $text);

            $text = preg_replace(
                        array(
                            '|\s*,\s*|',
                            '|\s*:\s*|',
                            '|\s*{\s*|',
                            '|\s*}\s*|'),
                        array(',', ':', '{', '}'),
                            $text);


            $css .= "\n/* {$item->href} */\n" . $text;
        }

        $file = 'c/' . sha1($css) . '.css';

        if (!file_exists($file)) {
            if (!file_put_contents($file, $css)) {
                return null;
            }
        }

        $link[] = sprintf('<link rel="stylesheet" type="text/css" href="/%s" />', $file);

        return implode($link);
    }
}
