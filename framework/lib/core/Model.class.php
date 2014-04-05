<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>
class Model {
	protected $db = null; // 当前数据库操作对象
	protected $name = ''; // 模型名称 如：Category 代表表名
	protected $tablePrefix = ''; // 数据表前缀
	protected $field = ''; // 字段
	protected $fieldInfo = array(); //字段信息
	protected $pk = 'id'; // 主键名称
	protected $tableName = ''; // 实际数据表名（包含表前缀）
	protected $dbName = ''; // 数据库名称

	// 当前使用的扩展模型
	protected $connection = ''; //数据库配置
	protected $error = ''; // 最近错误信息
	protected $data = array(); // 数据信息
	// 查询表达式参数
	protected $options = array();

	/**
	 * 数据库初始化操作
	 * @access public
	 * @param string $name        模型名称
	 * @param string $tablePrefix 表前缀
	 * @param mixed  $connection  数据库连接信息
	 */
	public function __construct($name = '', $tablePrefix = '', $connection = '') {
		$this->_init(); //创建控制器的初始化函数
		$this->name        = $name ? $name : CONTROLLER_NAME; // 获取模型名称 表名的大写
		$this->tablePrefix = $this->tablePrefix ? $this->tablePrefix : C('DB_PREFIX'); // 设置表前缀
		$this->db($connection); // 数据库初始化操作
	}

	// 回调方法 初始化模型
	protected function _init() { }


	//创建数据库对象
	public function db($connection = '') {
		if ($this->db) {
			return $this->db;
		}
		if ($connection == '' || $connection) {
			$this->db = Db::getInstance($connection);
		} elseif ($connection === null) {
			$this->db->close(); // 关闭数据库连接
			unset($this->db);
			return;
		}
		$this->_after_db();
		if (!empty($this->name)) {
			//缓存字段信息
			//$this->_checkTableInfo();
		}
		return $this;
	}

	// 数据库创建后回调方法
	protected function _after_db() { }


	/**
	 * 动态切换扩展模型
	 * @access public
	 * @param string $type 模型类型名称
	 * @param mixed  $vars 要传入扩展模型的属性变量
	 * @return Model
	 */
	public function switchModel($type, $vars = array()) {
		$class = ucwords(strtolower($type)).'Model';
		if (!class_exists($class)) {
			halt($class.L('_MODEL_NOT_EXIST_'));
		}
		// 实例化扩展模型
		$this->_extModel = new $class($this->name);
		if (!empty($vars)) {
			// 传入当前模型的属性到扩展模型
			foreach ($vars as $var) $this->_extModel->setProperty($var, $this->$var);
		}
		return $this->_extModel;
	}

	/**
	 * 对保存到数据库的数据进行处理
	 * @access protected
	 * @param mixed $data 要操作的数据
	 * @return boolean
	 */
	protected function _facade($data) {
		// 检查非数据字段
		if (!empty($this->fields)) {
			foreach ($data as $key => $val) {
				if (!in_array($key, $this->fields, true)) {
					unset($data[$key]);
				} elseif (is_scalar($val)) {
					// 字段类型检查
					$this->_parseType($data, $key);
				}
			}
		}
		// 安全过滤
		if (!empty($this->options['filter'])) {
			$data = array_map($this->options['filter'], $data);
			unset($this->options['filter']);
		}
		$this->_before_write($data);
		return $data;
	}

	protected function returnResult($data, $type = '') {
		if ($type) {
			if (is_callable($type)) {
				return call_user_func($type, $data);
			}
			switch (strtolower($type)) {
				case 'json':
					return json_encode($data);
				case 'xml':
					return xml_encode($data);
			}
		}
		return $data;
	}


