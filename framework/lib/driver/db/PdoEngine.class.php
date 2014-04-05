<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>
safe();
class PdoEngine extends Db {
	protected $config = ''; //数据库连接参数配置
	protected $pdo = null; // 实例化的DB对象
	protected $connected = false; // 是否已经连接数据库
	protected $numRows = 0; // 返回或者影响记录数
	protected $numCols = 0; // 返回字段数
	protected $dbName = ''; // 数据库名
	protected $dbType = null; // 数据库类型


	/**
	 * 架构函数 实例化Pdo对象~
	 * @access public
	 * @param array $config 数据库配置数组
	 */
	public function __construct($config = '') {
		if (!class_exists('PDO')) {
			halt('不支持PDO扩展！');
		}
		$config && $this->config = $config;
		$this->dbName = $config['name']; //数据库名称
		$option       = array(); //数据库连接参数
		try {
			if ($this->pdo) {
				return;
			}
			$config || $config = $this->config; //连接数据库的配置
			$type             = strtolower($config['type']); //数据库类型
			$this->dbType     = $type; //小写
			$config['params'] = ''; //连接参数
			switch ($type) {
				case 'mysql':
				case 'pgsql':
					$this->pdo = new PDO($type.':host='.$config['host'].';port='.$config['port'].';dbname='
					                     .$config['name'], $config['user'], $config['pwd'], $option);
					break;
			}
			$this->pdo->exec('SET NAMES \''.C('DB_CHARSET').'\'');
		} catch (PDOException $e) {
			throw new Exception($e->getMessage()); //创建pdo对象，抛出异常！
		}
	}

	//返回pdo操作对象
	public function getPdo() {
		return $this->pdo;
	}

	//返回最后插入行的id
	public function lastInsertId() {
		$id = $this->pdo->lastInsertId();
		return $id ? $id : false;
	}

	/**
	 * 启动事务
	 * @access public
	 * @return void
	 */
	public function beginTransaction() {
		if ($this->pdo) {
			$this->pdo->beginTransaction();
		}
	}

	/**
	 * 用于非自动提交状态下面的查询提交
	 * @access public
	 * @return boolen
	 */
	public function commit() {
		if ($this->pdo) {
			if (!$this->pdo->commit()) {
				$this->error('提交错误！');
			}
		}
	}

	/**
	 * 事务回滚
	 * @access public
	 * @return boolen
	 */
	public function rollback() {
		if ($this->pdo) {
			if (!$this->pdo->rollBack()) {
				$this->error('提交错误！');
			}
		}
	}


	/**
	 * 执行查询 返回一条数据
	 * @access public
	 * @param string $str  sql指令
	 * @return mixed
	 */
	public function find($sql, $params = array()) {
		$this->queryStr = $sql;
		$PS             = $this->pdo->prepare($this->queryStr);
		if ($PS === false) {
			return $this->error($PS);
		} else {
			$res = $params == array() ? $PS->execute() : $PS->execute($params);
			if (false === $res) {
				return $this->error($PS);
			} else {
				$res           = $PS->fetchAll(PDO::FETCH_ASSOC);
				$this->numRows = count($res);
				return $res[0];
			}
		}
	}

	/**
	 * 执行查询 返回数据集
	 * @access public
	 * @param string $str  sql指令
	 * @return mixed
	 */
	public function query($sql, $params = array()) {
		$this->queryStr = $sql;
		$PS             = $this->pdo->prepare($this->queryStr);
		if ($PS === false) {
			return $this->error($PS);
		} else {
			$res = $params == array() ? $PS->execute() : $PS->execute($params);
			if (false === $res) {
				return $this->error($PS);
			} else {
				$res           = $PS->fetchAll(PDO::FETCH_ASSOC);
				$this->numRows = count($res);
				return $res;
			}
		}
	}

	//执行语句,返回影响的函数
	public function exec($sql, $params = array()) {
		$this->queryStr = $sql;
		$PS             = $this->pdo->prepare($this->queryStr);
		if ($PS === false) {
			return $this->error($PS);
		}
		if (false === ($params == array() ? $PS->execute() : $PS->execute($params))) {
			return $this->error($PS);
		} else {
			$this->numRows = $PS->rowCount();
			return $this->numRows;
		}
	}

	//数据库错误信息
	public function error($PS) {
		if (is_string($PS)) {
			return $PS;
		} else {
			fb($PS->errorInfo());
			return '执行sql出错！- NO.'.$PS->errorCode().'- PDO 错误！';
		}
	}

