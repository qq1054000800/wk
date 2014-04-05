<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>
safe();

/*
 * 快捷函数
 * */
//引入其他函数库
//include COMMON_PATH.'/build.php';
include COMMON_PATH.'/system.php';
//include COMMON_PATH.'/file.php';
//include COMMON_PATH.'/string.php';
//include COMMON_PATH.'/array.php';

/**
 * A函数用于实例化控制器
 * @param string $name   CONTROLLER_NAME
 * @param string $layer  控制层名称
 * @return Controller obj|false
 */
function A($name, $layer = '') {
	static $_controllers = array(); //缓存实例化的控制器类
	$layer = $layer ? $layer : C('DEFAULT_C_LAYER'); //默认Controller
	if (isset($_controllers[$name])) {
		return $_controllers[$name];
	}
	$_class_name = basename($name.$layer); //类名 todo:注意类名的规范性验证
	if (class_exists($_class_name)) {
		$controller          = new $_class_name(); //创建控制器对象,如果该类未定义时，才会调用autoload函数
		$_controllers[$name] = $controller; //缓存控制器对象
		return $controller;
	}
	return false;
}

/**
 * 获取或设置配置，键值对数组，键名小写，键值不变
 * 系统定义的配置文件和数据库中的配置项
 * @param null $name
 * @param null $value
 */
function C($name = null, $value = null) {
	static $_config = array();
	if (empty($name)) {
		return $_config;
	}
	if (is_string($name)) {
		if (!strpos($name, '.')) {
			$name = strtolower($name);
			if (is_null($value)) {
				return isset($_config[$name]) ? $_config[$name] : null;
			}
			$_config[$name] = $value;
			return;
		}
		// 二维数组设置和获取支持
		$name    = explode('.', $name);
		$name[0] = strtolower($name[0]);
		if (is_null($value)) {
			return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : null;
		}
		$_config[$name[0]][$name[1]] = $value;
		return;
	}
	// 批量设置
	if (is_array($name)) {
		$_config = array_merge($_config, array_change_key_case($name)); //合并参数，键名统一小写
		return;
	}
	return null; // 避免非法参数
}

/**
 * 快速文件数据读取和保存 针对简单类型数据 字符串、数组
 * @param string $name  缓存名称
 * @param mixed  $value 缓存值
 * @param string $path  缓存路径
 * @return mixed
 */
function F($name, $value = '') {
	static $_cache = array();
	$path     = CACHE_PATH.'/'.(GROUP_NAME ? GROUP_NAME : 'common'); //缓存保存目录
	$filename = $path.'/'.$name.'.php'; //保存的文件名
	if ('' !== $value) {
		//获取配置
		if (is_null($value)) {
			// 删除缓存
			return false !== strpos($name, '*') ? array_map("unlink", glob($filename)) : unlink($filename);
		} else {
			// 缓存数据
			$dir = dirname($filename);
			// 目录不存在则创建
			if (!is_dir($dir)) {
				mkdir($dir, 0755, true);
			}
			$_cache[$name] = $value;
			return file_put_contents($filename, strip_whitespace("<?php\treturn ".var_export($value, true).";?>"));
		}
	}
	if (isset($_cache[$name])) {
		return $_cache[$name];
	}
	// 获取缓存数据
	if (is_file($filename)) {
		$value         = include $filename;
		$_cache[$name] = $value;
	} else {
		$value = false;
	}
	return $value;
}

/**
 * @param string $begin //开始的标志
 * @param string $end   //结束的表示
 * @param int    $dec   //$dec=int时 返回开始和结束的时间差 保留的小数点位数，$dec='m'时,开始和结束的内存差，单位是kb
 * @return string
 */
function G($begin, $end = '', $dec = 4) {
	static $_timeInfo = array(); //变量的赋值只在变量第一次初始化调用
	static $_memInfo = array();
	if (empty($end)) {
		//记录时间和内存
		$_timeInfo[$begin] = microtime(true);
		$_memInfo[$begin]  = memory_get_usage();
	} else {
		//输出时间差和内存差
		if (isset($_timeInfo[$end]) && isset($_memInfo[$end])) {
			if ($dec == 'm') {
				return number_format(($_memInfo[$end] - $_memInfo[$end]) / 1024); //返回kb
			}
			return number_format($_timeInfo[$end] - $_timeInfo[$begin], $dec); //返回时间差
		}
	}
}

//返回过滤后的字符串
function I($str = '') {
	if (!$str) {
		return false;
	}
	$str = strip_tags(trim($str)); //去除html/php标签
	$str = str_replace(array(
		'\\',
		';',
		'\'',
		'%2527',
		'%27',
		'%20',
		'&',
		'"',
		'<',
		'>',
		'exp',
		'or'
	), array(
		'',
		'',
		'',
		'',
		'',
		'',
		'&amp;',
		'&quot;',
		'&lt;',
		'&gt;',
		'',
		''
	), $str);
	return $str;
}

