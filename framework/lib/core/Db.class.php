<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>
safe();
class Db {
	//数据库引擎
	protected $dbEngine = 'Pdo';

	// 是否自动释放查询结果
	protected $autoFree = false;
	// 当前SQL指令
	protected $queryStr = '';
	// 最后插入ID
	protected $lastInsID = null;
	// 事务指令数
	protected $transTimes = 0;
	// 错误信息
	protected $error = '';
	// 查询表达式
	protected $selectSql = 'SELECT%DISTINCT% %FIELD% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT% %UNION%%COMMENT%';
	// 参数绑定
	protected $bind = array();

	/**
	 * 取得数据库类实例
	 * @static
	 * @access public
	 * @return mixed 返回数据库驱动类
	 */
	public static function getInstance() {
		$args = func_get_args(); //获取传入的参数
		return get_instance_of(__CLASS__, 'factory', $args);
	}

	/**
	 * 加载数据库 支持配置文件或者 DSN
	 * @access public
	 * @param mixed $db_config 数据库配置信息
	 * @return string
	 */
	public function factory($db_config = '') {
		$db_config = $this->parseConfig($db_config); // 读取数据库配置
		if (empty($db_config['engine'])) {
			halt('没有设置数据库引擎类型！'); //数据库类型没有设置
		}
		$this->dbEngine = ucfirst(strtolower($db_config['engine']));//数据库引擎，对应驱动的名称
		$this->dbType   = ucfirst(strtolower($db_config['type'])); // 数据库类型 类型名大写
		$class          = ucfirst(strtolower($this->dbEngine)).'Engine'; //类名 数据库引擎
		if (class_exists($class)) {
			try{
				return new $class($db_config);
			}catch (Exception $e){
				halt($e->getMessage());
			}
		} else {
			halt('没有定义驱动类！');
		}
	}

	/**
	 * 分析数据库配置信息，支持数组和DSN
	 * @access private
	 * @param mixed $db_config 数据库配置信息
	 * @return string
	 */
	private function parseConfig($db_config = '') {
		if (!empty($db_config) && is_string($db_config)) {
			$db_config['engine'] = 'Pdo';
			$db_config           = $this->parseDSN($db_config); // 如果DSN字符串则进行解析,适用于临时连接数据库
		} elseif (is_array($db_config)) {
			$db_config = array(
				'type'   => $db_config['DB_TYPE'],
				'engine' => $db_config['DB_ENGINE'],
				'user'   => $db_config['DB_USER'],
				'pwd'    => $db_config['DB_PWD'],
				'host'   => $db_config['DB_HOST'],
				'port'   => $db_config['DB_PORT'],
				'name'   => $db_config['DB_NAME'],
				'dsn'    => $db_config['DB_DSN'],
				'params' => $db_config['DB_PARAMS'],
			);
		} else {
			//默认连接 ^.^
			$db_config_0 = array(
				//数据库引擎
				'engine' => C('DB_ENGINE'),
				'dsn'    => C('DB_DSN'),
			);
			$db_config_1 = $this->parseDSN($db_config_0['dsn']);
			$db_config   = array_merge($db_config_0, $db_config_1);
		}
		return $db_config;
	}

	/**
	 * DSN解析
	 * 格式： mysql://username:passwd@localhost:3306/DbName
	 * @static
	 * @access public
	 * @param string $dsnStr
	 * @return array
	 */
	public function parseDSN($dsnStr) {
		if (empty($dsnStr)) {
			return false;
		}
		$info = parse_url($dsnStr);
		if ($info['scheme']) {
			$dsn = array(
				'type' => $info['scheme'],
				'user' => isset($info['user']) ? $info['user'] : '',
				'pwd'  => isset($info['pass']) ? $info['pass'] : '',
				'host' => isset($info['host']) ? $info['host'] : '',
				'port' => isset($info['port']) ? $info['port'] : '',
				'name' => isset($info['path']) ? substr($info['path'], 1) : ''
			);
		} else {
			preg_match('/^(.*?)\:\/\/(.*?)\:(.*?)\@(.*?)\:([0-9]{1, 6})\/(.*?)$/', trim($dsnStr), $matches);
			$dsn = array(
				'type' => $matches[1],
				'user' => $matches[2],
				'pwd'  => $matches[3],
				'host' => $matches[4],
				'port' => $matches[5],
				'name' => $matches[6]
			);
		}
		$dsn['dsn'] = '';
		return $dsn;
	}