	/**
	 * SQL指令安全过滤
	 * @access public
	 * @param string $str  SQL指令
	 * @return string
	 */
	public function escapeString($str) {
		switch ($this->dbType) {
			case 'PGSQL':
			case 'MSSQL':
			case 'SQLSRV':
			case 'MYSQL':
				return addslashes($str);
			case 'IBASE':
			case 'SQLITE':
			case 'ORACLE':
			case 'OCI':
				return str_ireplace("'", "''", $str);
		}
	}

	/**
	 * value分析
	 * @access protected
	 * @param mixed $value
	 * @return string
	 */
	protected function parseValue($value) {
		if (is_string($value)) {
			$value = strpos($value, ':') === 0 ? $this->escapeString($value) : '\''.$this->escapeString($value).'\'';
		} elseif (isset($value[0]) && is_string($value[0]) && strtolower($value[0]) == 'exp') {
			$value = $this->escapeString($value[1]);
		} elseif (is_array($value)) {
			$value = array_map(array(
				$this,
				'parseValue'
			), $value);
		} elseif (is_bool($value)) {
			$value = $value ? '1' : '0';
		} elseif (is_null($value)) {
			$value = 'null';
		}
		return $value;
	}

	/**
	 * 获取所有表名
	 * @param string $dbName 数据库名
	 * @return array 返回所有表名
	 */
	public function getTables($dbName = '') {
		switch ($this->dbType) {
			case 'mysql':
				$sql = 'SHOW TABLES '.($dbName ? 'FROM '.$dbName : '');
				break;
		}
		$res  = $this->query($sql);
		$info = array();
		if (is_array($res)) {
			foreach ($res as $v) {
				$v      = array_change_key_case($v); //键名小写
				$info[] = $v['tables_in_'.strtolower($dbName ? $dbName : $this->dbName)]; //注意这里的键名Tables_in_dbName
			}
		}
		return $info;
	}


	/**
	 * 插入数据到数据库的简便方法
	 * @param        $data      插入的数据
	 * @param string $tname     将数据插入到的
	 */
	public function insert($data, $tname) {
		$info_field = ''; //插入的字段
		$info_index = ''; //标示符
		foreach ($data as $k => $v) {
			$info_field .= $k.',';
			$info_index .= ':'.$k.',';
		}
		$info_field = trim($info_field, ',');
		$info_index = trim($info_index, ',');
		$sql        = 'INSERT INTO '.$tname.' ('.$info_field.') VALUES ('.$info_index.') ;';
		$ret = $this->exec($sql, $data);
		if($ret > 0){
			return $this->pdo->lastInsertId();
		}
		return 0;
	}

	/**
	 * 更新数据到数据库的简便方法
	 * @param $data     更新的数据
	 * @param $where    更新的条件
	 * @param $tname    表名
	 * @return string
	 */
	public function update($data, $where, $tname) {
		$index = ''; //sql预处理语句
		foreach ($data as $k => $v) {
			$index .= $k.' = :'.$k.' ,';
		}
		$index = trim($index, ',');
		$sql   = 'UPDATE '.$tname.' SET '.$index.' WHERE '.$where.' ;';
		return $this->exec($sql, $data);
	}

	/**
	 * 获取数据库字段
	 * @param      $tableName 真实的表名
	 * @param bool $simple
	 *                        true//返回字段详细信息
	 *                        false//仅仅返回字段名的数组
	 * @return array
	 */
	public function getFields($tableName, $simple = false) {
		switch ($this->dbType) {
			case 'mysql':
				$sql = 'DESC '.$tableName;
				break;
			//todo:添加兼容不同数据库
		}
		$result = $this->query($sql);
		$info   = array();
		if (is_array($result)) {
			foreach ($result as $val) {
				$val = array_change_key_case($val);
				if ($simple) {
					$info[] = $val['field'];
				} else {
					$val['name'] = isset($val['name']) ? $val['name'] : "";
					$val['type'] = isset($val['type']) ? $val['type'] : "";
					$name        = isset($val['field']) ? $val['field'] : $val['name'];
					$info[$name] = array(
						'name'    => $name,
						'type'    => $val['type'],
						'notnull' => (bool)(((isset($val['null'])) && ($val['null'] === ''))
						                    || ((isset($val['notnull'])) && ($val['notnull'] === ''))),
						// not null is empty, null is yes
						'default' => isset($val['default']) ? $val['default']
							: (isset($val['dflt_value']) ? $val['dflt_value'] : ""),
						'primary' => isset($val['key'])
							? strtolower($val['key']) == 'pri' : (isset($val['pk']) ? $val['pk'] : false),
						'autoinc' => isset($val['extra']) ? strtolower($val['extra']) == 'auto_increment'
							: (isset($val['key']) ? $val['key'] : false),
					);
				}
			}
		}
		return $info;
	}

	/**
	 * 析构方法
	 * @access public
	 */
	public function __destruct() {
		// 关闭连接
		$this->pdo = null;
	}
}