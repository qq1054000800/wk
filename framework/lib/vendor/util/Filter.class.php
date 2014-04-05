<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>
/*
 * 过滤字符串，获取需要的字符串
 * 判断字符串是否需要的格式
 * */

class Filter {
	/**
	 * 正则表达式验证数据
	 * @param $data     需要验证的数据
	 * @param $regStr   验证的正则表达式
	 * @return mixed
	 */
	public static function validate_regexp($data, $regStr) {
		return filter_var($data, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $regStr)));
	}

	/**
	 * 验证数据的大小是否在一个范围内
	 * @param      $data 需要验证的数据
	 * @param null $min  最小
	 * @param null $max  最大
	 * @return bool
	 */
	public static function validate_range($data, $min = null, $max = null) {
		return MATH::compareSize($data, $min, $max);
	}

	/**
	 * 验证数据的长度是否在一个范围内
	 * @param      $data 需要验证的数据
	 * @param null $min  最小
	 * @param null $max  最大
	 * @return bool
	 */
	public static function validate_length($data, $min = null, $max = null) {
		return MATH::compareSize(mb_strlen($data, 'utf8'), $min, $max);
	}

	/**
	 * 验证int类型
	 * @param      $data 验证int数据类型
	 * @param null $min 最小值
	 * @param null $max 最大值
	 * @return bool|mixed
	 */
	public static function validate_int($data, $min = null, $max = null) {
		if (is_null($min)) {
			return filter_var($data, FILTER_VALIDATE_INT);
		} elseif (!is_null($min) && is_null($max)) {
			return filter_var($data, FILTER_VALIDATE_INT, array('options' => array('min_range' => (int)$min)));
		} elseif (!is_null($min) && !is_null($max)) {
			return filter_var($data, FILTER_VALIDATE_INT, array(
				'options' => array(
					'min_range' => (int)$min,
					'max_range' => (int)$max
				)
			));
		}
		return false;
	}

	/* 检查是否是IP地址。NO_PRIV_RANGE是检查是否是私有地址，NO_RES_RANGE检查是否是保留IP地址。*/
	public static function checkIP($var, $range = 'all') {
		if ($range == 'all') {
			return filter_var($var, FILTER_VALIDATE_IP);
		}
		if ($range == 'public static') {
			return filter_var($var, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
		}
		if ($range == 'private') {
			if (filter_var($var, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false) {
				return $var;
			}
			return false;
		}
	}


	/**
	 * 多个连续<br/>只保留一个
	 * @param string $str 待转换的字符串
	 * @return string
	 */
	static public function merge_brs($str) {
		return preg_replace("/((<br\/?>)+)/i", "<br/>", $str);
	}

	/**
	 * 过滤字符串中<script>脚本
	 * @param string $string 待过滤的字符串
	 * @return string
	 */
	static public function strip_script($string) {
		$reg = "/<script[^>]*?>.*?<\/script>/is";
		return preg_replace($reg, '', $string);
	}

	/**
	 * 过滤字符串中<style>脚本
	 * @param string $string 待过滤的字符串
	 * @return string
	 */
	static public function strip_style($string) {
		$reg = "/<style[^>]*?>.*?<\/style>/is";
		return preg_replace($reg, '', $string);
	}

	/**
	 * 过滤字符串中<link>脚本
	 * @param string $string 待过滤的字符串
	 * @return string
	 */
	static public function strip_link($string) {
		$reg = "/<link[^>]*?>.*?<\/link>/is";
		return preg_replace($reg, '', $string);
	}


	/* 去除email里面的非法字符。*/
	public function cleanEmail($fieldName) {
		$fields = $this->processFields($fieldName);
		foreach ($fields as $fieldName) {
			$this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_EMAIL);
		}
		return $this;
	}

	/* 对URL进行编码。*/
	public function encodeURL($fieldName) {
		$fields = $this->processFields($fieldName);
		$args   = func_get_args();
		foreach ($fields as $fieldName) {
			$this->data->$fieldName =
				isset($args[1]) ? filter_var($this->data->$fieldname, FILTER_SANITIZE_ENCODE, $args[1])
					: filter_var($this->data->$fieldname, FILTER_SANITIZE_ENCODE);
		}
		return $this;
	}

	/* 去除url里面的非法字符。*/
	public function cleanURL($fieldName) {
		$fields = $this->processFields($fieldName);
		foreach ($fields as $fieldName) {
			$this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_URL);
		}
		return $this;
	}

	//获取浮点数
	public function cleanFloat($fieldName) {
		$fields = $this->processFields($fieldName);
		foreach ($fields as $fieldName) {
			$this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_NUMBER_FLOAT,
				FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
		}
		return $this;
	}

	/* 获取整型。*/
	public function cleanINT($fieldName = '') {
		$fields = $this->processFields($fieldName);
		foreach ($fields as $fieldName) {
			$this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_NUMBER_INT);
		}
		return $this;
	}

	//处理特殊字符
	public function specialChars($data) {
		return htmlspecialchars($data);
	}

	/* 设置默认值。*/
	public function setDefault($fieldName, $value) {
		if (!isset($this->data->$fieldName)) {
			return $this;
		}
		if (!isset($this->data->$fieldName) or empty($this->data->$fieldName)) {
			$this->data->$fieldName = $value;
		}
		return $this;
	}

	/* 条件设置。*/
	public function setIF($condition, $fieldName, $value) {
		if (!isset($this->data->$fieldName)) {
			return $this;
		}
		if ($condition) {
			$this->data->$fieldName = $value;
		}
		return $this;
	}

	/* 强制设置。*/
	public function setForce($fieldName, $value) {
		if (!isset($this->data->$fieldName)) {
			return $this;
		}
		$this->data->$fieldName = $value;
		return $this;
	}

	/* 删除某一个字段。*/
	public function remove($fieldName) {
		$fields = $this->processFields($fieldName);
		foreach ($fields as $fieldName) unset($this->data->$fieldName);
		return $this;
	}

	/* 添加一个字段。*/
	public function add($fieldName, $value) {
		$this->data->$fieldName = $value;
		return $this;
	}

	/* 调用回掉函数。*/
	public function callFunc($fieldName, $func) {
		$fields = $this->processFields($fieldName);
		foreach ($fields as $fieldName) {
			$this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_CALLBACK, array('options' => $func));
		}
		return $this;
	}

	/* 返回最终处理之后的数据。*/
	public function get($fieldName = '') {
		if (empty($fieldName)) {
			return $this->data;
		}
		return $this->data->$fieldName;
	}

	/* 处理传入的字段名：如果含有逗号，将其拆为数组。然后检查data变量中是否有这个字段。*/
	private function processFields($fields) {
		$fields = strpos($fields, ',') ? explode(',', str_replace(' ', '', $fields)) : array($fields);
		foreach ($fields as $key => $fieldName) {
			if (!isset($this->data->$fieldName)) {
				unset($fields[$key]);
			}
		}
		return $fields;
	}


	//html标签设置
	public static $htmlTags = array(
		'allow' => 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a',
		'ban'   => 'html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml',
	);

	/**
	+----------------------------------------------------------
	 * 转换文字中的超链接为可点击连接
	+----------------------------------------------------------
	 * @access public
	+----------------------------------------------------------
	 * @param string $string 要处理的字符串
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 */
	static public function makeLink($string) {
		$validChars   = "a-z0-9\/\-_+=.~!%@?#&;:$\|";
		$patterns     = array(
			"/(^|[^]_a-z0-9-=\"'\/])([a-z]+?):\/\/([{$validChars}]+)/ei",
			"/(^|[^]_a-z0-9-=\"'\/])www\.([a-z0-9\-]+)\.([{$validChars}]+)/ei",
			"/(^|[^]_a-z0-9-=\"'\/])ftp\.([a-z0-9\-]+)\.([{$validChars}]+)/ei",
			"/(^|[^]_a-z0-9-=\"'\/:\.])([a-z0-9\-_\.]+?)@([{$validChars}]+)/ei"
		);
		$replacements = array(
			"'\\1<a href=\"\\2://\\3\" title=\"\\2://\\3\" rel=\"external\">\\2://'.Input::truncate( '\\3' ).'</a>'",
			"'\\1<a href=\"http://www.\\2.\\3\" title=\"www.\\2.\\3\" rel=\"external\">'.Input::truncate( 'www.\\2.\\3' ).'</a>'",
			"'\\1<a href=\"ftp://ftp.\\2.\\3\" title=\"ftp.\\2.\\3\" rel=\"external\">'.Input::truncate( 'ftp.\\2.\\3' ).'</a>'",
			"'\\1<a href=\"mailto:\\2@\\3\" title=\"\\2@\\3\">'.Input::truncate( '\\2@\\3' ).'</a>'"
		);
		return preg_replace($patterns, $replacements, $string);
	}

	/**
	+----------------------------------------------------------
	 * 缩略显示字符串
	+----------------------------------------------------------
	 * @access public
	+----------------------------------------------------------
	 * @param string $string 要处理的字符串
	 * @param int    $length 缩略之后的长度
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 */
	static public function truncate($string, $length = '50') {
		if (empty($string) || empty($length) || strlen($string) < $length) {
			return $string;
		}
		$len = floor($length / 2);
		$ret = substr($string, 0, $len)." ... ".substr($string, 5 - $len);
		return $ret;
	}

	/**
	+----------------------------------------------------------
	 * 把换行转换为<br />标签
	+----------------------------------------------------------
	 * @access public
	+----------------------------------------------------------
	 * @param string $string 要处理的字符串
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 */
	static public function nl2Br($string) {
		return preg_replace("/(\015\012)|(\015)|(\012)/", "<br />", $string);
	}

	/**
	+----------------------------------------------------------
	 * 用于在textbox表单中显示html代码
	+----------------------------------------------------------
	 * @access public
	+----------------------------------------------------------
	 * @param string $string 要处理的字符串
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 */
	static function hsc($string) {
		return preg_replace(array(
			"/&amp;/i",
			"/&nbsp;/i"
		), array(
			'&',
			'&amp;nbsp;'
		), htmlspecialchars($string, ENT_QUOTES));
	}

	/**
	+----------------------------------------------------------
	 * 是hsc()方法的逆操作
	+----------------------------------------------------------
	 * @access public
	+----------------------------------------------------------
	 * @param string $text 要处理的字符串
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 */
	static function undoHsc($text) {
		return preg_replace(array(
			"/&gt;/i",
			"/&lt;/i",
			"/&quot;/i",
			"/&#039;/i",
			'/&amp;nbsp;/i'
		), array(
			">",
			"<",
			"\"",
			"'",
			"&nbsp;"
		), $text);
	}

	/**
	+----------------------------------------------------------
	 * 输出安全的html，用于过滤危险代码
	+----------------------------------------------------------
	 * @access public
	+----------------------------------------------------------
	 * @param string $text      要处理的字符串
	 * @param mixed  $allowTags 允许的标签列表，如 table|td|th|td
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 */
	static public function safeHtml($text, $allowTags = null) {
		$text = trim($text);
		//完全过滤注释
		$text = preg_replace('/<!--?.*-->/', '', $text);
		//完全过滤动态代码
		$text = preg_replace('/<\?|\?'.'>/', '', $text);
		//完全过滤js
		$text = preg_replace('/<script?.*\/script>/', '', $text);

		$text = str_replace('[', '&#091;', $text);
		$text = str_replace(']', '&#093;', $text);
		$text = str_replace('|', '&#124;', $text);
		//过滤换行符
		$text = preg_replace('/\r?\n/', '', $text);
		//br
		$text = preg_replace('/<br(\s\/)?'.'>/i', '[br]', $text);
		$text = preg_replace('/(\[br\]\s*){10,}/i', '[br]', $text);
		//过滤危险的属性，如：过滤on事件lang js
		while (preg_match('/(<[^><]+)(lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i', $text, $mat)) {
			$text = str_replace($mat[0], $mat[1], $text);
		}
		while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
			$text = str_replace($mat[0], $mat[1].$mat[3], $text);
		}
		if (empty($allowTags)) {
			$allowTags = self::$htmlTags['allow'];
		}
		//允许的HTML标签
		$text = preg_replace('/<('.$allowTags.')( [^><\[\]]*)>/i', '[\1\2]', $text);
		//过滤多余html
		if (empty($banTag)) {
			$banTag = self::$htmlTags['ban'];
		}
		$text = preg_replace('/<\/?('.$banTag.')[^><]*>/i', '', $text);
		//过滤合法的html标签
		while (preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i', $text, $mat)) {
			$text = str_replace($mat[0], str_replace('>', ']', str_replace('<', '[', $mat[0])), $text);
		}
		//转换引号
		while (preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i', $text, $mat)) {
			$text = str_replace($mat[0], $mat[1].'|'.$mat[3].'|'.$mat[4], $text);
		}
		//空属性转换
		$text = str_replace('\'\'', '||', $text);
		$text = str_replace('""', '||', $text);
		//过滤错误的单个引号
		while (preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i', $text, $mat)) {
			$text = str_replace($mat[0], str_replace($mat[1], '', $mat[0]), $text);
		}
		//转换其它所有不合法的 < >
		$text = str_replace('<', '&lt;', $text);
		$text = str_replace('>', '&gt;', $text);
		$text = str_replace('"', '&quot;', $text);
		//反转换
		$text = str_replace('[', '<', $text);
		$text = str_replace(']', '>', $text);
		$text = str_replace('|', '"', $text);
		//过滤多余空格
		$text = str_replace('  ', ' ', $text);
		return $text;
	}

	/**
	+----------------------------------------------------------
	 * 删除html标签，得到纯文本。可以处理嵌套的标签
	+----------------------------------------------------------
	 * @access public
	+----------------------------------------------------------
	 * @param string $string 要处理的html
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 */
	static public function deleteHtmlTags($string) {
		while (strstr($string, '>')) {
			$currentBeg   = strpos($string, '<');
			$currentEnd   = strpos($string, '>');
			$tmpStringBeg = @substr($string, 0, $currentBeg);
			$tmpStringEnd = @substr($string, $currentEnd + 1, strlen($string));
			$string       = $tmpStringBeg.$tmpStringEnd;
		}
		return $string;
	}

	/**
	+----------------------------------------------------------
	 * 处理文本中的换行
	+----------------------------------------------------------
	 * @access public
	+----------------------------------------------------------
	 * @param string $string 要处理的字符串
	 * @param mixed  $br     对换行的处理，
	 *                       false：去除换行；true：保留原样；string：替换成string
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 */
	static public function nl2($string, $br = '<br />') {
		if ($br == false) {
			$string = preg_replace("/(\015\012)|(\015)|(\012)/", '', $string);
		} elseif ($br != true) {
			$string = preg_replace("/(\015\012)|(\015)|(\012)/", $br, $string);
		}
		return $string;
	}

}