<?php

class Choose_Mail extends Zend_Mail
{
	/**
	 * Public constructor
	 *
	 * @param  string $charset
	 * @return void
	 */
	function __construct()
	{
		parent::__construct('ISO-2022-JP');
	}
	
	/**
	 * メール送信
	 *
	 * @param type $section
	 * @param type $params
	 * @param type $subject
	 */
	function send($section, $params = NULL, $subject = array(), $html = false)
	{
		$config = new Zend_Config_Ini(
				APPLICATION_PATH . '/configs/mail.ini',
				APPLICATION_ENV);
		
		if (!isset($config->$section)) {
			throw new Choose_Exception('mail設定ファイルに定義がありません');
		}
		
		$mail = $config->$section;
		
		// Subject
		array_unshift($subject, $mail->subject);
		$subject = call_user_func_array('sprintf', $subject);
		$subject = mb_convert_encoding($subject, $this->_charset);
		$this->setSubject($subject); // メールのタイトルを設定 
		
		// From
		$name = mb_encode_mimeheader(
				mb_convert_encoding(
						$config->{$mail->from}->name,
						$this->_charset));		
		$this->setFrom($config->{$mail->from}->address, $name);// 宛先情報を設定
		
		// To,Cc,Bccは予めセットしてください
		
		// return path
		$tr = null;
		if ($mail->return) {
			$name = mb_encode_mimeheader(
					mb_convert_encoding(
							$config->{$mail->return}->name,
							$this->_charset));
		
			$tr = new Zend_Mail_Transport_Sendmail("-f{$name}<{$config->{$mail->return}->address}>");
			$this->setReturnPath($config->{$mail->return}->address, $name);
		}
	
		// repry-to
		$tr = null;
		if ($mail->replyTo) {
			$this->setReplyTo($config->{$mail->replyTo}->address);
		}
	
		// Body
		$body = new Zend_View();
		$body->setUseStreamWrapper(true);
		$body->setScriptPath(APPLICATION_PATH . '/views/mails');
		$body->assign($params);
		$body = $body->render($section . '.php');
	
		$body = preg_replace("|\r\n?|", "\r\n", $body);
	
		$body = mb_convert_encoding($body, $this->_charset);
		
		$this->setBodyText($body); // メールの本文を設定
	
		if ($html)
			$this->setBodyHtml($body);
	
		parent::send($tr);
	}
	
	/*
	 * @param string $templace_name
	 * @param array $to array(array('email' => '', 'name' => ''))
	 * @param array $params 
	 */
	function sendTo($section, $to, $params = null, $subject = array(), $html = false){
		// To
		$name = mb_encode_mimeheader(
		        mb_convert_encoding(
		                $to['name'],
		                $this->_charset));
		$this->addTo($to['email'], $name);

		$this->send($section, $params, $subject, $html);
	}
	/**
	 * サイト管理者へ送信
	 * 
	 * @param string $templace_name
	 * @param array $from array('email' => '', 'name' => '')
	 * @param array $to array(array('email' => '', 'name' => ''))
	 * @param array $params
	 */
	function sendToAdmin($section, $params = null, $subject = array(), $html = false)
	{
		$config = new Zend_Config_Ini(
				APPLICATION_PATH . '/configs/mail.ini',
				APPLICATION_ENV);
	
		if (!isset($config->$section)) {
			throw new Choose_Exception('mail設定ファイルに定義がありません');
		}
	
		$mail = $config->$section;
		// To
		foreach (explode(',', $mail->to) as $to) {
			$name = mb_encode_mimeheader(
					mb_convert_encoding(
							$config->$to->name,
							$this->_charset));
			$this->addTo($config->$to->address, $name);
		}
		$this->send($section, $params, $subject, $html);
	}
	
	/**
	 * 店舗へ送信
	 * 
	 * @param string $templace_name
	 * @param array $from array('email' => '', 'name' => '')
	 * @param array $to array(array('email' => '', 'name' => ''))
	 * @param array $params
	 */
	function sendShop($section, $shop_id, $params = null, $subject = array(), $html = false)
	{
	    $obj_shop = new App_Model_DbTable_MShops();
	    $shop = $obj_shop->getByShopId($shop_id);
	    // To
	    $name = mb_encode_mimeheader(
	            mb_convert_encoding(
	                    $shop['shopname'],
	                    $this->_charset));
	    $this->addTo($shop['email_address'], $name);
	    	
	    $this->send($section, $params, $subject, $html);
	}
	
	/**
	 * ユーザーへ送信
	 * 
	 * @param string $templace_name
	 * @param array $from array('email' => '', 'name' => '')
	 * @param array $to array(array('email' => '', 'name' => ''))
	 * @param array $params
	 */
	function sendUser($section, $userId, $params = null, $subject = array(), $html = false)
	{
		$obj_user = new App_Model_DbTable_MUsers();
		$user = $obj_user->getRowPreUser($userId);
		// To
		$name = mb_encode_mimeheader(
				mb_convert_encoding(
						$user['first_name'].$user['last_name'],
						$this->_charset));
		$this->addTo($user['email_address'], $name);
					
		$this->send($section, $params, $subject, $html);
	}
}
