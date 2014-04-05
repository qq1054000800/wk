<?php
//上帝未满18岁 | <qq1054000800@gmail.com> | <www.phpjcw.com>
safe();
class APP {
	static public function init() {
		//设置时区
		date_default_timezone_set('PRC');
		// 定义当前请求的系统常量
		define('NOW_TIME', $_SERVER['REQUEST_TIME']);
		define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
		define('IS_GET', REQUEST_METHOD == 'GET' ? true : false);
		define('IS_POST', REQUEST_METHOD == 'POST' ? true : false);
		define('IS_PUT', REQUEST_METHOD == 'PUT' ? true : false);
		define('IS_DELETE', REQUEST_METHOD == 'DELETE' ? true : false);
		define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])
		                    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		                   || !empty($_POST['ajax'])
		                   || !empty($_GET['ajax'])) ? true : false);

		//过滤所有的$_POST $_GET 数组
		if (C('VAR_FILTERS')) {
			//过滤函数
			foreach (explode(',', C('VAR_FILTERS')) as $value) {
				//过滤所有的$_POST
				array_walk_recursive($_GET, $value);
				array_walk_recursive($_POST, $value);
			}
		}
		Router::dispatch(); //处理url调度，与过滤函数的顺序？
	}

	//执行项目
	static public function exec() {
		//创建Action对象
		$Controller = A(CONTROLLER_NAME); //创建控制器对象
		if (!$Controller) {
			//不存在控制器,查找empty控制器
			$Controller = A('Empty');
			if (!$Controller) {
				//没有定义控制器
				echo 'no controller!!!';
				return;
			}
		}

		// 获取当前操作名 支持动态路由
		$action = ACTION_NAME;
		try {
			if (!preg_match('/^[A-Za-z](\w)*$/', $action)) {
				// 非法操作
				throw new ReflectionException();
			}
			$method = new ReflectionMethod($Controller, $action);
			if ($method->isPublic()) {
				$class = new ReflectionClass($Controller);
				// 前置操作
				if ($class->hasMethod('_before_'.$action)) {
					$before = $class->getMethod('_before_'.$action);
					if ($before->isPublic()) {
						$before->invoke($Controller);
					}
				}
				$method->invoke($Controller); //执行函数 注意：动作都是不带参数的，参数通过$_GET 或 $_POST 提交
				// 后置操作
				if ($class->hasMethod('_after_'.$action)) {
					$after = $class->getMethod('_after_'.$action);
					if ($after->isPublic()) {
						$after->invoke($Controller);
					}
				}
			} else {
				// 操作方法不是Public 抛出异常
				throw new ReflectionException();
			}
		} catch (ReflectionException $e) {
			// 方法调用发生异常后 引导到__call方法处理
			$method = new ReflectionMethod($Controller, '__call');
			$method->invokeArgs($Controller, array(
				$action,
				''
			));
		}
		return;
	}

	//运行应用
	static public function run() {
		APP::init(); //项目初始化
		APP::exec(); //项目执行
		return;
	}

}