	/**
	 * set分析
	 * @access protected
	 * @param array $data
	 * @return string
	 */
	protected function parseSet($data) {
		foreach ($data as $key => $val) {
			if (is_array($val) && 'exp' == $val[0]) {
				$set[] = $this->parseKey($key).'='.$val[1];
			} elseif (is_scalar($val) || is_null(($val))) { // 过滤非标量数据
				if (C('DB_BIND_PARAM') && 0 !== strpos($val, ':')) {
					$name  = md5($key);
					$set[] = $this->parseKey($key).'=:'.$name;
					$this->bindParam($name, $val);
				} else {
					$set[] = $this->parseKey($key).'='.$this->parseValue($val);
				}
			}
		}
		return ' SET '.implode(',', $set);
	}

	/**
	 * 参数绑定
	 * @access protected
	 * @param string $name  绑定参数名
	 * @param mixed  $value 绑定值
	 * @return void
	 */
	protected function bindParam($name, $value) {
		$this->bind[':'.$name] = $value;
	}

	/**
	 * 参数绑定分析
	 * @access protected
	 * @param array $bind
	 * @return array
	 */
	protected function parseBind($bind) {
		$bind       = array_merge($this->bind, $bind);
		$this->bind = array();
		return $bind;
	}

	/**
	 * 字段名分析
	 * @access protected
	 * @param string $key
	 * @return string
	 */
	protected function parseKey(&$key) {
		return $key;
	}

