<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>
/*
 * 七牛云储存操作类
 * 注意sdk的路径问题！
 * */
require_once(VENDOR_PATH."/qiniu/rs.php");
class QiNiu {
	const ACCESSKEY = 'crgqeP-SmyZZVSCo_O6cCzVlXiVqS13n_T6R37aO';
	const SECRETKEY = 'nllCS6C_d3qkVzH_VdfbGehn2XjJRmO3RanODOtq';
	const BUCKET    = "phpjcw";

	static public function upload($key, $filename) {
		require_once(VENDOR_PATH."/qiniu/io.php");
		Qiniu_SetKeys(self::ACCESSKEY, self::SECRETKEY);
		$putPolicy       = new Qiniu_RS_PutPolicy(self::BUCKET);
		$upToken         = $putPolicy->Token(null);
		$putExtra        = new Qiniu_PutExtra();
		$putExtra->Crc32 = 1;
		list($ret, $err) = Qiniu_PutFile($upToken, $key, $filename, $putExtra);
		if ($err !== null) {
			return $err;
		} else {
			return $ret;
		}
	}

	static public function fileInfo($key) {
		return Qiniu_RS_Stat(new Qiniu_MacHttpClient(null), self::BUCKET, $key);
	}

	/**
	 * 获取文件路径的前缀
	 */
	static public function prefix() {
		static $prefix = 'http://phpjcw.qiniudn.com';
		return $prefix;
	}
}