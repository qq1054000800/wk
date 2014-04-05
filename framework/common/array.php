<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>

/**
 * 根据数组元素路径获取元素和设置元素的值
 * @param        $arr  在$arr 树中获取值
 * @param        $path 路径
 * @param        $val  赋值操作，null时表示获取值
 * @param string $d    分割符号 默认 ,
 * @return
 * <code>
 *         arr($arr,'1,5,6')
 *         arr($arr,'1,5,6','hahah')
 * </code>
 */
function arr(&$arr, $path, $val = null, $d = ',') {
	foreach (explode($d, $path) as $v) {
		is_array($arr) || $arr = array();
		if ($v) {
			isset($arr[$v]) || $arr[$v] = array();
			$arr = & $arr[$v];
		}
	}
	if ($val === null) {
		return $arr;
	} else {
		$arr = $val;
	}
}
