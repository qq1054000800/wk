<?php
//上帝未满18岁 | <qq1054000800@gmail.com> | <www.phpjcw.com>
/*
 *Auth::check($name,$uid);//验证用户
 * */
class Auth {
	/**
	 * 根据用户id和规则判断用户是否符合
	 * @param $name 规则名
	 * @param $uid 用户id
	 * @return bool 返回通过规则与否
	 */
	static function check($name, $uid = '') {
		if (!C('AUTH_ON')) {
			return true;
		}
		if(empty($uid)){
			$uid = $_SESSION['userInfo']['id'];//默认登录用户
		}
		$authList = self::getAuthList($uid);
		if (in_array($name, $authList)) {
			return true;
		}
		return false;
	}

	/**
	 * 根据用户id获取用户的权限！
	 * @param $uid 用户的id
	 * @return mixed 该用户的权限
	 */
	static function getAuthList($uid) {
		static $_authList = array(); //规则列表
		$userInfo = self::getUserInfo($uid); //用户信息
		$gid      = $userInfo['group_id']; //规则组的id
		if (isset($_authList[$gid])) {
			return $_authList[$gid];
		}
		if (isset($_SESSION['_AUTH_LIST_'.$gid])) {
			return $_SESSION['_AUTH_LIST_'.$gid];
		}
		if (isset($userInfo['g_rules'])) {
			$_authList[$gid]              = explode(',', $userInfo['g_rules']);
			$_SESSION['_AUTH_LIST_'.$gid] = $_authList[$gid];
			return $_authList[$gid];
		}
		return array();
	}

	//获得用户组，外部也可以调用
	static function getUserInfo($uid) {
		if (isset($GLOBALS['userInfo'])) {
			return $GLOBALS['userInfo'];
		} elseif (isset($_SESSION['userInfo'])) {
			return $_SESSION['userInfo'];
		} else {
			//获取用户信息
			$Model = M('View_u_g');
			$sql = 'SELECT * FROM '.$Model->getTableName().' WHERE id = ? LIMIT 1';
			$userInfo = $Model->query($sql,array($uid));
			if ($userInfo) {
				$GLOBALS['userInfo']  = $userInfo;
				$_SESSION['userInfo'] = $userInfo;
				return $userInfo;
			}
			//todo:出错！！
		}
	}
}
