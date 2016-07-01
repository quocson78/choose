<?php

class Choose_Lib {
	
	/**
	 * ランダムの文字列（サイズ：$n）を取得
	 * 
	 * @param int $n
	 * @return string
	 */
	public function getRandomString($n)
    {
		$strinit = 'abcdefghkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ012345679';

        $n = (int)$n;
		$str = null;

		for ($i = 1; $i <= $n; $i++ ) {
			$index = mt_rand(1, strlen($strinit)) - 1;
			$str .= $strinit[$index];
		}

		return $str;
	}
	
	public function getShopImagePath($shop_id)
    {
	    $n = (int)($shop_id/100);

		$path = sprintf('/images/%d/%d',
                    ($n + 1) * 100, $shop_id);

        return $path;
	}
}