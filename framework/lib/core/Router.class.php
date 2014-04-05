<?php
//上帝未满18岁 | <qq1054000800@gmail.com> | <www.phpjcw.com>
safe();
class Router {

    /**
     * 路由url
     */
    static public function dispatch() {
        $type        = strtolower($_SERVER['REQUEST_METHOD']);
        $request_uri = strtolower($_SERVER['REQUEST_URI']); //不区分大小写
        //截取?a=a1&b=b1这样，后面的不需要的url
        if (false !== ($getPosition = strpos($request_uri, '?'))) {
            $request_uri = substr($request_uri, 0, $getPosition);
        }

        $depr   = C('URL_DEPR'); //分割符
        $routes = C('ROUTES'); //路由信息
        if (strpos($request_uri, '/index.php') === 0) {
            $request_uri = substr($request_uri, 11); //去除：/index.php
        }

        /*获取url信息=>模块名/控制器/方法*/
        $request_uri = ltrim($request_uri, '/'); //去除：/
        if ($request_uri) {//TODO:删除url字符中的空格
            $url_arr = explode($depr, $request_uri);
            $module_name = in_array($url_arr[0], $GLOBALS['MODULE_LIST']) ? array_shift($url_arr) : $GLOBALS['MODULE_LIST'][0]; //获取分组名
            $controller_name = empty($url_arr[0]) ? $routes['*']['/'][1] : array_shift($url_arr);
            $action_name     = empty($url_arr[0]) ? $routes['*']['/'][2] : array_shift($url_arr);
        } else {
            $module_name     = $GLOBALS['MODULE_LIST'][0];
            $controller_name = $routes['*']['/'][1];
            $action_name     = $routes['*']['/'][2];
        }

        //定义常量
        define('__APP__', __ROOT__); //todo
        define('GROUP_NAME', $module_name); //todo：删除
        define('GROUP_PATH', APP_PATH.'/'.strtolower(GROUP_NAME)); //todo:删除
        define('__GROUP__', GROUP_NAME == $GLOBALS['APP_GROUP_LIST'][0] ? __APP__ : __APP__.'/'.GROUP_NAME); //todo:删除
        define('MODULE_PATH', APP_PATH.'/'.strtolower(GROUP_NAME));
        define('MODULE_NAME', $module_name);
        define('CONTROLLER_NAME', $controller_name); //当前控制器的名称
        define('ACTION_NAME', $action_name); //当前控制器的动作
        define('__URL__', !empty($domainModule) ? __GROUP__.$depr : __GROUP__.$depr.CONTROLLER_NAME);
        define('__ACTION__', __URL__.$depr.ACTION_NAME); // 当前操作地址
        $_REQUEST = array_merge($_POST, $_GET); //保证$_REQUEST正常取值


        //加载文件
        if (is_file(MODULE_PATH.'/conf/config.php')) {
            C(include MODULE_PATH.'/conf/config.php');
        }
        if (is_file(MODULE_PATH.'/common/common.php')) {
            include MODULE_PATH.'/common/common.php';
        }

        //定义常用常量
        define('TABLEPRE',C('DB_PREFIX'));//表前缀

    }

}