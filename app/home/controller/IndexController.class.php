<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>

class IndexController extends CommonController{


    function index(){
        $arr = array(
            'a' => 'aaaaaaa',
            'b' => array(
                'c' => 'ccccccc',
            )
        );
        $this->assign('arr',$arr);
        $this->display();
    }


}