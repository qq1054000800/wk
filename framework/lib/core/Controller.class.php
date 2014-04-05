<?php
//上帝未满18岁 | <qq1054000800@gmail.com> | <www.phpjcw.com>
safe();
abstract class Controller {

	//视图层的对象
	protected $view = null;

	/**
	 * 当前控制器名称
	 * @var name
	 * @access protected
	 */
	protected $name = '';

	/**
	 * 控制器参数
	 * @var config
	 * @access protected
	 */
	protected $config = array();

	//初始化
	public function __construct() {
		$this->view = new View(); //创建视图类 todo:单例模式
		$this->_init(); //创建控制器的初始化函数
	}

	// 回调方法 初始化控制器
	protected function _init() { }

	/**
	 * 模板显示 调用内置的模板引擎显示方法，
	 * @access protected
	 * @param string $templateFile 指定要调用的模板文件
	 * 默认为空 由系统自动定位模板文件
	 * @param string $content      输出内容
	 * @return void
	 */
	protected function display($tplName = null) {
		$this->view->display($tplName);
	}

	/**
	 *  创建静态页面
	 * @access protected
	 * @htmlfile 生成的静态文件名称
	 * @htmlpath 生成的静态文件路径
	 * @param string $templateFile 指定要调用的模板文件
	 * 默认为空 由系统自动定位模板文件
	 * @return string
	 */
	protected function buildHtml($htmlfile = '', $htmlpath = '', $templateFile = '') {
		$content  = $this->fetch($templateFile);
		$htmlpath = !empty($htmlpath) ? $htmlpath : HTML_PATH;
		$htmlfile = $htmlpath.$htmlfile.C('HTML_FILE_SUFFIX');
		if (!is_dir(dirname($htmlfile))) // 如果静态目录不存在 则创建
		{
			mkdir(dirname($htmlfile), 0755, true);
		}
		if (false === file_put_contents($htmlfile, $content)) {
			throw_exception(L('_CACHE_WRITE_ERROR_').':'.$htmlfile);
		}
		return $content;
	}

	/**
	 * 模板主题设置
	 * @access protected
	 * @param string $theme 模版主题
	 * @return Action
	 */
	protected function theme($theme) {
		$this->view->theme($theme);
		return $this;
	}

	/**
	 * 模板变量赋值
	 * @access protected
	 * @param mixed $name  要显示的模板变量
	 * @param mixed $value 变量的值
	 * @return Action
	 */
	protected function assign($name, $value = '') {
		$this->view->assign($name, $value);
		return $this;
	}

	public function __set($name, $value) {
		//
	}

	public function __get($name) {
		//
	}

	public function __isset($name) {
		//
	}