/**
 * 获取和设置语言定义
 * @param string|array $name  语言变量
 * @param string       $value 语言值
 * @return mixed
 */
function L($name = null, $value = null) {
	static $_lang = array();
	// 空参数返回所有定义
	if (empty($name)) {
		return $_lang;
	}
	// 判断语言获取(或设置)
	// 若不存在,直接返回全大写$name
	if (is_string($name)) {
		if (is_null($value)) {
			return isset($_lang[$name]) ? $_lang[$name] : $name;
		}
		$_lang[$name] = $value; // 语言定义
		return;
	}
	// 批量定义
	if (is_array($name)) {
		$_lang = array_merge($_lang, $name);
	}
	return;
}

/**
 * 根据表名实例化模型表
 * @param string $name        模型名称 User 返回UserModel 不设置返回DB
 * @param string $tablePrefix 表前缀
 * @param mixed  $connection  数据库连接信息
 * @return Model
 */
function M($name = '', $tablePrefix = '', $connection = '') {
	static $_model = array(); //根据表名+连接缓存模型，可以缓存不同的表模型。表模型不多~ 都是普通模型 实例化的Model.class.php
	$tablePrefix = $tablePrefix ? $tablePrefix : TABLEPRE;
	$_guid       = $tablePrefix.$name.($connection ? to_guid_string($connection) : ''); //缓存键名
	if ($name === '') {
		if (!isset($_model['defaultModel'])) {
			$_model['defaultModel'] = DB::getInstance();
		}
		return $_model['defaultModel'];
	}
	if (!isset($_model[$_guid])) {
		$__classname = $name.'Model';
		if (class_exists($__classname)) {
			$_model[$_guid] = new $__classname($name, $tablePrefix, $connection);
		} else {
			$_model[$_guid] = new Model($name, $tablePrefix, $connection);
		}
	}
	return $_model[$_guid];
}

/**
 * 设置和获取统计数据
 * 使用方法:
 * <code>
 * N('db',1); // 记录数据库操作次数
 * N('read',1); // 记录读取次数
 * echo N('db'); // 获取当前页面数据库的所有操作次数
 * echo N('read'); // 获取当前页面读取次数
 * </code>
 * @param string  $key  标识位置
 * @param integer $step 步进值
 * @return mixed
 */
function N($key, $step = 0, $save = false) {
	static $_num = array();
	if (!isset($_num[$key])) {
		$_num[$key] = (false !== $save) ? S('N_'.$key) : 0;
	}
	if (empty($step)) {
		return $_num[$key];
	} else {
		$_num[$key] = $_num[$key] + (int)$step;
	}
	if (false !== $save) {
		S('N_'.$key, $_num[$key], $save);
	}
}

/**
 * 缓存管理
 * @param mixed $name    缓存路径 S(CACHE_COMMON_PATH.'/_fileCache/db_config.php', $_temp_res);
 * @param mixed $value   缓存值
 * @param mixed $options 缓存配置参数
 * @return mixed
 */
function S($name, $value = '', $options = null) {
	static $defaultCache = ''; //默认缓存对象
	if (is_array($options)) {
		// 定制缓存对象
		$type  = isset($options['type']) ? $options['type'] : '';
		$cache = Cache::getInstance($type, $options);
	} else {
		$cache = $defaultCache ? $defaultCache : Cache::getInstance(); //默认缓存对象
	}
	if ('' === $value) {
		return $cache->get($name); // 获取缓存
	} elseif (is_null($value)) {
		return $cache->rm($name); // 删除缓存
	} else {
		if (is_array($options)) {
			$expire = isset($options['expire']) ? $options['expire'] : NULL; //缓存期望保存时间
		} else {
			$expire = is_numeric($options) ? $options : NULL;
		}
		return $cache->set($name, $value, $expire); //保存缓存
	}
}

/**
 * URL组装 支持不同URL模式
 * @param string $url
 * @param string $par      附加的参数，显示在url中
 * @param string $suffix   伪静态后缀，默认为true表示获取配置值
 * @return string
 */
