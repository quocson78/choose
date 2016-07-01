#!/usr/bin/env php
<?php
include_once 'Cli.php';

// CLI 用のオプション
$opts = new Zend_Console_Getopt(array(
            'csv|c=s' => 'csv filename.',
       ));

try {
    $opts->parse();

    if (!$opts->csv) {
        throw new Zend_Console_Getopt_Exception(
                null, $opts->getUsageMessage());
    }

    if (!file_exists($opts->csv)) {
        throw new Exception('file not exsists.');
    }

    $fp = fopen($opts->csv, 'r');
    if (!$fp) {
        throw new Exception('file open error.');
    }

    $conv = new kana2roma();
    $table = new App_Model_DbTable_MLines;

    $line = fgetcsv($fp);

    while(!feof($fp)) {

        $line = fgetcsv($fp);

        if (13 != count($line)) {
            continue;
        }

        $data = array(
            'line_id'   => $line[0],
            'company_id' => $line[1],
            'name'      => $line[2],
            'alias'     => preg_replace('/\(.*\)/', '', $line[2]),
            'kana'      => $line[3],
            'rome'      => $conv->conv($line[3]),
            'order_no'  => $line[12],
        );
        $table->insert($data);
    }

} catch (Zend_Console_Getopt_Exception $e) {
    echo $e->getUsageMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}

class kana2roma {
	var $charset='utf-8';
	var $mode_Krows  = 'k'; //か・く・こ(k or c)
	var $mode_XArows = 'l'; //小文字ぁ行と「っ」( L or X)
	var $mode_TYrows = 'ch'; //ち行＋小文字や行(ty or ch or cy)
	var $mode_SYrows = 'sh'; //し行＋小文字や行(sy or sh)
	var $mode_JYrows = 'j'; //じ行＋小文字や行(j or zy or jy)
	var $mode_Sstr   = 'sh'; //し(s or sh or c)
	var $mode_Jstr   = 'j'; //じ(j or z)
	var $mode_TUstr  = 'ts'; //つ(t or ts)
	var $mode_FUstr  = 'f'; //ふ(h or f)
	var $mode_TIstr  = 'ch'; //ち(t or ch)
	var $mode_Nstr   = 'n'; //ん(n or nn)
	var $strout = true; //配列でなく文字で返すかどうか
	var $chop = false; //ローマ字文字列をアルファベット１文字ごとに分解するかどうか
	var $vowel = array("a","i","u","e","o");
	var $child = array("a","k","s","t","n","h","m","y","r","w","g","z","d","b","p","x","y","t");
	var $symbol = array("!","?","-","'",",");
	var $number = array("0","1","2","3","4","5","6","7","8","9");
	var $cols_H = array(
		"A"=>array("あ","か","さ","た","な","は","ま","や","ら","わ","が","ざ","だ","ば","ぱ","ぁ","ゃ"),
		"I"=>array("い","き","し","ち","に","ひ","み","＠","り","＠","ぎ","じ","ぢ","び","ぴ","ぃ"),
		"U"=>array("う","く","す","つ","ぬ","ふ","む","ゆ","る","ん","ぐ","ず","づ","ぶ","ぷ","ぅ","ゅ","っ"),
		"E"=>array("え","け","せ","て","ね","へ","め","＠","れ","＠","げ","ぜ","で","べ","ぺ","ぇ"),
		"O"=>array("お","こ","そ","と","の","ほ","も","よ","ろ","を","ご","ぞ","ど","ぼ","ぽ","ぉ","ょ")
	);
	var $const=NULL;

	function __construct($txt=NULL){
		if(!empty($txt)){
			$this->const=$txt;
			return $this->conv($txt);
		}
	}

