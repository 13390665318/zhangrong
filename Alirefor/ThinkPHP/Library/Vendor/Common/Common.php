<?php

class Common {
	public $error;

	public static function C() {
		static $common = null;
		if ($common == null)
			$common = new Common();
		return $common;
	}

	public function __construct() {

	}

	public function getError() {
		return $this->error;
	}

	public function sendPost($url, $post, $gzip = false, $timeout = 10) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36');

		if ($gzip) {
			curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		}
		if ($post) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		if (strpos($url, "https") !== false) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}

		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

		$content = curl_exec($ch);
		if ($error = curl_error($ch))
			$this->error = $error;

		curl_close($ch);

		return $content;
	}

	public function getSign($data, $serect_key) {
		if (!is_array($data))
			return "";

		ksort($data);
		$signStr = "";
		foreach ($data as $key => $value) {
			$signStr .= $key . "=" . $value;
		}
		$signStr .= $serect_key;

		return strtolower(md5($signStr));
	}

}
