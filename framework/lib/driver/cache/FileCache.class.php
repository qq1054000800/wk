<?php
//上帝未满18岁 | <qq1054000800@gmail.com> | <www.phpjcw.com>
safe();
class FileCache extends Cache {

	//架构函数
	public function __construct($options = array()) {
		if (!empty($options)) {
			$this->options = $options;
		}
		$this->options['prefix'] = isset($options['prefix']) ? $options['prefix'] : '';
		$this->options['expire'] = isset($options['expire']) ? $options['expire'] : 0;
		$this->options['length'] = isset($options['length']) ? $options['length'] : 0;
	}

	/**
	 * 读取缓存
	 * @access public
	 * @param string $name 缓存变量名
	 * @return mixed
	 */
	public function get($filename) {
		if (!is_file($filename)) {
			return false;
		}
		$content = file_get_contents($filename);
		if (false !== $content) {
			$expire = (int)substr($content, 8, 12);
			if ($expire != 0 && time() > filemtime($filename) + $expire) {
				unlink($filename); //缓存过期删除缓存文件
				return false;
			}
			$content = substr($content, 20, -2); //截取字符串
			$content = unserialize($content);
			return $content;
		} else {
			return false;
		}
	}

	/**
	 * 写入缓存
	 * @access public
	 * @param string $name    缓存文件名
	 * @param mixed  $value   存储数据
	 * @param int    $expire  有效时间 0为永久
	 * @return boolen
	 */
	public function set($filename, $value, $expire = null) {
		if (is_null($expire)) {
			$expire = $this->options['expire'];
		}
		$data = serialize($value);
		//todo: 数据压缩 数据校验
		$data   = "<?php\n/*".sprintf('%012d', $expire).$data.'*/';
		$result = write_file($filename, $data);
		if ($result) {
			if ($this->options['length'] > 0) {
				// 记录缓存队列
				$this->queue($filename);
			}
			clearstatcache();
			return true;
		} else {
			return false;
		}
	}

	//删除缓存
	public function rm($name) {
		return file_exists($name) && unlink($name);
	}

	/**
	 * 清除缓存
	 * @access public
	 * @param string $name 缓存变量名
	 * @return boolen
	 */
	public function clear() {
		$path  = $this->options['temp'];
		$files = scandir($path);
		if ($files) {
			foreach ($files as $file) {
				if ($file != '.' && $file != '..' && is_dir($path.$file)) {
					array_map('unlink', glob($path.$file.'/*.*'));
				} elseif (is_file($path.$file)) {
					unlink($path.$file);
				}
			}
			return true;
		}
		return false;
	}
}