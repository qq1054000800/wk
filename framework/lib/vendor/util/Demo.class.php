<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>

class Demo {
    static function quickSort($arr) {
        if (count($arr) > 1) {
            $k     = $arr[0]; //第一个数组元素
            $x     = array();
            $y     = array();
            $_size = count($arr);
            for ($i = 1; $i < $_size; $i++) {
                if ($arr[$i] <= $k) {
                    $x[] = $arr[$i]; //小于等于第一个的数组集合
                } else {
                    $y[] = $arr[$i]; //大于第一个的数组集合
                }
            }
            $x = quickSort($x);
            $y = quickSort($y);
            return array_merge($x, array($k), $y);
        } else {
            return $arr;
        }
    }

    static function mpSort($arr) {
        $arr = array_values($arr);
        $num = count($arr);
        for ($i = 0; $i < $num; $i++) {
            for ($j = $num - 1; $j > $i; $j--) {
                if ($arr[$i] > $arr[$j]) {
                    $tmp     = $arr[$i];
                    $arr[$i] = $arr[$j];
                    $arr[$j] = $tmp;
                }
            }
        }
        return $arr;
    }
}