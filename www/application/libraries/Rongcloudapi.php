<?php
	require_once(APPPATH."helpers/http_helper.php");
	class Rongcloudapi {
		private $appkey = 'x18ywvqf8xdcc';
		private $appsecret = 'IlKQIFBSPS';
		public function __construct() {
		}
		/*
		*	获取融云token
		*/

		public function get_rctoken($user_id, $nickname, $headimg) {
			$data_rc = 'userId='.$user_id.'&name='.$nickname.'&portraitUri='.$headimg;
			$timestamp = time();
			$nonce = rand();
			$singnature = sha1($this->appsecret.$nonce.$timestamp);
			$headers = array(
					'App-Key:'.$this->appkey,
					'Nonce:'.$nonce,
					'Timestamp:'.$timestamp,
					'Signature:'.$singnature,
					'Content-Type: application/x-www-form-urlencoded',
				);

			$url = 'https://api.cn.ronghub.com/user/getToken.json';
			$sJson = http_request($url, $data_rc, $headers);
			$data_json = json_decode($sJson, true);
			if($data_json['code'] != "200") 
			{
				return  null;
			}
			return $data_json['token'];
		}
	}
?>