function U($url = '', $par = '', $suffix = '') {
	$info   = parse_url($url); // 解析url信息
	$url    = !empty($info['path']) ? $info['path'] : ACTION_NAME; //操作信息
	$params = isset($info['query']) ? $info['query'] : ''; //地址里面的参数
	$anchor = isset($info['fragment']) ? $info['fragment'] : ''; // 解析锚点
	//解析url
	$_urls    = explode('/', $url); //临时变量
	$urls_arr = array(); //url路径数组
	switch (count($_urls)) {
		case 1:
			GROUP_NAME == DEFAULT_GROUP || $urls_arr[] = GROUP_NAME;
			$urls_arr[] = CONTROLLER_NAME;
			$urls_arr[] = $_urls[0];
			break;
		case 2:
			GROUP_NAME == DEFAULT_GROUP || $urls_arr[] = GROUP_NAME;
			$urls_arr[] = $_urls[0];
			$urls_arr[] = $_urls[1];
			break;
		case 3:
			$_urls[0] == DEFAULT_GROUP || $urls_arr[] = $_urls[0];
			$urls_arr[] = $_urls[1];
			$urls_arr[] = $_urls[2];
			break;
	}
	$url = __APP__.'/'.implode(C('URL_PATHINFO_DEPR'), $urls_arr); //url内容
	$url .= $par ? '/'.$par : ''; //附加参数
	$url .= $suffix ? $suffix : C('URL_HTML_SUFFIX'); //伪静态
	$url .= empty($params) ? '' : '?'.$params; // 添加参数
	$url .= $anchor ? '#'.$anchor : '';
	return $url;
}


//输出变量
function Y($str = false) {
	echo $str ? $str : '';
}

//加密函数
function Z($str, $qz = 'kwx') {
	return sha1($qz.md5($str).$qz);
}


/**
 * 取得对象实例 支持调用类的静态方法
 * @param string $name   类名 默认Db
 * @param string $method 方法名，如果为空则返回实例化对象
 * @param array  $args   连接参数
 * @return object
 */
function get_instance_of($name, $method = '', $args = array()) {
	static $_instance = array(); //缓存DB对象
	$_guid = empty($args) ? $name.$method : $name.$method.to_guid_string($args);
	if (!isset($_instance[$_guid])) {
		if (class_exists($name)) {
			$o = new $name(); //创建Db对象 Db.class.php
			if (method_exists($o, $method)) {
				if (!empty($args)) {
					$_instance[$_guid] = call_user_func_array(array(
						&$o,
						$method
					), $args);
				} else {
					$_instance[$_guid] = $o->$method();
				}
			} else {
				$_instance[$_guid] = $o;
			}
		} else {
			halt('Db类不存在！');
		}
	}
	return $_instance[$_guid];
}

/**
 * session管理函数
 * @param string|array $name  session名称 如果为数组则表示进行session设置
 * @param mixed        $value session值
 * @return mixed
 */
function session($name, $value = '') {
	if ($name) {
		if ($value) {
			$_SESSION[$name] = $value;
		} else {
			return $_SESSION[$name];
		}
	} else {
		return $_SESSION;
	}
}

/**
 * Cookie 设置、获取、删除
 * @param string $name    cookie名称
 * @param mixed  $value   cookie值
 * @param mixed  $options cookie参数
 * @return mixed
 */
function cookie($name, $value = '', $option = null) {
	// 默认设置
	$config = array(
		'prefix' => C('COOKIE_PREFIX'),
		// cookie 名称前缀
		'expire' => C('COOKIE_EXPIRE'),
		// cookie 保存时间
		'path'   => C('COOKIE_PATH'),
		// cookie 保存路径
		'domain' => C('COOKIE_DOMAIN'),
		// cookie 有效域名
	);
	// 参数设置(会覆盖黙认设置)
	if (!is_null($option)) {
		if (is_numeric($option)) {
			$option = array('expire' => $option);
		} elseif (is_string($option)) {
			parse_str($option, $option);
		}
		$config = array_merge($config, array_change_key_case($option));
	}
	// 清除指定前缀的所有cookie
	if (is_null($name)) {
		if (empty($_COOKIE)) {
			return;
		}
		// 要删除的cookie前缀，不指定则删除config设置的指定前缀
		$prefix = empty($value) ? $config['prefix'] : $value;
		if (!empty($prefix)) { // 如果前缀为空字符串将不作处理直接返回
			foreach ($_COOKIE as $key => $val) {
				if (0 === stripos($key, $prefix)) {
					setcookie($key, '', time() - 3600, $config['path'], $config['domain']);
					unset($_COOKIE[$key]);
				}
			}
		}
		return;
	}
	$name = $config['prefix'].$name;
	if ('' === $value) {
		if (isset($_COOKIE[$name])) {
			$value = $_COOKIE[$name];
			if (0 === strpos($value, 'think:')) {
				$value = substr($value, 6);
				return array_map('urldecode', json_decode(MAGIC_QUOTES_GPC ? stripslashes($value) : $value, true));
			} else {
				return $value;
			}
		} else {
			return null;
		}
	} else {
		if (is_null($value)) {
			setcookie($name, '', time() - 3600, $config['path'], $config['domain']);
			unset($_COOKIE[$name]); // 删除指定cookie
		} else {
			// 设置cookie
			if (is_array($value)) {
				$value = 'think:'.json_encode(array_map('urlencode', $value));
			}
			$expire = !empty($config['expire']) ? time() + intval($config['expire']) : 0;
			setcookie($name, $value, $expire, $config['path'], $config['domain']);
			$_COOKIE[$name] = $value;
		}
	}
}
