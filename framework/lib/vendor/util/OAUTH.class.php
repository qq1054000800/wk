<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>
class OAUTH {
	// API Key
	public $api_key = 'ftadGmMA6MykscSoqVio8fvC';
	// Secret Key
	public $secret_key = 'DOvtyADjU4pv6AoQPRWOAYlLkw4GuU93';
	// 回调地址
	public $redirect_uri = 'http://www.mvc.com';
	// 验证令牌
	public $access_token = 'a9f0b14c5db4680c70b866ab9cb24608';

	/**
	 * 获取授权跳转的url
	 * @param   授权类型，有 baidu sinaweibo qqdenglu qqweibo renren kaixin
	 * @return url字符串
	 * @link    http://developer.baidu.com/wiki/index.php?title=docs/social/oauth/authorization#.E8.AF.B7.E6.B1.82.E6.95.B0.E6.8D.AE.E5.8C.85.E6.A0.BC.E5.BC.8F
	 * @version 1.0
	 * 2013年10月14日22:34:11
	 */
	public function url($type = "qqdenglu") {
		$url = "https://openapi.baidu.com/social/oauth/2.0/authorize?";
		if (!session_id()) {
			session_start();
		}
		$_SESSION["state"] = md5(rand());

		// client_id：必须参数，注册应用时获得的API Key。
		// response_type：必须参数，此值固定为“code”。
		// media_type：必须参数，需要登录的社会化平台标识，具体参数定义可以参考“《支持的社会化平台》”一节。
		// redirect_uri：必须参数，授权后要社会化服务回跳的URI，即接收Authorization Code的URI。对于无Web Server的应用，其值可以是“oob”，此时用户同意授权后，授权服务会将Authorization Code直接拼接在响应页面的URL参数中。如果redirect_uri不为"oob"，则redirect_uri指向的页面必须与开发者在“社会化登录服务”中所填写的"社会化服务回跳地址"相匹配。
		// state：非必须参数，用于保持请求和回调的状态，授权服务器在回调时（重定向用户浏览器到“redirect_uri”时），会在Query Parameter中原样回传该参数。建议开发者利用state参数来防止CSRF攻击。
		// display：非必须参数，登录和授权页面的展现样式，默认为”page”，具体参数定义可以参考“《授权页面样式》”一节。
		// client_type：非必须参数，用来标识请求来源于哪一个客户端。如果在请求中传递了该参数，则可以在开发者中心应用管理中查看到相关统计数据。client_type的值可为“ios”、”android”、”web”中的任意一种。
		$query_array = array(
			"client_id"     => $this->api_key,
			"response_type" => "code",
			"media_type"    => $type,
			"redirect_uri"  => $this->redirect_uri,
			"state"         => $_SESSION["state"],
			"display"       => "page",
			"client_type"   => "web",
		);
		$url .= http_build_query($query_array);
		return $url;
	}

	/**
	 * 用code和state进行授权获取token等信息
	 * @param   数组，包含 code、state
	 * @return  对象{data:1,info:{...}}
	 * @link    http://developer.baidu.com/wiki/index.php?title=docs/social/oauth/authorization#.E8.AF.B7.E6.B1.82.E6.95.B0.E6.8D.AE.E5.8C.85.E6.A0.BC.E5.BC.8F_2
	 * @version 1.0
	 * 2013年10月14日22:32:32
	 */
	public function _oauth($array = array()) {
		if (!session_id()) {
			session_start();
		}

		if (!isset($array["code"]) || !isset($array["state"]) || !isset($_SESSION["state"])) {
			return $this->json(-1, "Illegal operation!");
		}

		$code  = $array["code"];
		$state = $array["state"];
		if ($state !== $_SESSION["state"]) {
			return $this->json(-2, "Illegal authorization!");
		}

		$url = "https://openapi.baidu.com/social/oauth/2.0/token";
		// grant_type：必须参数，此值固定为“authorization_code”；
		// code：必须参数，通过上面第一步所获得的Authorization Code；
		// client_id：必须参数，应用的API Key；
		// client_secret：必须参数，'应用的Secret Key；
		// redirect_uri：必须参数，该值必须与获取Authorization Code时传递的“redirect_uri”保持一致。
		$query_array = array(
			"grant_type"    => "authorization_code",
			"code"          => $code,
			"client_id"     => $this->api_key,
			"client_secret" => $this->secret_key,
			"redirect_uri"  => $this->redirect_uri,
		);
		$ret         = $this->post($url, $query_array);
		$json        = json_decode($ret);
		// access_token：要获取的Access Token；
		// expires_in：Access Token的有效期，以秒为单位；
		// media_type：用户登录的社会化平台标识，具体值定义可以参考“《支持的社会化平台》”一节；
		// 'media_uid：用户在“media_type”代表的社会化平台上的唯一uid/openid；
		// 'social_uid：用户在百度社会化服务当中分配的唯一用户id；
		// 'name：用户名；
		// session_key：基于http调用Social API时所需要的Session Key，其有效期与Access Token一致；
		// session_secret：基于http调用Social API时计算参数签名用的签名密钥。
		if (isset($json->error_code)) {
			return $this->json(0 - abs($json->error_code), $json->error_msg);
		} else {
			$_SESSION["access_token"]   = $json->access_token;
			$_SESSION["media_type"]     = $json->media_type;
			$_SESSION["social_uid"]     = $json->social_uid;
			$_SESSION["session_key"]    = $json->session_key;
			$_SESSION["session_secret"] = $json->session_secret;
			return $this->json(1, $json);
		}
	}

