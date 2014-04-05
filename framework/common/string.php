<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>

//todo:删除！

function putstr($str) {
	file_put_contents('C://index.php', $str, FILE_APPEND);
}


//UTF-8/GBK都支持的汉字截取函数 cut_str
function cut_str($string, $start = 0, $sublen, $suffix = '', $code = 'UTF-8') {
	if ($code == 'UTF-8') {
		$pa =
			"/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
		preg_match_all($pa, $string, $t_string);
		if (count($t_string[0]) - $start > $sublen) {
			return join('', array_slice($t_string[0], $start, $sublen)).$suffix;
		}
		return join('', array_slice($t_string[0], $start, $sublen));
	} else {
		$start  = $start * 2;
		$sublen = $sublen * 2;
		$strlen = strlen($string);
		$tmpstr = '';
		for ($i = 0; $i < $strlen; $i++) {
			if ($i >= $start && $i < ($start + $sublen)) {
				if (ord(substr($string, $i, 1)) > 129) {
					$tmpstr .= substr($string, $i, 2);
				} else {
					$tmpstr .= substr($string, $i, 1);
				}
			}
			if (ord(substr($string, $i, 1)) > 129) {
				$i++;
			}
		}
		if (strlen($tmpstr) < $strlen) {
			$tmpstr .= $suffix;
		}
		return $tmpstr;
	}
}

//转换为正确的编码
function safeEncoding($string, $outEncoding = 'UTF-8') {
	$encoding = "UTF-8";
	for ($i = 0; $i < strlen($string); $i++) {
		if (ord($string{$i}) < 128) {
			continue;
		}
		if ((ord($string{$i}) & 224) == 224) {
			//第一个字节判断通过
			$char = $string{++$i};
			if ((ord($char) & 128) == 128) {
				//第二个字节判断通过
				$char = $string{++$i};
				if ((ord($char) & 128) == 128) {
					$encoding = "UTF-8";
					break;
				}
			}
		}
		if ((ord($string{$i}) & 192) == 192) {
			//第一个字节判断通过
			$char = $string{++$i};
			if ((ord($char) & 128) == 128) {
				//第二个字节判断通过
				$encoding = "GB2312";
				break;
			}
		}
	}
	if (strtoupper($encoding) == strtoupper($outEncoding)) {
		return $string;
	} else {
		return iconv($encoding, $outEncoding, $string);
	}
}

/**
 * 正则匹配字符串
 * @param $rule 匹配的模式
 * @param $value 需要匹配的字符串
 * @return bool
 * 字符串满足匹配的模式 返回 true
 */
function regex($rule, $value) {
	$validate = array(
		'require'  => '/.+/',
		'email'    => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
		'url'      => '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
		'currency' => '/^\d+(\.\d+)?$/',
		'number'   => '/^\d+$/',
		'zip'      => '/^\d{6}$/',
		'integer'  => '/^[-\+]?\d+$/',
		'double'   => '/^[-\+]?\d+(\.\d+)?$/',
		'english'  => '/^[A-Za-z]+$/',
	);
	// 检查是否有内置的正则表达式
	if (isset($validate[strtolower($rule)])) {
		$rule = $validate[strtolower($rule)];
	}
	return preg_match($rule, $value) === 1;
}