	//パブリックメソッド
	//文字列分割→字数で分岐→ローマ字変換
	function conv($txt=NULL){
		if(empty($txt) && !empty($this->const)){
			$txt=$this->const;
		}
		if(empty($txt) && empty($this->const)){
			return NULL;
		}
		$txt=mb_convert_kana($txt,"c",$this->charset);
		$stack = $this->_TextSlice($txt);
		$out = array();
		for ($i = 0; $i <count($stack); $i++) {
			if(mb_strlen($stack[$i],$this->charset) == 1){
				$str = $this->_baseOne($stack[$i]);
				$out[]=$this->stringChopper($str);
			}else{
				$str2 = $this->_baseTwo($stack[$i]);
				$out[]=$this->stringChopper($str2);
			}
		}
		if ($this->strout) {
			return implode('',$out);
		}
		return $this->flatten($out);
	}

	//ローマ字文字列分解
	//$this->chop　がtrueならアルファベット毎に分解
	//@param {Object} str　ローマ字（日本語１文字分）
	function stringChopper($str){
		$out = array();
		if ($this->chop && !$this->strout) {
			for ($n = 0; $n <mb_strlen($str,$this->charset); $n++) {
				$out[]=mb_substr($str,$n,1);
			}
			return $out;
		}else{
			return $str;
		}
	}

	//文章を1文字単位に分割する
	//@param {Object} str　文章
	function _TextSlice($txt){
		$max = mb_strlen($txt,$this->charset);
		$n = 0;
		$array = array();
		for ($i = 0; $i <$max; $i++) {
			++$n;//次
			$str = mb_substr($txt,$i,1);//今の文字
			$nxt = mb_substr($txt,$n,1);//次の文字
			//隣接する１文字目が小文字や行なら
			if(ereg("(ゃ|ゅ|ょ)",$nxt)){
				$array[]=$str.$nxt;
				$i++;
				$n++;
			}else if($str=="っ" && array_search($nxt,$this->symbol)===false ){
				if(array_search($nxt,$this->number)===false){
					$array[]=$str.$nxt;
					$i++;
					$n++;
				}else{
					$array[]=$str;
				}
			}else{
				$array[]=$str;
			}
		}
		return $array;
	}

	//変換ベース（2文字）
	//小文字とセットで2文字になってる文字を判別して処理を分配する
	//@param {Object} str　変換する文字（小文字とセットで2文字）
	function _baseTwo($str){
		if (ereg("っ",$str)) {
			if(mb_strlen($str,$this->charset)==2){
				$txt = $this->_baseOne(mb_substr($str,1,1));
				return mb_substr($txt,0,1).$txt;
			}else{
				return $this->_baseOne($str);
			}
		}else{
			switch($str){
				case "ちゃ":
					return $this->mode_TYrows.$this->vowel[0];
				break;
				case "ちゅ":
					return $this->mode_TYrows.$this->vowel[2];
				break;
				case "ちょ":
					return $this->mode_TYrows.$this->vowel[4];
				break;
				case "しゃ":
					return $this->mode_SYrows.$this->vowel[0];
				break;
				case "しゅ":
					return $this->mode_SYrows.$this->vowel[2];
				break;
				case "しょ":
					return $this->mode_SYrows.$this->vowel[4];
				break;
				case "じゃ":
					return $this->mode_JYrows.$this->vowel[0];
				break;
				case "じゅ":
					return $this->mode_JYrows.$this->vowel[2];
				break;
				case "じょ":
					return $this->mode_JYrows.$this->vowel[4];
				break;
				default:
					$first = $this->_baseOne(mb_substr($str,0,1));
					$second = $this->_baseOne(mb_substr($str,1,1));
					return mb_substr($first,0,1).$second;
			}
		}
	}

