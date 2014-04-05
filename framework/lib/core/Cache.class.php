<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>
class Cache {

	/**
	 * 操作句柄
	 * @var string
	 * @access protected
	 */
	protected $handler;

	/*
	 * 缓存连接参数
	 * $options['temp']//路径
	 * $options['prefix']//缓存前缀
	 * $options['expire']//期望保存时间  0 永久保存
	 * $options['length']//长度
	 */
	protected $options = array();


	//取得缓存类实例
	static function getInstance() {
		$param = func_get_args();
		return get_instance_of(__CLASS__, 'connect', $param);
	}

	/**
	 * @param string $type    缓存类型
	 * @param array  $options 缓存选项
	 * @return mixed
	 */
	public function connect($type = '', $options = array()) {
		empty($type) && $type = C('DATA_CACHE_TYPE'); //默认缓存类
		$class = $type.'Cache';
		if (class_exists($class)) {
			$cache = new $class($options);
			return $cache;
		} else {
			halt('不存在缓存驱动类');
		}
	}

	public function __get($name) {
		return $this->get($name);
	}

	public function __set($name, $value) {
		return $this->set($name, $value);
	}

	public function __unset($name) {
		$this->rm($name);
	}

	public function setOptions($name, $value) {
		$this->options[$name] = $value;
	}

	public function getOptions($name) {
		return $this->options[$name];
	}

	public function __call($method, $args) {
		//调用缓存类型自己的方法
		if (method_exists($this->handler, $method)) {
			return call_user_func_array(array(
				$this->handler,
				$method
			), $args);
		} else {
			halt(__CLASS__.':'.$method.L('_METHOD_NOT_EXIST_'));
			return;
		}
	}
}