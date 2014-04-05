<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>

/*
 * 扩展系统函数，将系统函数查询结果缓存！
 * */

/**
 * 判断文件名是否存在
 * @param $name 文件名
 * @return mixed
 */
function isFile($name) {
	static $filenames = array();
	if (empty($filenames[$name])) {
		$filenames[$name] = is_file($name);
	}
	return $filenames[$name];
}


/**
 * 根据PHP各种类型变量生成唯一标识号
 * @param mixed $mix 变量
 * @return string
 */
function to_guid_string($mix) {
	if (is_object($mix) && function_exists('spl_object_hash')) {
		return spl_object_hash($mix);
	} elseif (is_resource($mix)) {
		$mix = get_resource_type($mix).strval($mix);
	} else {
		$mix = serialize($mix);
	}
	return md5($mix);
}


/**
 * 错误输出
 * @param mixed $error 错误
 * @return void
 */
function halt($error) {
	//todo:APP_DEBUG才输出全部错误信息
	$e            = array();
	$trace        = debug_backtrace(); //获取错误信息
	$e['message'] = $error;
	$e['file']    = $trace[0]['file'];
	$e['line']    = $trace[0]['line'];
	ob_start();
	debug_print_backtrace();
	$e['trace'] = ob_get_clean();
	include C('TMPL_EXCEPTION_FILE'); // 包含异常页面模板，然后退出
	exit;
}

// 过滤表单中的表达式
function filter_exp(&$value) {
	if (in_array(strtolower($value), array(
		'exp',
		'or'
	))
	) {
		$value .= ' ';
	}
}

/**
 * XML编码
 * @param mixed  $data     数据
 * @param string $encoding 数据编码
 * @param string $root     根节点名
 * @return string
 */
function xml_encode($data, $encoding = 'utf-8', $root = 'think') {
	$xml = '<?xml version="1.0" encoding="'.$encoding.'"?>';
	$xml .= '<'.$root.'>';
	$xml .= data_to_xml($data);
	$xml .= '</'.$root.'>';
	return $xml;
}

/**
 * 数据XML编码
 * @param mixed $data 数据
 * @return string
 */
function data_to_xml($data) {
	$xml = '';
	foreach ($data as $key => $val) {
		is_numeric($key) && $key = "item id=\"$key\"";
		$xml .= "<$key>";
		$xml .= (is_array($val) || is_object($val)) ? data_to_xml($val) : $val;
		list($key,) = explode(' ', $key);
		$xml .= "</$key>";
	}
	return $xml;
}

/**
 * URL重定向
 * @param string  $url  重定向的URL地址
 * @param integer $time 重定向的等待时间（秒）
 * @param string  $msg  重定向前的提示信息
 * @return void
 */
function redirect($url, $time = 0, $msg = '') {
	if (!headers_sent()) {
		if (0 === $time) {
			header('Location: '.$url);
		} else {
			header("refresh:{$time};url={$url}");
			echo($msg);
		}
		exit();
	} else {
		$str = "<meta http-equiv='Refresh' content='".$time.";URL=".$url."'>";
		if ($time != 0) {
			$str .= $msg;
		}
		exit($str);
	}
}


/**
 * 发送HTTP状态
 * @param integer $code 状态码
 * @return void
 */
function send_http_status($code) {
	static $_status = array( // Informational 1xx
		100 => 'Continue',
		101 => 'Switching Protocols',
		// Success 2xx
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		// Redirection 3xx
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Moved Temporarily ',
		// 1.1
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		// 306 is deprecated but reserved
		307 => 'Temporary Redirect',
		// Client Error 4xx
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		// Server Error 5xx
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		509 => 'Bandwidth Limit Exceeded'
	);
	if (isset($_status[$code])) {
		header('HTTP/1.1 '.$code.' '.$_status[$code]);
		// 确保FastCGI模式下正常
		header('Status:'.$code.' '.$_status[$code]);
	}
}

/**
 * 去除代码中的空白和注释
 * @param string $content 代码内容
 * @return string
 */
function strip_whitespace($content) {
	$stripStr   = '';
	$tokens     = token_get_all($content); //分离php源码
	$last_space = false;
	for ($i = 0, $j = count($tokens); $i < $j; $i++) {
		if (is_string($tokens[$i])) {
			$last_space = false;
			$stripStr .= $tokens[$i];
		} else {
			switch ($tokens[$i][0]) {
				//过滤各种PHP注释
				case T_COMMENT:
				case T_DOC_COMMENT:
					break;
				//过滤空格
				case T_WHITESPACE:
					if (!$last_space) {
						$stripStr .= ' ';
						$last_space = true;
					}
					break;
				case T_START_HEREDOC:
					$stripStr .= "<<<THINK\n";
					break;
				case T_END_HEREDOC:
					$stripStr .= "THINK;\n";
					for ($k = $i + 1; $k < $j; $k++) {
						if (is_string($tokens[$k]) && $tokens[$k] == ';') {
							$i = $k;
							break;
						} else if ($tokens[$k][0] == T_CLOSE_TAG) {
							break;
						}
					}
					break;
				default:
					$last_space = false;
					$stripStr .= $tokens[$i][1];
			}
		}
	}
	return $stripStr;
}