	//変換ベース（1文字）
	//あいうえお行の配列（cols_H,number,symbol）から文字が何かを判別して各関数へ処理を分配する
	//@param {Object} str　変換する文字（１文字のみ）
	function _baseOne($str){
		if(array_search($str,$this->cols_H['A'])!==false){//あ行
			return $this->_Change_A_Rows(array_search($str,$this->cols_H['A']));
		}else if(array_search($str,$this->cols_H['I'])!==false){//い行
			return $this->_Change_I_Rows(array_search($str,$this->cols_H['I']));
		}else if(array_search($str,$this->cols_H['U'])!==false){//う行
			return $this->_Change_U_Rows(array_search($str,$this->cols_H['U']));
		}else if(array_search($str,$this->cols_H['E'])!==false){//え行
			return $this->_Change_E_Rows(array_search($str,$this->cols_H['E']));
		}else if(array_search($str,$this->cols_H['O'])!==false){//お行
			return $this->_Change_O_Rows(array_search($str,$this->cols_H['O']));
		}else if(array_search($str,$this->symbol) !== false){//記号
			return $this->symbol[array_search($str,$this->symbol)];
		}else if(array_search($str,$this->number) !== false){//数字
			return $str;
		}else{
			return NULL;
		}
	}

	//単音あ行文字をローマ字に
	//@param {Object} key　ひらがな配列のキー番号
	function _Change_A_Rows($key){
		if ($key == 1){//か行
			return $this->mode_Krows.$this->vowel[0];
		}else if($key == 15){//小文字ぁ行
			return $this->mode_XArows.$this->vowel[0];
		}else if($key == 0){
			return $this->vowel[0];
		}else{
			return $this->child[$key].$this->vowel[0];
		}
	}

	//単音い行文字をローマ字に　
	//@param {Object} key　ひらがな配列のキー番号
	function _Change_I_Rows($key){
		if ($key == 0){//母音
			return $this->vowel[1];
		}else if($key == 15){//小文字ぁ行
			return $this->mode_XArows.$this->vowel[1];
		}else if($key == 2){//し
			return $this->mode_Sstr.$this->vowel[1];
		}else if($key == 11){//じ
			return $this->mode_Jstr.$this->vowel[1];
		}else if($key == 3){//ち
			return $this->mode_TIstr.$this->vowel[1];
		}else{
			return $this->child[$key].$this->vowel[1];
		}
	}

	//単音う行文字をローマ字に　
	//@param {Object} key　ひらがな配列のキー番号
	function _Change_U_Rows($key){
		if ($key == 0){//母音
			return $this->vowel[2];
		}else if($key == 1){//く
			return $this->mode_Krows.$this->vowel[2];
		}else if($key == 15){//小文字ぁ行
			return $this->mode_XArows.$this->vowel[2];
		}else if($key == 3){//つ
			return $this->mode_TUstr.$this->vowel[2];
		}else if($key == 5){//ふ
			return $this->mode_FUstr.$this->vowel[2];
		}else if($key == 9){//ん
			return $this->mode_Nstr;
		}else if($key == 17){//っ
			return $this->mode_XArows.$this->mode_TUstr.$this->vowel[2];
		}else{
			return $this->child[$key].$this->vowel[2];
		}
	}

	//単音え行文字をローマ字に　
	//@param {Object} key　ひらがな配列のキー番号
	function _Change_E_Rows($key){
		if ($key == 0){//母音
			return $this->vowel[3];
		}else if($key == 15){//小文字ぁ行
			return $this->mode_XArows.$this->vowel[3];
		}else{
			return $this->child[$key].$this->vowel[3];
		}
	}

	//単音お行文字をローマ字に　
	//@param {Object} key　ひらがな配列のキー番号
	function _Change_O_Rows($key){
		if ($key == 0){//母音
			return $this->vowel[4];
		}else if($key == 1){//こ
			return $this->mode_Krows.$this->vowel[4];
		}else if($key == 15){//小文字ぁ行
			return $this->mode_XArows.$this->vowel[4];
		}else{
			return $this->child[$key].$this->vowel[4];
		}
	}

	function flatten($array) {
		$tmp = array();
		while (($v = array_shift($array)) !== null) {
			if (is_array($v)) {
				$array = array_merge($v, $array);
			} else {
				$tmp[] = $v;
			}
		}
		return $tmp;
	}
}