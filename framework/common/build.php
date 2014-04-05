<?php
/**
 * Created with JetBrains PhpStorm.
 * Date: 13-11-9
 * Time: 上午9:33
 * 上帝未满18岁 | <qq1054000800@gmail.com> | <www.phpjcw.com>
 */
//生成目录安全文件：index.html
function build_dir_secure($dirs = array()){
	foreach($dirs as $value){
		file_put_contents($value.'/index.html','');
	}
}
//自动创建APP目录
function build_app_dir(){
	mkdir(APP_PATH,0755,true);//生成项目目录
	if(is_writeable(APP_PATH)){

		$_temp_group = $GLOBALS['APP_GROUP_LIST'];
		$_temp_group[] = 'common';
		$dirs = array();//需要生成的路径，数组
		foreach($_temp_group as $value){
			$dirs[] = APP_PATH.'/'.$value;
			$dirs[] = APP_PATH.'/'.$value.'/common';
			$dirs[] = APP_PATH.'/'.$value.'/conf';
			$dirs[] = APP_PATH.'/'.$value.'/controller';
			$dirs[] = APP_PATH.'/'.$value.'/model';
			$dirs[] = APP_PATH.'/'.$value.'/view';
		}
		foreach($dirs as $value){
			if(!is_dir($value)){mkdir($value,0755,true);}
		}
		build_dir_secure($dirs);//生成安全文件 index.html
	}
}