	/**
	 * 创建数据对象 但不保存到数据库
	 * @access public
	 * @param mixed  $data 创建数据
	 * @param string $type 状态
	 * @return mixed
	 */
	public function create22($data = '', $type = '') {
		// 如果没有传值默认取POST数据
		if (empty($data)) {
			$data = $_POST;
		} elseif (is_object($data)) {
			$data = get_object_vars($data);
		}
		// 验证数据
		if (empty($data) || !is_array($data)) {
			$this->error = L('_DATA_TYPE_INVALID_');
			return false;
		}

		// 检查字段映射
		$data = $this->parseFieldsMap($data, 0);

		// 状态
		$type = $type ? $type : (!empty($data[$this->getPk()]) ? self::MODEL_UPDATE : self::MODEL_INSERT);

		// 检测提交字段的合法性
		if (isset($this->options['field'])) { // $this->field('field1,field2...')->create()
			$fields = $this->options['field'];
			unset($this->options['field']);
		} elseif ($type == self::MODEL_INSERT && isset($this->insertFields)) {
			$fields = $this->insertFields;
		} elseif ($type == self::MODEL_UPDATE && isset($this->updateFields)) {
			$fields = $this->updateFields;
		}
		if (isset($fields)) {
			if (is_string($fields)) {
				$fields = explode(',', $fields);
			}
			// 判断令牌验证字段
			if (C('TOKEN_ON')) {
				$fields[] = C('TOKEN_NAME');
			}
			foreach ($data as $key => $val) {
				if (!in_array($key, $fields)) {
					unset($data[$key]);
				}
			}
		}

		// 数据自动验证
		if (!$this->autoValidation($data, $type)) {
			return false;
		}

		// 表单令牌验证
		if (!$this->autoCheckToken($data)) {
			$this->error = L('_TOKEN_ERROR_');
			return false;
		}

		// 验证完成生成数据对象
		if ($this->autoCheckFields) { // 开启字段检测 则过滤非法字段数据
			$fields = $this->getDbFields();
			foreach ($data as $key => $val) {
				if (!in_array($key, $fields)) {
					unset($data[$key]);
				} elseif (MAGIC_QUOTES_GPC && is_string($val)) {
					$data[$key] = stripslashes($val);
				}
			}
		}

		// 创建完成对数据进行自动处理
		$this->autoOperation($data, $type);
		// 赋值当前数据对象
		$this->data = $data;
		// 返回创建的数据以供其他调用
		return $data;
	}

	// 自动表单令牌验证
	// TODO  ajax无刷新多次提交暂不能满足
	public function autoCheckToken($data) {
		// 支持使用token(false) 关闭令牌验证
		if (isset($this->options['token']) && !$this->options['token']) {
			return true;
		}
		if (C('TOKEN_ON')) {
			$name = C('TOKEN_NAME');
			if (!isset($data[$name]) || !isset($_SESSION[$name])) { // 令牌数据无效
				return false;
			}

			// 令牌验证
			list($key, $value) = explode('_', $data[$name]);
			if ($value && $_SESSION[$name][$key] === $value) { // 防止重复提交
				unset($_SESSION[$name][$key]); // 验证完成销毁session
				return true;
			}
			// 开启TOKEN重置
			if (C('TOKEN_RESET')) {
				unset($_SESSION[$name][$key]);
			}
			return false;
		}
		return true;
	}

	/**
	 * 自动表单处理
	 * @access public
	 * @param array  $data 创建数据
	 * @param string $type 创建类型
	 * @return mixed
	 */
	private function autoOperation(&$data, $type) {
		if (!empty($this->options['auto'])) {
			$_auto = $this->options['auto'];
			unset($this->options['auto']);
		} elseif (!empty($this->_auto)) {
			$_auto = $this->_auto;
		}
		// 自动填充
		if (isset($_auto)) {
			foreach ($_auto as $auto) {
				// 填充因子定义格式
				// array('field','填充内容','填充条件','附加规则',[额外参数])
				if (empty($auto[2])) {
					$auto[2] = self::MODEL_INSERT;
				} // 默认为新增的时候自动填充
				if ($type == $auto[2] || $auto[2] == self::MODEL_BOTH) {
					switch (trim($auto[3])) {
						case 'function': //  使用函数进行填充 字段的值作为参数
						case 'callback': // 使用回调方法
							$args = isset($auto[4]) ? (array)$auto[4] : array();
							if (isset($data[$auto[0]])) {
								array_unshift($args, $data[$auto[0]]);
							}
							if ('function' == $auto[3]) {
								$data[$auto[0]] = call_user_func_array($auto[1], $args);
							} else {
								$data[$auto[0]] = call_user_func_array(array(
									&$this,
									$auto[1]
								), $args);
							}
							break;
						case 'field': // 用其它字段的值进行填充
							$data[$auto[0]] = $data[$auto[1]];
							break;
						case 'ignore': // 为空忽略
							if ('' === $data[$auto[0]]) {
								unset($data[$auto[0]]);
							}
							break;
						case 'string':
						default: // 默认作为字符串填充
							$data[$auto[0]] = $auto[1];
					}
					if (false === $data[$auto[0]]) {
						unset($data[$auto[0]]);
					}
				}
			}
		}
		return $data;
	}

