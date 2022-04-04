<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SMS library
 *
 * Library with utilities to send texts via SMS Gateway (requires proxy implementation)
 */

class Sms_lib
{
	private $CI;

  	public function __construct()
	{
		$this->CI =& get_instance();
	}

	/*
	 * SMS sending function
	 * Example of use: $response = sendSMS('4477777777', 'My test message');
	 */
	public function sendSMS($phone, $message)
	{
		$username   = $this->CI->config->item('msg_uid');
		$password   = $this->CI->encryption->decrypt($this->CI->config->item('msg_pwd'));
		$originator = $this->CI->config->item('msg_src');

		$response = FALSE;

		// if any of the parameters is empty return with a FALSE
		if(empty($username) || empty($password) || empty($phone) || empty($message) || empty($originator))
		{
			//echo $username . ' ' . $password . ' ' . $phone . ' ' . $message . ' ' . $originator;
			$curl = curl_init();
 

			$BASE_URL = "https://19132k.api.infobip.com";
			$API_KEY = "App 6365b9de68ca7e76a8217f65cf3e4bfa-080eb0d1-a79d-425f-831f-eacb6dcb748f";
			
			$SENDER = $username;
			$RECIPIENT = $phone;
			$MESSAGE_TEXT = $message;
			 
			$MEDIA_TYPE = "application/json";
			 
			curl_setopt_array($curl, array(
				CURLOPT_URL => $BASE_URL . '/sms/2/text/advanced',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
			 
				CURLOPT_HTTPHEADER => array(
					'Authorization: ' . $API_KEY,
					'Content-Type: ' . $MEDIA_TYPE,
					'Accept: ' . $MEDIA_TYPE
				),
			 
				CURLOPT_POSTFIELDS =>'{"messages":[{"from": "' . $SENDER . '","destinations":[{"to":"' . $RECIPIENT . '"}],"text":"' . $MESSAGE_TEXT . '"}]}',
			));
			 
			$response = curl_exec($curl);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
			 
			echo ("HTTP code: " . $httpcode . "\n");
			echo ("Response body: " . $response . "\n");
			
		}
		else
		{
			$response = TRUE;

			// make sure passed string is url encoded
			$message = rawurlencode($message);

			// add call to send a message via 3rd party API here


	
			// Some examples

			// $url = "http://xxx.xxx.xxx.xxx/send_sms?username=$username&password=$password&src=$originator&dst=$phone&msg=$message&dr=1";

			// $c = curl_init();
			// curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			// curl_setopt($c, CURLOPT_URL, $url);
			// $response = curl_exec($c);
			// curl_close($c);
			

			// This is a textmarketer.co.uk API call, see: http://wiki.textmarketer.co.uk/display/DevDoc/Text+Marketer+Developer+Documentation+-+Wiki+Home
			
			// $url = 'https://api.textmarketer.co.uk/gateway/'."?username=$username&password=$password&option=xml";
			// $url .= "&to=$phone&message=".urlencode($message).'&orig='.urlencode($originator);
			// $fp = fopen($url, 'r');
			// $response = fread($fp, 1024);
			
		}

		return $response;
	}
}

?>
