<?php
/**
 * Created with JetBrains PhpStorm.
 * Date: 2014-4-2
 * 上帝未满18岁 | <qq1054000800@gmail.com> | <www.phpjcw.com>
 */
require './fb.php';
ob_start();

error_reporting(E_ALL);
define('APP_NAME','app');//项目名称
define('APP_DEBUG',true);
$GLOBALS['APP_GROUP_LIST'] = array('home','admin');//分组 todo:删除
$GLOBALS['MODULE_LIST'] = array('home','admin');//分组，小写！
include './framework/core.php';