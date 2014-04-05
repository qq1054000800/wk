<?php
//上帝未满18岁 | <qq1054000800@gmail.com> | <www.phpjcw.com>

/**
 * Framework运行的条件：
 * PHP版本大于5.2  version_compare()
 * 关闭自动转义    ini_set('magic_quotes_runtime', 0)
 */
define('EXT', '.class.php'); //类的后缀 todo:删除
if ((bool)ini_get('safe_mode') == true) {
    ini_set('safe_mode', 'Off');
}
if ((bool)get_magic_quotes_runtime() == true) {
    ini_set('magic_quotes_runtime', 'Off');
}

/*定义常用系统路径*/
define('CORE_VERSION', '1.0');
define('ROOT_PATH', dirname($_SERVER['SCRIPT_FILENAME'])); //主入口文件路径 index.php路径
define('CORE_PATH', dirname(__FILE__)); //当前文件core.php路径
define('APP_PATH', ROOT_PATH.'/'.APP_NAME); //项目app路径
define('DEFAULT_GROUP', $GLOBALS['APP_GROUP_LIST'][0]);
define('SYS_START_TIME', microtime(true)); //系统开始时间
define('CURRENT_TIME', time()); //系统当期时间戳
define('__ROOT__', 'http://'.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '')); //当前访问的主机名
define('RUNTIME_PATH', ROOT_PATH.'/cache'); //缓存目录
define('IS_CGI', substr(PHP_SAPI, 0, 3) == 'cgi' ? true : false);
define('IS_WIN', strstr(PHP_OS, 'WIN') ? true : false);

/*定义常用路径*/
define('COMMON_PATH', CORE_PATH.'/common'); //公共目录
define('LIB_PATH', CORE_PATH.'/lib'); //核心类库
define('EXTEND_PATH', LIB_PATH.'/extend'); //扩展类库
define('VENDOR_PATH', LIB_PATH.'/vendor'); //第三方类库
define('CACHE_PATH', ROOT_PATH.'/cache'); //缓存目录
define('CACHE_COMMON_PATH', CACHE_PATH.'/common'); //公共缓存目录
define('UPLOAD_PATH', ROOT_PATH.'/uploads'); //文件上传路径
define('UPLOAD_URL', __ROOT__.'/uploads'); //文件上传url路径
define('STATIC_PATH', ROOT_PATH.'/static'); //静态文件目录
define('APP_COMMON_PATH', APP_PATH.'/common'); //项目路径常量
define('COMMON_VENDOR_PATH', APP_COMMON_PATH.'/vendor'); //项目第三方类库
define('HAS_CACHE', false); //定义一些需要的常量

set_include_path(get_include_path().PATH_SEPARATOR.VENDOR_PATH); //为了方便导入第三方类库 设置Vendor目录到include_path

require CORE_PATH.'/common/common.php'; //加载公共函数库
require CORE_PATH.'/lib/core/Kwx.class.php';
require CORE_PATH.'/lib/core/KwxException.class.php';
require CORE_PATH.'/lib/core/Cache.class.php';
require CORE_PATH.'/lib/core/Behavior.class.php';

if (!is_dir(APP_PATH)) {
    build_app_dir(); //生成项目目录
}
function safe() {
    defined('CORE_VERSION') or exit();
}

Kwx::start();