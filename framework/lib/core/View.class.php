<?php
//上帝未满18岁 | <qq1054000800@gmail.com> | <www.phpjcw.com>

class View {
    private $compileDir; //模板编译后路径
    private $tVar = array(); //模板输出变量
    static $phpCode; //模板中的php代码

    public function __construct() {
        $this->compileDir = CACHE_PATH.'/'.GROUP_NAME.'/tpl'; //设置缓存文件地址
        if (!is_dir($this->compileDir)) {
            mkdir($this->compileDir, 0755, true);
        }
    }

    //变量赋值
    public function assign($name, $value = '') {
        $this->tVar[$name] = $value;
    }

    /**
     * 输出解析后的模板文件
     * $templateFile 查找的模板文件名
     * a.当不输入$templateFile ，默认当前操作的文件
     * b.当输入的文件名不带路径 / ，表示当前主题下的文件名
     * c.当输入的文件名带有路径 / ，表示跨项目。定位于APP_PATH 自己组装路径
     */
    public function display($path = null) {
        //告诉浏览器一些内容的信息
        header('Content-Type: text/html;charset='.C('DEFAULT_CHARSET')); //内容类型
        header('Cache-control:private'); // 页面缓存控制
        //todo:这里可以判断ajax请求，然后返回json数据。现在仅仅返回html。不建议返回json，因为返回数据不必处理，直接显示！


        $tplName = $this->getTplName($path); //模板的文件名
        $cplName = $this->getCplName($tplName); //模板编译后的文件名
        if (!HAS_CACHE || !file_exists($cplName) || filemtime($tplName) >= filemtime($cplName)) {
            self::compile($tplName, $cplName); //编译模板文件
        }

        extract($this->tVar, EXTR_OVERWRITE); //阵列变量
        ob_start();
        ob_implicit_flush(false);
        include($cplName.'');
        $html = ob_get_clean(); //todo:这里可以缓存静态页面，可以删除注释和压缩文件

        echo $html; //输出静态文件
        exit;
    }


    /**
     * 编译模板文件
     * @param $tplName 模板文件名
     * @param $cplName 编译后文件名
     * 注意：
     * 变量命名：$[a-zA-Z_]\w*
     * 模板中使用数组：$a['b']['c'] => $a.b.c
     * 模板中使用对象：直接使用php代码
     * todo:防止上传文件包含{php危险代码}或者<?php 危险代码?>
     */
    private function compile($tplName, $cplName) {
        $tpl = @file_get_contents($tplName); //获取模板
        //获取子页面
        $tpl = preg_replace("/\{include\s+([a-z0-9_\/]+)\}/ie", "file_get_contents(self::getTplName('\\1'))", $tpl);
        //获取二级子页面
        $tpl = preg_replace("/\{include\s+([a-z0-9_\/]+)\}/ie", "file_get_contents(self::getTplName('\\1'))", $tpl);
        //处理php标签
        preg_match_all('/\<\?php.*?\?\>/is', $tpl, $phpCode);
        self::$phpCode = $phpCode[0];
        $tpl           = preg_replace('/\<\?php.*?\?\>/is', '_@#$%^&*%@_', $tpl); //todo:这个字符是非法字符

        //todo:这里小心与js冲突！
        //三维数组 $a.b.c => $a['b']['c']
        $tpl = preg_replace("/(\\\$[a-zA-Z_]\w*)\.([a-zA-Z_]\w*)\.([a-zA-Z_]\w*)/", "\\1['\\2']['\\3']", $tpl);
        //二维数组 $a.b => $a['b']
        $tpl = preg_replace("/(\\\$[a-zA-Z_]\w*)\.([a-zA-Z_]\w*)/", "\\1['\\2']", $tpl);
        //三维数组 $a[b][c] => $a['b']['c']
        $tpl = preg_replace("/(\\\$[a-zA-Z_]\w*)\[([a-zA-Z_]\w*)\]\[([a-zA-Z_]\w*)\]/", "\\1['\\2']['\\3']", $tpl);
        //二维数组 $a[b] => $a['b']
        $tpl = preg_replace("/(\\\$[a-zA-Z_]\w*)\[([a-zA-Z_]\w*)\]/", "\\1['\\2']", $tpl);

        //todo:功能加强！
        $tpl = preg_replace("/\{tag\s+([^!@#$%^&*(){}<>?,.\'\"\+\-\;\":~`]+)\}/ie", "self::readtag(\"'\\1'\")", $tpl);
        $tpl = preg_replace("/\{showad\((.+?)\)\}/ie", "self::showad('\\1')", $tpl);

        //逻辑
        $tpl = preg_replace("/\{if\s+(.+?)\}/i", '<?php if(\\1) { ?>', $tpl);
        $tpl = preg_replace("/\{elseif\s+(.+?)\}/is", '<?php } elseif(\\1) { ?>', $tpl);
        $tpl = preg_replace("/\{else\}/is", "<?php } else { ?>", $tpl);
        $tpl = preg_replace("/\{\/if\}/", "<?php } ?>", $tpl);
        //循环
        $tpl = preg_replace("/\{loop\s+(\S+)\s+(\S+)\}/", '<?php if(is_array(\\1)) {$_count = count(\\1);$_index = 0;foreach(\\1 as \\2) {$_index++; ?>', $tpl);
        $tpl = preg_replace("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/", '<?php if(is_array(\\1)) {$_count = count(\\1);$_index = 0;foreach(\\1 as \\2 => \\3) {$_index++; ?>', $tpl);
        $tpl = preg_replace("/\{\/loop\}/", '<?php }} ?>', $tpl);

        //输出常量
        $tpl = preg_replace("/\{([a-zA-Z_]\w*)\}/", "<?php echo \\1;?>", $tpl);
        //输出变量
        $tpl = preg_replace("/\{(\\\$[a-zA-Z_][\w\.\'\"\[\]]*)\}/i", "<?php echo \\1;?>", $tpl);

        //换行
        $tpl = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $tpl);
        $tpl = str_replace('<?=', '<?php echo ', $tpl);

        //恢复php语句
        $tpl = preg_replace_callback('/\_\@\#\$\%\^\&\*\%\@\_/is', 'self::__mcallback', $tpl);

        @file_put_contents($cplName, $tpl); //保存编译文件 todo：压缩编译文件
    }

    /**
     * 恢复php语句
     * @return mixed
     */
    static function __mcallback() {
        return array_shift(self::$phpCode);
    }


    /**
     * 获取模板文件名，仅能调用同模块的模板！
     * @param $path 模板的path
     *      当path 为空时，自动定位到当前控制器文件夹下的动作名的文件
     *      但path 不为空时，使用模板文件名作为path
     */
    private function getTplName($path = null) {
        $tplName = is_null($path) ? MODULE_PATH.'/view/'.C('DEFAULT_THEME').'/'.CONTROLLER_NAME.'_'.ACTION_NAME.C('TPL_SUFFIX') : GROUP_PATH.'/view/'.C('DEFAULT_THEME').'/'.$path.C('TPL_SUFFIX');
        is_file($tplName) OR halt($tplName.' [TPL NOT EXIST]');
        return $tplName;
    }

    /**
     * 返回编译后的文件名
     * @param $tplName 模板的文件名
     * @return string
     */
    private function getCplName($tplName) {
        return $this->compileDir.'/'.basename($tplName);
    }

}