	/**
	 * 自动表单验证
	 * @access protected
	 * @param array  $data 创建数据
	 * @param string $type 创建类型
	 * @return boolean
	 */
	protected function autoValidation($data, $type) {
		if (!empty($this->options['validate'])) {
			$_validate = $this->options['validate'];
			unset($this->options['validate']);
		} elseif (!empty($this->_validate)) {
			$_validate = $this->_validate;
		}
		// 属性验证
		if (isset($_validate)) { // 如果设置了数据自动验证则进行数据验证
			if ($this->patchValidate) { // 重置验证错误信息
				$this->error = array();
			}
			foreach ($_validate as $key => $val) {
				// 验证因子定义格式
				// array(field,rule,message,condition,type,when,params)
				// 判断是否需要执行验证
				if (empty($val[5]) || $val[5] == self::MODEL_BOTH || $val[5] == $type) {
					if (0 == strpos($val[2], '{%') && strpos($val[2], '}')) // 支持提示信息的多语言 使用 {%语言定义} 方式
					{
						$val[2] = L(substr($val[2], 2, -1));
					}
					$val[3] = isset($val[3]) ? $val[3] : self::EXISTS_VALIDATE;
					$val[4] = isset($val[4]) ? $val[4] : 'regex';
					// 判断验证条件
					switch ($val[3]) {
						case self::MUST_VALIDATE: // 必须验证 不管表单是否有设置该字段
							if (false === $this->_validationField($data, $val)) {
								return false;
							}
							break;
						case self::VALUE_VALIDATE: // 值不为空的时候才验证
							if ('' != trim($data[$val[0]])) {
								if (false === $this->_validationField($data, $val)
								) {
									return false;
								}
							}
							break;
						default: // 默认表单存在该字段就验证
							if (isset($data[$val[0]])) {
								if (false === $this->_validationField($data, $val)) {
									return false;
								}
							}
					}
				}
			}
			// 批量验证的时候最后返回错误
			if (!empty($this->error)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * 验证表单字段 支持批量验证
	 * 如果批量验证返回错误的数组信息
	 * @access protected
	 * @param array $data 创建数据
	 * @param array $val  验证因子
	 * @return boolean
	 */
	protected function _validationField($data, $val) {
		if (false === $this->_validationFieldItem($data, $val)) {
			if ($this->patchValidate) {
				$this->error[$val[0]] = $val[2];
			} else {
				$this->error = $val[2];
				return false;
			}
		}
		return;
	}

	/**
	 * 根据验证因子验证字段
	 * @access protected
	 * @param array $data 创建数据
	 * @param array $val  验证因子
	 * @return boolean
	 */
	protected function _validationFieldItem($data, $val) {
		switch (strtolower(trim($val[4]))) {
			case 'function': // 使用函数进行验证
			case 'callback': // 调用方法进行验证
				$args = isset($val[6]) ? (array)$val[6] : array();
				if (is_string($val[0]) && strpos($val[0], ',')) {
					$val[0] = explode(',', $val[0]);
				}
				if (is_array($val[0])) {
					// 支持多个字段验证
					foreach ($val[0] as $field) $_data[$field] = $data[$field];
					array_unshift($args, $_data);
				} else {
					array_unshift($args, $data[$val[0]]);
				}
				if ('function' == $val[4]) {
					return call_user_func_array($val[1], $args);
				} else {
					return call_user_func_array(array(
						&$this,
						$val[1]
					), $args);
				}
			case 'confirm': // 验证两个字段是否相同
				return $data[$val[0]] == $data[$val[1]];
			case 'unique': // 验证某个值是否唯一
				if (is_string($val[0]) && strpos($val[0], ',')) {
					$val[0] = explode(',', $val[0]);
				}
				$map = array();
				if (is_array($val[0])) {
					// 支持多个字段验证
					foreach ($val[0] as $field) $map[$field] = $data[$field];
				} else {
					$map[$val[0]] = $data[$val[0]];
				}
				if (!empty($data[$this->getPk()])) { // 完善编辑的时候验证唯一
					$map[$this->getPk()] = array(
						'neq',
						$data[$this->getPk()]
					);
				}
				if ($this->where($map)->find()) {
					return false;
				}
				return true;
			default: // 检查附加规则
				return $this->check($data[$val[0]], $val[1], $val[4]);
		}
	}

	/**
	 * 验证数据 支持 in between equal length regex expire ip_allow ip_deny
	 * @access public
	 * @param string $value 验证数据
	 * @param mixed  $rule  验证表达式
	 * @param string $type  验证方式 默认为正则验证
	 * @return boolean
	 */
	public function check($value, $rule, $type = 'regex') {
		$type = strtolower(trim($type));
		switch ($type) {
			case 'in': // 验证是否在某个指定范围之内 逗号分隔字符串或者数组
			case 'notin':
				$range = is_array($rule) ? $rule : explode(',', $rule);
				return $type == 'in' ? in_array($value, $range) : !in_array($value, $range);
			case 'between': // 验证是否在某个范围
			case 'notbetween': // 验证是否不在某个范围
				if (is_array($rule)) {
					$min = $rule[0];
					$max = $rule[1];
				} else {
					list($min, $max) = explode(',', $rule);
				}
				return $type == 'between' ? $value >= $min && $value <= $max : $value < $min || $value > $max;
			case 'equal': // 验证是否等于某个值
			case 'notequal': // 验证是否等于某个值
				return $type == 'equal' ? $value == $rule : $value != $rule;
			case 'length': // 验证长度
				$length = mb_strlen($value, 'utf-8'); // 当前数据长度
				if (strpos($rule, ',')) { // 长度区间
					list($min, $max) = explode(',', $rule);
					return $length >= $min && $length <= $max;
				} else { // 指定长度
					return $length == $rule;
				}
			case 'expire':
				list($start, $end) = explode(',', $rule);
				if (!is_numeric($start)) {
					$start = strtotime($start);
				}
				if (!is_numeric($end)) {
					$end = strtotime($end);
				}
				return NOW_TIME >= $start && NOW_TIME <= $end;
			case 'ip_allow': // IP 操作许可验证
				return in_array(get_client_ip(), explode(',', $rule));
			case 'ip_deny': // IP 操作禁止验证
				return !in_array(get_client_ip(), explode(',', $rule));
			case 'regex':
			default: // 默认使用正则验证 可以使用验证类中定义的验证名称
				// 检查附加规则
				return $this->regex($value, $rule);
		}
	}


	//sql查询
	public function query($sql, $params = array()) {
		return $this->db->query($sql, $params);
	}

	//sql查询，只返回一条数据
	public function find($sql, $params = array()) {
		return $this->db->find($sql, $params, false);
	}

	//执行sql
	public function exec($sql, $params = array()) {
		return $this->db->exec($sql, $params);
	}

	//获取pdo对象,灵活的对象~
	public function getPdo() {
		return $this->db->getPdo();
	}

	/**
	 * 启动事务
	 * @access public
	 * @return void
	 */
	public function beginTransaction() {
		return $this->db->beginTransaction();
	}

	/**
	 * 用于非自动提交状态下面的查询提交
	 * @access public
	 * @return boolen
	 */
	public function commit() {
		return $this->db->commit();
	}

	/**
	 * 事务回滚
	 * @access public
	 * @return boolen
	 */
	public function rollback() {
		return $this->db->rollback();
	}

	//获取最后插入的id
	public function lastInsertId() {
		return $this->db->lastInsertId();
	}

	//得到数据库完整的表名 todo:删除
	public function getTableName() {
		if (empty($this->tableName)) {
			$this->tableName = ($this->tablePrefix ? $this->tablePrefix : '').lcfirst($this->name);
		}
		return ($this->dbName ? $this->dbName.'.' : '').$this->tableName;
	}

	//得到完整的表名
	public function tname(){
		if (empty($this->tableName)) {
			$this->tableName = ($this->tablePrefix ? $this->tablePrefix : '').lcfirst($this->name);
		}
		return ($this->dbName ? $this->dbName.'.' : '').$this->tableName;
	}

	/**
	 * 获取所有表名
	 * @param string $dbName 数据库名//不输入默认
	 * @return mixed 返回所有表名
	 */
	public function getTables($dbName = '') {
		return $this->db->getTables($dbName);
	}

	//获取数据库字段
	public function getFields($tableName = '', $simple = false) {
		return $this->db->getFields($tableName ? $tableName : $this->tableName, $simple);
	}

	/**
	 * 返回模型的错误信息
	 * @access public
	 * @return string
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * 返回数据库的错误信息
	 * @access public
	 * @return string
	 */
	public function getDbError() {
		return $this->db->getError();
	}

	/**
	 * 返回最后插入的ID
	 * @access public
	 * @return string
	 */
	public function getLastInsID() {
		return $this->db->getLastInsID();
	}

	/**
	 * 返回最后执行的sql语句
	 * @access public
	 * @return string
	 */
	public function getLastSql() {
		return $this->db->getLastSql($this->name);
	}

	// 鉴于getLastSql比较常用 增加_sql 别名
	public function _sql() {
		return $this->getLastSql();
	}

	/**
	 * 获取主键名称
	 * @access public
	 * @return string
	 */
	public function getPk() {
		return isset($this->fields['_pk']) ? $this->fields['_pk'] : $this->pk;
	}

	/**
	 * 查询SQL组装 union
	 * @access public
	 * @param mixed   $union
	 * @param boolean $all
	 * @return Model
	 */
	public function union($union, $all = false) {
		if (empty($union)) {
			return $this;
		}
		if ($all) {
			$this->options['union']['_all'] = true;
		}
		if (is_object($union)) {
			$union = get_object_vars($union);
		}
		// 转换union表达式
		if (is_string($union)) {
			$options = $union;
		} elseif (is_array($union)) {
			if (isset($union[0])) {
				$this->options['union'] = array_merge($this->options['union'], $union);
				return $this;
			} else {
				$options = $union;
			}
		} else {
			throw_exception(L('_DATA_TYPE_INVALID_'));
		}
		$this->options['union'][] = $options;
		return $this;
	}

	/**
	 * 调用命名范围
	 * @access public
	 * @param mixed $scope 命名范围名称 支持多个 和直接定义
	 * @param array $args  参数
	 * @return Model
	 */
	public function scope($scope = '', $args = NULL) {
		if ('' === $scope) {
			if (isset($this->_scope['default'])) {
				// 默认的命名范围
				$options = $this->_scope['default'];
			} else {
				return $this;
			}
		} elseif (is_string($scope)) { // 支持多个命名范围调用 用逗号分割
			$scopes  = explode(',', $scope);
			$options = array();
			foreach ($scopes as $name) {
				if (!isset($this->_scope[$name])) {
					continue;
				}
				$options = array_merge($options, $this->_scope[$name]);
			}
			if (!empty($args) && is_array($args)) {
				$options = array_merge($options, $args);
			}
		} elseif (is_array($scope)) { // 直接传入命名范围定义
			$options = $scope;
		}

		if (is_array($options) && !empty($options)) {
			$this->options = array_merge($this->options, array_change_key_case($options));
		}
		return $this;
	}

	/**
	 * 指定查询数量
	 * @access public
	 * @param mixed $offset 起始位置
	 * @param mixed $length 查询数量
	 * @return Model
	 */
	public function limit($offset, $length = null) {
		$this->options['limit'] = is_null($length) ? $offset : $offset.','.$length;
		return $this;
	}

	/**
	 * 指定分页
	 * @access public
	 * @param mixed $page     页数
	 * @param mixed $listRows 每页数量
	 * @return Model
	 */
	public function page($page, $listRows = null) {
		$this->options['page'] = is_null($listRows) ? $page : $page.','.$listRows;
		return $this;
	}

	/**
	 * 设置数据对象的值
	 * @access public
	 * @param string $name  名称
	 * @param mixed  $value 值
	 * @return void
	 */
	public function __set($name, $value) {
		// 设置数据对象属性
		$this->data[$name] = $value;
	}

	/**
	 * 获取数据对象的值
	 * @access public
	 * @param string $name 名称
	 * @return mixed
	 */
	public function __get($name) {
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}

	/**
	 * 检测数据对象的值
	 * @access public
	 * @param string $name 名称
	 * @return boolean
	 */
	public function __isset($name) {
		return isset($this->data[$name]);
	}

	/**
	 * 销毁数据对象的值
	 * @access public
	 * @param string $name 名称
	 * @return void
	 */
	public function __unset($name) {
		unset($this->data[$name]);
	}

	/**
	 * 利用__call方法实现一些特殊的Model方法
	 * @access public
	 * @param string $method 方法名称
	 * @param array  $args   调用参数
	 * @return mixed
	 */
	public function __call($method, $args) {
		exit('方法'.$method.'-参数'.$args.' 不存在！');
	}

	/**
	 * 筛选$_POST中的数据，只获取需要的数据.获取需要插入的数据(数据库字段的数据)
	 * @param string $Controller_name 控制器名称
	 * @return array
	 */
	public function data() {
		$data = array(); //添加的数据
		//筛选插入数据库中的数据
		foreach ($_POST as $k => $v) {
			in_array($k, explode(',', Table2Arr::fields2arr($this->name))) && $v && $data[$k] = $v;
		}
		return $data;
	}


	/**
	 * @param array $data 插入数据库的数据，字段名=>字段值得键值对
	 * @return array $info
	 * $info['field'] 字段信息
	 * $info['data'] 字段值
	 * $info['index'] 字段值得替换值 防止sql注入，统一预处理方式！
	 * $info['sql'] 插入的sql语句，也可自己组装
	 */
	public function insertData($data) {
		$info          = array();
		$info['field'] = ''; //插入的字段
		$info['data']  = array(); //插入的数据
		$info['index'] = ''; //标示符
		foreach ($data as $k => $v) {
			$info['field'] .= $k.',';
			$info['data'][$k] = $v;
			$info['index'] .= ':'.$k.',';
		}
		$info['field'] = trim($info['field'], ',');
		$info['index'] = trim($info['index'], ',');
		$info['sql']   = 'insert into '.$this->tableName.' ('.$info['field'].') values ('.$info['index'].');';
		return $info;
	}


	/**
	 * 插入数据到数据库的简便方法
	 * @param        $data      插入的数据
	 * @param string $tablename 将数据插入到的
	 */
	public function insert($data, $tname = '') {
		$tname || $tname = $this->tableName;
		return $this->db->insert($data, $tname);
	}

	/**
	 * 更新数据到数据库的简便方法
	 * @param        $data
	 * @param        $where 更新条件
	 * @param string $tname
	 * @return mixed
	 */
	public function update($data, $where, $tname = '') {
		$tname || $tname = $this->tableName;
		return $this->db->update($data, $where, $tname);
	}

}