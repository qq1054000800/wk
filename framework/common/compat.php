<?php
/**
 * 兼容性处理的函数
 * ----------------------------------------------------------
 * Created with JetBrains PhpStorm.
 * Date: 13-11-7
 * Time: 上午10:57
 * 上帝未满18岁 | <qq1054000800@gmail.com> | <www.phpjcw.com>
 */

//This is a function that should work for both Windows XP/2003 and most distrabutions of UNIX and Mac OS X.
if (!function_exists('memory_get_usage')) {
	function memory_get_usage() {
		//If its Windows
		//Tested on Win XP Pro SP2. Should work on Win 2003 Server too
		//Doesn't work for 2000
		//If you need it to work for 2000 look at http://us2.php.net/manual/en/function.memory-get-usage.php#54642
		if (substr(PHP_OS, 0, 3) == 'WIN') {
			if (substr(PHP_OS, 0, 3) == 'WIN') {
				$output = array();
				exec('tasklist /FI "PID eq '.getmypid().'" /FO LIST', $output);
				return preg_replace('/[\D]/', '', $output[5]) * 1024;
			}
		} else {
			//We now assume the OS is UNIX
			//Tested on Mac OS X 10.4.6 and Linux Red Hat Enterprise 4
			//This should work on most UNIX systems
			$pid = getmypid();
			exec("ps -eo%mem,rss,pid | grep $pid", $output);
			$output = explode("  ", $output[0]);
			//rss is given in 1024 byte units
			return $output[1] * 1024;
		}
	}
}