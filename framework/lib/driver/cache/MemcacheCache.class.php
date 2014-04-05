<?php
safe();
class MemcacheCache extends Cache {
	/**
	 * 架构函数
	 * @param array $options 缓存参数
	 * @access public
	 */
	function __construct($options = array()) {
		if (!extension_loaded('memcache')) {
			throw_exception('没有安装memcached扩展！');
		}

		$options = array_merge(array(
			'host'       => C('MEMCACHE_HOST') ? C('MEMCACHE_HOST') : '127.0.0.1',
			'port'       => C('MEMCACHE_PORT') ? C('MEMCACHE_PORT') : 11211,
			'timeout'    => C('DATA_CACHE_TIMEOUT') ? C('DATA_CACHE_TIMEOUT') : false,
			'persistent' => false,
		), $options);

		$this->options           = $options;
		$this->options['expire'] = isset($options['expire']) ? $options['expire'] : 0; //过期时间
		$this->options['prefix'] = isset($options['prefix']) ? $options['prefix'] : ''; //数据前缀
		$this->options['length'] = isset($options['length']) ? $options['length'] : 0;
		$func                    = $options['persistent'] ? 'pconnect' : 'connect';
		$this->handler           = new Memcache(); //数据操作句柄
		$options['timeout'] === false ? @$this->handler->$func($options['host'], $options['port'])
			: @$this->handler->$func($options['host'], $options['port'], $options['timeout']);
	}

	/**
	 * 读取缓存
	 * @access public
	 * @param string $name 缓存变量名
	 * @return mixed
	 */
	public function get($name) {
		return $this->handler->get($this->options['prefix'].$name);
	}

	/**
	 * 写入缓存
	 * @access public
	 * @param string  $name    缓存变量名
	 * @param mixed   $value   存储数据
	 * @param integer $expire  有效时间（秒）
	 * @return boolen
	 */
	public function set($name, $value, $expire = null) {
		if (is_null($expire)) {
			$expire = $this->options['expire'];
		}
		$name = $this->options['prefix'].$name;
		if ($this->handler->set($name, $value, 0, $expire)) {
			if ($this->options['length'] > 0) {
				// 记录缓存队列
				$this->queue($name);
			}
			return true;
		}
		return false;
	}

	/**
	 * 删除缓存
	 * @access public
	 * @param string $name 缓存变量名
	 * @return boolen
	 */
	public function rm($name, $ttl = false) {
		$name = $this->options['prefix'].$name;
		return $ttl === false ? $this->handler->delete($name) : $this->handler->delete($name, $ttl);
	}

	/**
	 * 清除缓存
	 * @access public
	 * @return boolen
	 */
	public function clear() {
		return $this->handler->flush();
	}
}