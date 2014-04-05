<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>
/*
 * 一些工具函数
 * get_client_ip() 获取客户端ip
 *
 * */
class Tool {

	/**
	 * 获取客户端IP地址
	 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
	 * @return mixed
	 */
	static function get_client_ip($type = 0) {
		$type = $type ? 1 : 0;
		static $ip = null;
		if ($ip !== null) {
			return $ip[$type];
		}
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos = array_search('unknown', $arr);
			if (false !== $pos) {
				unset($arr[$pos]);
			}
			$ip = trim($arr[0]);
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		// IP地址合法验证
		$long = sprintf("%u", ip2long($ip));
		$ip   = $long
			? array(
				$ip,
				$long
			)
			: array(
				'0.0.0.0',
				0
			);
		return $ip[$type];
	}

	//根据经纬度获取数据
	static function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
		$theta      = $longitude1 - $longitude2;
		$miles      = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (
			cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
		$miles      = acos($miles);
		$miles      = rad2deg($miles);
		$miles      = $miles * 60 * 1.1515;
		$feet       = $miles * 5280;
		$yards      = $feet / 3;
		$kilometers = $miles * 1.609344;
		$meters     = $kilometers * 1000;
		return compact('miles', 'feet', 'yards', 'kilometers', 'meters');
	}

	//json_encode
	static function jsonx_encode($array) {
		static $replace=array();
		if(!$replace) {
			for($i=0; $i < 0x20; $i++) {
				//$t = $find[$i];
				$t = chr($i);
				$replace[$t] = sprintf("\\u%04x",$i);
			}
			$replace["\\"]="\\\\";
			$replace["\""]="\\\"";
			$replace["\t"]="\\t";
			$replace["\r"]="\\r";
			$replace["\n"]="\\n";
			$replace["\x0C"]="\\f";
			$replace["\x08"]="\\b";
		}

		$str = '';
		if(is_array($array) || is_object($array)) {
			$array = (array) $array;
			$is_o = array_keys($array) !== range(0, count($array) - 1);
			$str .= ($is_o ? "{" : '[');
			$is_start=0;
			foreach($array as $k => $v) {
				$str .= ($is_start ? "," : '');
				$is_start =1;
				if($is_o){
					$str .= '"';
					$str .= strtr($k, $replace);
					$str .= '":';
				}
				$t = __FUNCTION__;
				$str .= $t($v);
			}
			$str .= ($is_o ? "}" : ']');
		}
		elseif(is_string($array)) {
			$str .= '"';
			$str .= strtr($array, $replace);
			$str .= '"';
		}
		elseif(is_numeric($array)) {
			$str .= $array;
		}
		else{
			$str .= 'null';
		}
		return $str;
	}

}
