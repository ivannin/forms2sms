<?php
/**
 * Рассылка SMS оповещений менеджерам
 */
class SMS2Manager
{
	
	/**
	 * Метод отправляет текст очередному менеджеру
	 *
	 * @param string $text	Текст сообщения 
	 * @return void
	 */
	public function send($text)
	{
		$tel = $this->getTel();
		if ( !empty($tel) )
			return $this->sendSMS($tel, $text);	
		else
			return false;
	}
	
	
	/**
	 * Метод отправляет SMS
	 * http://redsms.ru/podklyuchenie/
	 * https://lk.redsms.ru/api/ 
	 *
	 * @param string $tel	Телефон получателя
	 * @param string $text	Текст сообщения
	 * @retun bool
	 */ 
	protected function sendSMS($tel, $text)
	{
		$tel = str_replace( array('+', '-', '(', ')', ' '), '', $tel ); 
		
		$options = get_option( 'f2sms_settings' );
		
		$timestamp = file_get_contents( 'https://lk.redsms.ru/get/timestamp.php' );

		$api_key = $options['api_key'];
		$login = $options['login'];
		$phone = $tel;
		$return = 'json';
		$sender = $options['sender'];
		$text = $text;

		$params = array(
			'timestamp' => $timestamp,
			'login'     => $login,
			'phone'     => $phone,
			'text'      => $text,
			'sender'    => $sender,
			'return'    => $return
		);

		ksort( $params );
		reset( $params );
		$signature = md5( implode( $params ) . $api_key );

		//Send SMS with cURL
		$query = "https://lk.redsms.ru/get/send.php?login=" . $login . "&signature=" . $signature . "&phone=" . $phone . "&sender="
			. $sender . "&return=" . $return . "&timestamp=" . $timestamp . "&text=" . urlencode( $text );
		$curl = curl_init();
		curl_setopt( $curl, CURLOPT_URL, $query );
		curl_setopt( $curl, CURLOPT_ENCODING, "utf-8" );
		curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 120 );
		curl_setopt( $curl, CURLOPT_TIMEOUT, 120 );
		curl_setopt( $curl, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec( $curl );
		
		return $result;			
	}
	
	
    /** 
	 * @const Имя файла с номерами телефонов
	 */
    const TEL_TILE = 'tel.txt';	
	
	/**
	 * Метод возвращает телефон очередного получателя
	 * https://www.atompark.com/ru/servis-sms-rassylok/shlyuz-php/
	 * 
	 * @retun string
	 */ 
	protected function getTel()
	{
		//return '+79151732528';		
		
		// Читаем файл в массив
		$options = get_option( 'f2sms_settings' );
		$tels = explode("\n", $options['tel_list']);
		
		// Создаем новое содержимое со второго номера
		$newFileContent = '';
		for ($i = 1; $i < count($tels); $i++)
			$newFileContent .= trim($tels[$i]) . PHP_EOL;
		
		// В конец добавляем первый номер
		$newFileContent .= trim($tels[0]);
		
		// Пишем файл
		$options['tel_list'] = $newFileContent;
		update_option( 'f2sms_settings', $options );
			
		// Возвращаем первый номер
		return trim($tels[0]);
	}
}