<?php
/**
 * WeddingPark
 *
 * @package WeddingPark
 * @copyright  Copyright (c) 2013- Groove Gear LTD.
 * @version    $Id$
 */

/**
 * Javascript出力補助ヘルパ
 *
 * 注意！
 * ユーザーが入力したデータなど、変数をそのままappendFileすると
 * セキュリティーホールになります。
 *
 */
class Choose_View_Helper_HeadScript extends Zend_View_Helper_HeadScript
{
    /**
     * Retrieve string representation
     *
     * @param  string|int $indent
     * @return string
     */
    public function join()
    {
        $indent = (null !== $indent)
                ? $this->getWhitespace($indent)
                : $this->getIndent();

        if ($this->view) {
            $useCdata = $this->view->doctype()->isXhtml() ? true : false;
        } else {
            $useCdata = $this->useCdata ? true : false;
        }
        $escapeStart = ($useCdata) ? '//<![CDATA[' : '//<!--';
        $escapeEnd   = ($useCdata) ? '//]]>'       : '//-->';

        $script = null;
        $this->getContainer()->ksort();

        foreach ($this as $item) {
            if (!$this->_isValid($item)) {
                continue;
            }

            $text = file_get_contents('.' . $item->attributes['src']);

            $text = preg_replace(
                        array(
                            '|/\*[\s\S]*?\*/|',
                            '|//.*?\n|'),
                        '', $text);

            // ；なしのjsが補完できないので
            // 残念ながら改行は消せない感じ
            $text = preg_replace(
                array(
                    '/\t/',
                    '/ +/',
                    '|\n+\s*|',
                    '|;\s+|'),
                array(
                    ' ',
                    ' ',
                    "\n",
                    ';'),
                $text);

            $script .= "\n/* {$item->attributes['src']} */\n" . $text;
        }

        $file = 'c/' . sha1($script) . '.js';

        if (!file_exists($file)) {
            if (!file_put_contents($file, $script)) {
                return null;
            }
        }

        return sprintf('<script src="/%s"></script>', $file);
    }
}