	public function __call($method, $args) {
		exit ($method);
		if (0 === strcasecmp($method, ACTION_NAME.C('ACTION_SUFFIX'))) {
			if (method_exists($this, '_empty')) {
				// 如果定义了_empty操作 则调用
				$this->_empty($method, $args);
			} elseif (file_exists_case($this->view->parseTemplate())) {
				// 检查是否存在默认模版 如果有直接输出模版
				$this->display();
			}
		} else {
			switch (strtolower($method)) {
				// 判断提交方式
				case 'ispost'   :
				case 'isget'    :
				case 'ishead'   :
				case 'isdelete' :
				case 'isput'    :
					return strtolower($_SERVER['REQUEST_METHOD']) == strtolower(substr($method, 2));
				// 获取变量 支持过滤和默认值 调用方式 $this->_post($key,$filter,$default);
				case '_get'     :
					$input =& $_GET;
					break;
				case '_post'    :
					$input =& $_POST;
					break;
				case '_put'     :
					parse_str(file_get_contents('php://input'), $input);
					break;
				case '_param'   :
					switch ($_SERVER['REQUEST_METHOD']) {
						case 'POST':
							$input = $_POST;
							break;
						case 'PUT':
							parse_str(file_get_contents('php://input'), $input);
							break;
						default:
							$input = $_GET;
					}
					if (C('VAR_URL_PARAMS') && isset($_GET[C('VAR_URL_PARAMS')])) {
						$input = array_merge($input, $_GET[C('VAR_URL_PARAMS')]);
					}
					break;
				case '_request' :
					$input =& $_REQUEST;
					break;
				case '_session' :
					$input =& $_SESSION;
					break;
				case '_cookie'  :
					$input =& $_COOKIE;
					break;
				case '_server'  :
					$input =& $_SERVER;
					break;
				case '_globals' :
					$input =& $GLOBALS;
					break;
				default:
					halt(__CLASS__.':'.$method.L('_METHOD_NOT_EXIST_'));
			}
			if (!isset($args[0])) { // 获取全局变量
				$data = $input; // 由VAR_FILTERS配置进行过滤
			} elseif (isset($input[$args[0]])) { // 取值操作
				$data    = $input[$args[0]];
				$filters = isset($args[1]) ? $args[1] : C('DEFAULT_FILTER');
				if ($filters) { // 2012/3/23 增加多方法过滤支持
					$filters = explode(',', $filters);
					foreach ($filters as $filter) {
						if (function_exists($filter)) {
							$data = is_array($data) ? array_map($filter, $data) : $filter($data); // 参数过滤
						}
					}
				}
			} else { // 变量默认值
				$data = isset($args[2]) ? $args[2] : NULL;
			}
			Log::record('建议使用I方法替代'.$method, Log::NOTICE);
			return $data;
		}
	}

	//操作错误跳转的快捷方法
	protected function error($message = '', $jumpUrl = '') {
		$this->dispatchJump($message, 0, $jumpUrl);
	}

	//操作成功跳转的快捷方法
	protected function success($message = '', $jumpUrl = '') {
		$this->dispatchJump($message, 1, $jumpUrl);
	}

	/**
	 * Ajax方式返回数据到客户端
	 * @access protected
	 * @param mixed  $data 要返回的数据
	 * @param String $type AJAX返回数据格式
	 * @return void
	 */
	protected function ajaxReturn($data, $type = '') {
		$type || $type = 'JSON'; //默认json返回
		switch (strtoupper($type)) {
			case 'JSON' :
				// 返回JSON数据格式到客户端 包含状态信息
				header('Content-Type:application/json; charset=utf-8');
				exit(json_encode($data));
			case 'XML'  :
				// 返回xml格式数据
				header('Content-Type:text/xml; charset=utf-8');
				exit(xml_encode($data));
			case 'JSONP':
				// 返回JSON数据格式到客户端 包含状态信息
				header('Content-Type:application/json; charset=utf-8');
				$handler =
					isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
				exit($handler.'('.json_encode($data).');');
			case 'EVAL' :
				// 返回可执行的js脚本
				header('Content-Type:text/html; charset=utf-8');
				exit($data);
		}
	}

	/**
	 * 默认跳转操作 支持错误导向和正确跳转
	 * 调用模板显示 默认为public目录下面的success页面
	 * 提示页面为可配置 支持模板标签
	 * @param string  $message 提示信息
	 * @param Boolean $status  状态
	 * @param string  $jumpUrl 页面跳转地址
	 * @access private
	 * @return void
	 */
	private function dispatchJump($message, $status = 1, $jumpUrl = '') {
		if (IS_AJAX) {
			$data           = array();
			$data['info']   = $message;
			$data['status'] = $status;
			$data['url']    = $jumpUrl;
			$this->ajaxReturn($data);
		}
		if ($status) {
			redirect($jumpUrl ? $jumpUrl : U('Public/succes'), 0, $message);
		} else {
			redirect($jumpUrl ? $jumpUrl : U('Public/error'), 0, $message);
		}
	}

	//析构函数
	public function __destruct() {
		// 执行后续操作
	}
}