	/**
	 * value分析
	 * @access protected
	 * @param mixed $value
	 * @return string
	 */
	protected function parseValue($value) {
		if (is_string($value)) {
			$value = '\''.$this->escapeString($value).'\'';
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
	 * field分析
	 * @access protected
	 * @param mixed $fields
	 * @return string
	 */
	protected function parseField($fields) {
		if (is_string($fields) && strpos($fields, ',')) {
			$fields = explode(',', $fields);
		}
		if (is_array($fields)) {
			// 完善数组方式传字段名的支持
			// 支持 'field1'=>'field2' 这样的字段别名定义
			$array = array();
			foreach ($fields as $key => $field) {
				if (!is_numeric($key)) {
					$array[] = $this->parseKey($key).' AS '.$this->parseKey($field);
				} else {
					$array[] = $this->parseKey($field);
				}
			}
			$fieldsStr = implode(',', $array);
		} elseif (is_string($fields) && !empty($fields)) {
			$fieldsStr = $this->parseKey($fields);
		} else {
			$fieldsStr = '*';
		}
		//TODO 如果是查询全部字段，并且是join的方式，那么就把要查的表加个别名，以免字段被覆盖
		return $fieldsStr;
	}

	/**
	 * table分析
	 * @access protected
	 * @param mixed $table
	 * @return string
	 */
	protected function parseTable($tables) {
		if (is_array($tables)) { // 支持别名定义
			$array = array();
			foreach ($tables as $table => $alias) {
				if (!is_numeric($table)) {
					$array[] = $this->parseKey($table).' '.$this->parseKey($alias);
				} else {
					$array[] = $this->parseKey($table);
				}
			}
			$tables = $array;
		} elseif (is_string($tables)) {
			$tables = explode(',', $tables);
			array_walk($tables, array(
				&$this,
				'parseKey'
			));
		}
		return implode(',', $tables);
	}

	/**
	 * where分析
	 * @access protected
	 * @param mixed $where
	 * @return string
	 */
	protected function parseWhere($where) {
		$whereStr = '';
		if (is_string($where)) {
			// 直接使用字符串条件
			$whereStr = $where;
		} else { // 使用数组表达式
			$operate = isset($where['_logic']) ? strtoupper($where['_logic']) : '';
			if (in_array($operate, array(
				'AND',
				'OR',
				'XOR'
			))
			) {
				// 定义逻辑运算规则 例如 OR XOR AND NOT
				$operate = ' '.$operate.' ';
				unset($where['_logic']);
			} else {
				// 默认进行 AND 运算
				$operate = ' AND ';
			}
			foreach ($where as $key => $val) {
				$whereStr .= '( ';
				if (is_numeric($key)) {
					$key = '_complex';
				}
				if (0 === strpos($key, '_')) {
					// 解析特殊条件表达式
					$whereStr .= $this->parseThinkWhere($key, $val);
				} else {
					// 查询字段的安全过滤
					if (!preg_match('/^[A-Z_\|\&\-.a-z0-9\(\)\,]+$/', trim($key))) {
						throw_exception(L('_EXPRESS_ERROR_').':'.$key);
					}
					// 多条件支持
					$multi = is_array($val) && isset($val['_multi']);
					$key   = trim($key);
					if (strpos($key, '|')) { // 支持 name|title|nickname 方式定义查询字段
						$array = explode('|', $key);
						$str   = array();
						foreach ($array as $m => $k) {
							$v     = $multi ? $val[$m] : $val;
							$str[] = '('.$this->parseWhereItem($this->parseKey($k), $v).')';
						}
						$whereStr .= implode(' OR ', $str);
					} elseif (strpos($key, '&')) {
						$array = explode('&', $key);
						$str   = array();
						foreach ($array as $m => $k) {
							$v     = $multi ? $val[$m] : $val;
							$str[] = '('.$this->parseWhereItem($this->parseKey($k), $v).')';
						}
						$whereStr .= implode(' AND ', $str);
					} else {
						$whereStr .= $this->parseWhereItem($this->parseKey($key), $val);
					}
				}
				$whereStr .= ' )'.$operate;
			}
			$whereStr = substr($whereStr, 0, -strlen($operate));
		}
		return empty($whereStr) ? '' : ' WHERE '.$whereStr;
	}

	// where子单元分析
	protected function parseWhereItem($key, $val) {
		$whereStr = '';
		if (is_array($val)) {
			if (is_string($val[0])) {
				if (preg_match('/^(EQ|NEQ|GT|EGT|LT|ELT)$/i', $val[0])) { // 比较运算
					$whereStr .= $key.' '.$this->comparison[strtolower($val[0])].' '.$this->parseValue($val[1]);
				} elseif (preg_match('/^(NOTLIKE|LIKE)$/i', $val[0])) { // 模糊查找
					if (is_array($val[1])) {
						$likeLogic = isset($val[2]) ? strtoupper($val[2]) : 'OR';
						if (in_array($likeLogic, array(
							'AND',
							'OR',
							'XOR'
						))
						) {
							$likeStr = $this->comparison[strtolower($val[0])];
							$like    = array();
							foreach ($val[1] as $item) {
								$like[] = $key.' '.$likeStr.' '.$this->parseValue($item);
							}
							$whereStr .= '('.implode(' '.$likeLogic.' ', $like).')';
						}
					} else {
						$whereStr .= $key.' '.$this->comparison[strtolower($val[0])].' '.$this->parseValue($val[1]);
					}
				} elseif ('exp' == strtolower($val[0])) { // 使用表达式
					$whereStr .= ' ('.$key.' '.$val[1].') ';
				} elseif (preg_match('/IN/i', $val[0])) { // IN 运算
					if (isset($val[2]) && 'exp' == $val[2]) {
						$whereStr .= $key.' '.strtoupper($val[0]).' '.$val[1];
					} else {
						if (is_string($val[1])) {
							$val[1] = explode(',', $val[1]);
						}
						$zone = implode(',', $this->parseValue($val[1]));
						$whereStr .= $key.' '.strtoupper($val[0]).' ('.$zone.')';
					}
				} elseif (preg_match('/BETWEEN/i', $val[0])) { // BETWEEN运算
					$data = is_string($val[1]) ? explode(',', $val[1]) : $val[1];
					$whereStr .= ' ('.$key.' '.strtoupper($val[0]).' '.$this->parseValue($data[0]).' AND '
					             .$this->parseValue($data[1]).' )';
				} else {
					throw_exception(L('_EXPRESS_ERROR_').':'.$val[0]);
				}
			} else {
				$count = count($val);
				$rule  = isset($val[$count - 1]) ? strtoupper($val[$count - 1]) : '';
				if (in_array($rule, array(
					'AND',
					'OR',
					'XOR'
				))
				) {
					$count = $count - 1;
				} else {
					$rule = 'AND';
				}
				for ($i = 0; $i < $count; $i++) {
					$data = is_array($val[$i]) ? $val[$i][1] : $val[$i];
					if ('exp' == strtolower($val[$i][0])) {
						$whereStr .= '('.$key.' '.$data.') '.$rule.' ';
					} else {
						$op = is_array($val[$i]) ? $this->comparison[strtolower($val[$i][0])] : '=';
						$whereStr .= '('.$key.' '.$op.' '.$this->parseValue($data).') '.$rule.' ';
					}
				}
				$whereStr = substr($whereStr, 0, -4);
			}
		} else {
			//对字符串类型字段采用模糊匹配
			if (C('DB_LIKE_FIELDS') && preg_match('/('.C('DB_LIKE_FIELDS').')/i', $key)) {
				$val = '%'.$val.'%';
				$whereStr .= $key.' LIKE '.$this->parseValue($val);
			} else {
				$whereStr .= $key.' = '.$this->parseValue($val);
			}
		}
		return $whereStr;
	}

	/**
	 * 特殊条件分析
	 * @access protected
	 * @param string $key
	 * @param mixed  $val
	 * @return string
	 */
	protected function parseThinkWhere($key, $val) {
		$whereStr = '';
		switch ($key) {
			case '_string':
				// 字符串模式查询条件
				$whereStr = $val;
				break;
			case '_complex':
				// 复合查询条件
				$whereStr = is_string($val) ? $val : substr($this->parseWhere($val), 6);
				break;
			case '_query':
				// 字符串模式查询条件
				parse_str($val, $where);
				if (isset($where['_logic'])) {
					$op = ' '.strtoupper($where['_logic']).' ';
					unset($where['_logic']);
				} else {
					$op = ' AND ';
				}
				$array = array();
				foreach ($where as $field => $data) $array[] = $this->parseKey($field).' = '.$this->parseValue($data);
				$whereStr = implode($op, $array);
				break;
		}
		return $whereStr;
	}

	/**
	 * limit分析
	 * @access protected
	 * @param mixed $lmit
	 * @return string
	 */
	protected function parseLimit($limit) {
		return !empty($limit) ? ' LIMIT '.$limit.' ' : '';
	}

	/**
	 * join分析
	 * @access protected
	 * @param mixed $join
	 * @return string
	 */
	protected function parseJoin($join) {
		$joinStr = '';
		if (!empty($join)) {
			if (is_array($join)) {
				foreach ($join as $key => $_join) {
					if (false !== stripos($_join, 'JOIN')) {
						$joinStr .= ' '.$_join;
					} else {
						$joinStr .= ' LEFT JOIN '.$_join;
					}
				}
			} else {
				$joinStr .= ' LEFT JOIN '.$join;
			}
		}
		//将__TABLE_NAME__这样的字符串替换成正规的表名,并且带上前缀和后缀
		$joinStr = preg_replace("/__([A-Z_-]+)__/esU", C("DB_PREFIX").".strtolower('$1')", $joinStr);
		return $joinStr;
	}

	/**
	 * order分析
	 * @access protected
	 * @param mixed $order
	 * @return string
	 */
	protected function parseOrder($order) {
		if (is_array($order)) {
			$array = array();
			foreach ($order as $key => $val) {
				if (is_numeric($key)) {
					$array[] = $this->parseKey($val);
				} else {
					$array[] = $this->parseKey($key).' '.$val;
				}
			}
			$order = implode(',', $array);
		}
		return !empty($order) ? ' ORDER BY '.$order : '';
	}

	/**
	 * group分析
	 * @access protected
	 * @param mixed $group
	 * @return string
	 */
	protected function parseGroup($group) {
		return !empty($group) ? ' GROUP BY '.$group : '';
	}

	/**
	 * having分析
	 * @access protected
	 * @param string $having
	 * @return string
	 */
	protected function parseHaving($having) {
		return !empty($having) ? ' HAVING '.$having : '';
	}

	/**
	 * comment分析
	 * @access protected
	 * @param string $comment
	 * @return string
	 */
	protected function parseComment($comment) {
		return !empty($comment) ? ' /* '.$comment.' */' : '';
	}

	/**
	 * distinct分析
	 * @access protected
	 * @param mixed $distinct
	 * @return string
	 */
	protected function parseDistinct($distinct) {
		return !empty($distinct) ? ' DISTINCT ' : '';
	}

	/**
	 * union分析
	 * @access protected
	 * @param mixed $union
	 * @return string
	 */
	protected function parseUnion($union) {
		if (empty($union)) {
			return '';
		}
		if (isset($union['_all'])) {
			$str = 'UNION ALL ';
			unset($union['_all']);
		} else {
			$str = 'UNION ';
		}
		foreach ($union as $u) {
			$sql[] = $str.(is_array($u) ? $this->buildSelectSql($u) : $u);
		}
		return implode(' ', $sql);
	}

	/**
	 * 获取最近插入的ID
	 * @access public
	 * @return string
	 */
	public function getLastInsID() {
		return $this->lastInsID;
	}

	/**
	 * 获取最近的错误信息
	 * @access public
	 * @return string
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * SQL指令安全过滤
	 * @access public
	 * @param string $str  SQL字符串
	 * @return string
	 */
	public function escapeString($str) {
		return addslashes($str);
	}

	/**
	 * 设置当前操作模型
	 * @access public
	 * @param string $model  模型名
	 * @return void
	 */
	public function setModel($model) {
		$this->model = $model;
	}

	// 关闭数据库 由驱动类定义
	public function close() { }
}