<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>

class Math {

	/**
	 * 检测一个值是否在范围内
	 * @param      $val 要检测的值
	 * @param null $min 最小值
	 * @param null $max 最大值
	 * @return bool
	 */
	static public function compareSize($val, $min = null, $max = null) {
		$boolMin = is_null($min); //是否设置最小值 true=>未设置值 false=>设置了值
		$boolMax = is_null($max); //是否设置最大值
		if (!($boolMin && $boolMax) && ($boolMin ? true : $val >= $min) && ($boolMax ? true : $val <= $max)) {
			return true;
		}
		return false;
	}


}