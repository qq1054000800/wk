<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>

//写入文件
function write_file($filename,$content){
	if(!is_dir(dirname($filename))){
		mkdir(dirname($filename),0755,true);
	}
	return file_put_contents($filename,$content);
}