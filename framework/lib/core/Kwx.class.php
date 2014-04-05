<?php
//上帝未满18岁 | <qq1054000800@gmail.com> | <www.phpjcw.com>
safe();
class Kwx {
	//应用程序初始化
	static public function start() {
		$_class_name = __CLASS__; //当前类名
		//register_shutdown_function(array($_class_name, 'fatalError')); //1.页面被用户强制关闭 2.程序执行超时 3.代码执行完成时
		//set_error_handler(array($_class_name, 'appError')); //自定义错误处理函数
		//set_exception_handler(array($_class_name, 'appException')); //自定义异常处理函数
		spl_autoload_register(array(
			$_class_name,
			'autoLoad'
		)); //可以设置多个自定义函数
		//建立应用
		C(include CORE_PATH.'/conf/config.php'); //导入核心配置
		L(include CORE_PATH.'/lang/zn-cn.php'); //导入语言配置
		//加载核心文件
		require LIB_PATH.'/core/Log.class.php';
		require LIB_PATH.'/core/Router.class.php';
		require LIB_PATH.'/core/App.class.php';
		require LIB_PATH.'/core/Controller.class.php';
		require LIB_PATH.'/core/Model.class.php';
		require LIB_PATH.'/core/View.class.php';
		require LIB_PATH.'/core/Db.class.php';
		//todo:引入项目公共文件
		if (is_file(APP_COMMON_PATH.'/conf/config.php')) {
			C(include APP_COMMON_PATH.'/conf/config.php'); //导入项目的配置
		}
		if (is_file(APP_COMMON_PATH.'/common/common.php')) {
			require APP_COMMON_PATH.'/common/common.php'; //项目公共函数库
		}
		APP::run(); //运行app
		return;
	}

	/**
	 * 自动加载类
	 * @param $name 自动加载的类名
	 */
	static public function autoLoad($name) {
		$filenames = array(); //需要导入文件的数组
		$__name    = substr($name, -5);
		//fb($name);
		switch ($__name) {
			case 'oller':
				//Controller
				$filenames[] = APP_PATH.'/common/controller/'.$name.EXT; //在项目公共目录查找
				$filenames[] = MODULE_PATH.'/controller/'.$name.EXT; //在当前项目查找
				break;
			case 'Model':
				//Model
				$filenames[] = APP_PATH.'/common/model/'.$name.EXT; //在项目公共目录查找
				$filenames[] = MODULE_PATH.'/model/'.$name.EXT; //在当前项目查找
				break;
			case 'Cache':
				//Cache
				$filenames[] = LIB_PATH.'/driver/cache/'.$name.EXT; //缓存驱动
				break;
			case 'ngine':
				//Engine 数据库操作类
				$filenames[] = LIB_PATH.'/driver/db/'.$name.EXT; //数据库驱动
				break;
			default:
				//从默认类库调用
				$filenames[] = VENDOR_PATH.'/util/'.$name.EXT; //框架自带类库
				$filenames[] = APP_PATH.'/'.GROUP_NAME.'/vendor'.'/'.$name.EXT; //项目第三方类库
				$filenames[] = COMMON_VENDOR_PATH.'/'.$name.EXT; //项目公共目录
				break;
		}
		foreach ($filenames as $name) {
			if (isFile($name)) {
				include $name;
			}
		}
	}

	static public function fatalError() {

	}

	static public function appError() {

	}

	static public function appException() {

	}
}