	/**
	 * 获取用户信息
	 * @return  信息对象
	 * @param   [type] [varname] [description]
	 * @link    http://developer.baidu.com/wiki/index.php?title=docs/social/api/list#.E8.8E.B7.E5.8F.96.E7.AC.AC.E4.B8.89.E6.96.B9.E5.B9.B3.E5.8F.B0.E7.94.A8.E6.88.B7.E7.9A.84.E4.BF.A1.E6.81.AF
	 * @version 1.0
	 * 2013年10月14日22:38:09
	 */
	public function user_info() {
		$url = "https://openapi.baidu.com/social/api/2.0/user/info";
		return $this->api($url);
	}

	/**
	 * API请求
	 * @param url地址
	 * @param   参数数组
	 * @return  信息对象
	 * @version 1.0
	 * 2013年10月14日22:39:05
	 */
	private function api($url = "", $params = array()) {
		if (empty($url)) {
			return $this->json(-1, "Illegal operation!");
		}

		if (!session_id()) {
			session_start();
		}

		$access_token = "";
		if (!empty($this->access_token)) {
			$access_token = $this->access_token;
		} else if (isset($_SESSION["access_token"])) {
			$access_token = $_SESSION["access_token"];
		}

		if (empty($access_token)) {
			return $this->json(-2, "Illegal authorization!");
		}

		$base = array(
			'access_token' => $access_token,
		);

		$data = $base + $params;

		$ret  = $this->post($url, $data);
		$json = json_decode($ret);

		if (isset($json->error_code)) {
			return $this->json(0 - abs($json->error_code), $json->error_msg);
		} else {
			return $this->json(1, $json);
		}
	}

	/**
	 * 返回json数据格式
	 * @param json代码
	 * @param json内容
	 * @return json对象格式
	 */
	private function json($data, $info) {
		$array = array(
			'data' => $data,
			'info' => $info,
		);
		return (object)$array;
	}

	/**
	 * get
	 * @param string $url     基于的baseUrl
	 * @param array  $keysArr 参数列表数组
	 * @return string 返回的资源内容
	 * @version 1.0
	 * 2013年10月14日22:34:29
	 */
	private function get($url, $keysArr) {
		$url = $url."?".http_build_query($keysArr);
		if (ini_get("allow_url_fopen") == "1") {
			$response = @file_get_contents($url);
		} else {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_URL, $url);
			$response = curl_exec($ch);
			curl_close($ch);
		}
		return $response;
	}

	/**
	 * post
	 * @param string $url     基于的baseUrl
	 * @param array  $keysArr 请求的参数列表
	 * @param int    $flag    标志位
	 * @return string 返回的资源内容
	 * @version 1.0
	 * 2013年10月14日22:34:44
	 */
	private function post($url, $keysArr, $flag = 0) {

		$ch = curl_init();
		if (!$flag) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $keysArr);
		curl_setopt($ch, CURLOPT_URL, $url);
		$ret = curl_exec($ch);

		curl_close($ch);
		return $ret;
	}
}