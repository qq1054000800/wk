<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>

class DemoController extends  CommonController{

    public function index(){
        $this->display();
    }

    public function upload(){
        $arr = array(
            'a' => '哈哈哈哈哈',
            'b' => array(
                'c' => '呵呵呵呵呵',
                'd' => '嘿嘿嘿嘿嘿'
            ),
        );
        $this->assign('arr',$arr);
        $this->display();
    }
}