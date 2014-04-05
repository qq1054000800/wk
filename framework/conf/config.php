<?php
//上帝未满18岁 | <qq1054000800@gmail.com> | <www.phpjcw.com>
safe();
return array(
    /*****************************begin url相关*********************************/
    // URL禁止访问的后缀设置
    //兼容模式 不推荐使用
    'VAR_PATHINFO'          => 's',
    //PATHINFO 兼容模式获取变量例如 ?s=/module/action/id/1 后面的参数取决于URL_PATHINFO_DEPR
    //子域名
    'APP_SUB_DOMAIN_DEPLOY' => false,
    // 是否开启子域名部署
    'APP_SUB_DOMAIN_RULES'  => array(),
    // 子域名部署规则
    'APP_SUB_DOMAIN_DENY'   => array(),
    //  子域名禁用列表
    //默认控制器 和 动作名称
    'DEFAULT_MODULE'        => 'Index',
    // 默认控制器名字
    'DEFAULT_ACTION'        => 'index',
    // 默认动作名字
    /*****************************end url相关*********************************/
    'DEFAULT_M_LAYER'       => 'Model',
    // 默认的模型层名称
    'DEFAULT_C_LAYER'       => 'Controller',
    // 默认的控制器层名称
    'DEFAULT_V_LAYER'       => 'View',
    // 默认的视图层名称
    /****************************begin 模板*******************************************/
    'TPL_SUFFIX'  => '.tpl',
    // 默认模板文件后缀
    'DEFAULT_THEME'         => 'default',
    // 默认模板主题名称
    'DEFAULT_CHARSET'       => 'UTF-8',
    'DB_CHARSET'            => 'utf8',
    //数据库编码默认采用utf8
    'TMPL_ACTION_ERROR'     => CORE_PATH.'/view/dispatch_jump.tpl',
    // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   => CORE_PATH.'/view/dispatch_jump.tpl',
    // 默认成功跳转对应的模板文件
    'TMPL_EXCEPTION_FILE'   => CORE_PATH.'/view/think_exception.tpl',
    // 异常页面的模板文件
    /****************************end 模板*********************************************/
    /*缓存*/
    //默认数据缓存类型
    'DATA_CACHE_TYPE'       => 'Memcache',

    //数据库
    //PDO连接方式
    'DB_ENGINE'             => 'pdo',
    // 数据库引擎
    'DB_PREFIX'             => 'kwx_',
    // 数据库表前缀
    'DB_DSN'                => 'mysql://root:@localhost:3306/bsxCompany',

    //认证
    'AUTH_ON'               => true,
    //认证开关
    'AUTH_TYPE'             => 1,
    // 认证方式，1为时时认证；2为登录认证。
    'AUTH_GROUP'            => 'think_auth_group',
    //用户组数据表名
    'AUTH_GROUP_ACCESS'     => 'think_auth_group_access',
    //用户组明细表
    'AUTH_RULE'             => 'think_auth_rule',
    //权限规则表
    'AUTH_USER'             => 'think_members',
    //用户信息表

    /*路由信息*/
    'URL_DEPR'              => '/',
    'URL_HTML_SUFFIX'       => '.html',
    'URL_DENY_SUFFIX'       => 'ico|png|gif|jpg',
    'ROUTES'                => array(
        '*'       => array(
            '/' => array(
                'home',
                'Index',
                'index'
            ),
        ),
        /*RESTful routes*/
        'get'     => array(),
        'post'    => array()
    ),
);