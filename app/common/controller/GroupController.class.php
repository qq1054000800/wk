<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>

class GroupController extends Controller{

    public function _init(){
        /*定义模板常量*/
        define('THEME_PATH', GROUP_PATH.'/view/'.C('default_theme')); //主题路径
        define('__STATIC__',__ROOT__.'/static');//静态文件路径
        define('__THEME__', __ROOT__.'/static/'.GROUP_NAME.'/'.C('default_theme')); //当前theme url路径
        define('__JS__', __THEME__.'/js'); //js url路径
        define('__CSS__', __THEME__.'/css'); //css url路径
        define('__IMAGES__', __THEME__.'/images'); //images url路径
        define('SKIN_PATH',__ROOT__.'/static/'.GROUP_NAME.'/tmp');//tmp路径，要删除！
    }

}