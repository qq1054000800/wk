<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>

class Curl {

    /**
     * 用CURL模拟获取网页页面内容
     * @param string  $url    所要获取内容的网址
     * @param array   $data   所要提交的数据
     * @return string
     */
    public static function get($url,$is_ssl = false) {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($is_ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);// 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);// 从证书中检查SSL加密算法是否存在
        }
        //设置浏览器
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //使用自动跳转
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//true返回数据 false输出到屏幕

        $content = curl_exec($ch);//执行url

        curl_close($ch);
        return $content;
    }

    /**
     * 用CURL模拟提交数据
     * @param string  $url    post所要提交的网址
     * @param array   $data   所要提交的数据
     * @param string  $proxy  代理设置
     * @param integer $expire 所用的时间限制
     * @return string
     */
    public static function post($url, $data = array(), $proxy = null, $expire = 30) {
        //分析是否开启SSL加密
        $ssl = substr($url, 0, 8) == 'https://' ? true : false;

        //读取网址内容
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);//设置url
        curl_setopt($ch, CURLOPT_POST, true);//post请求

        //设置代理
        if (!is_null($proxy)) {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }



        if ($ssl) {
            // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            // 从证书中检查SSL加密算法是否存在
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        }

        //cookie设置
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

        //设置浏览器
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //发送一个常规的Post请求

        //Post提交的数据包
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));//urlencode $data数据

        //使用自动跳转
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $expire);

        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }
}