<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - RequestFunction.php
 * Author:    WuHui
 * Date:      2017-08-25 23:50
 * Desc:      请求
 *******************************************************************************/

namespace Engine\Common;


class RequestFunction
{
    /**
     * @desc   检测是否为命令行运行
     * @return bool
     */
    public static function isCommandLine()
    {
        if ((defined('PHP_SAPI') && PHP_SAPI === 'cli') || php_sapi_name() === 'cli') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @desc   发送 post 请求
     * @param  string $url     请求的Url路径
     * @param  array  $data    POST参数
     * @param  int    $timeout 超时处理
     * @param  bool   $CA      是否验证CA
     * @param  string $ca_file CA证书的存放位置
     * @return mixed
     */
    public static function curlPost($url = '', $data = array(), $timeout = 30, $CA = false, $ca_file = '')
    {
        $ca_cert = getcwd() . $ca_file;
        $SSL = substr($url, 0, 8) == "https://" ? true : false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout - 2);

        if ($SSL && $CA) {

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_CAINFO, $ca_cert);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        } else if ($SSL && !$CA) {

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }

    /**
     * @desc   发送 get 请求
     * @param  $url
     * @param  int $timeout
     * @return mixed
     */
    public static function curlGet($url, $timeout = 1)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }

    /**
     * @desc   发送 post 请求 - file_get_contents
     * @param  $url
     * @param  $post_data
     * @return bool|string
     */
    public static function sendPostByFileGetContents($url, $post_data)
    {
        $context_options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($post_data) . "\r\n",
                'content' => $post_data
            )
        );
        $context = stream_context_create($context_options);

        $result = file_get_contents($url, 0, $context);
        return $result;
    }

    /**
     * @desc   检测是不是 ajax 的 get 请求
     * @return bool
     */
    public static function ajax_get()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && strtolower($_SERVER['REQUEST_METHOD']) == 'get') {
            return true;
        }

        return false;
    }

    /**
     * @desc   检测是不是 ajax 的 post 请求
     * @return bool
     */
    public static function ajax_post()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            return true;
        }

        return false;
    }

    /**
     * @desc  ajax 的返回值
     * @param array $data
     * @param string $desc
     * @param int $status
     */
    public static function ajaxJsonReturn($data = array(), $desc = 'Success Response.', $status = 200)
    {
        echo json_encode(array(
            'status' => intval($status),
            'desc' => $desc,
            'data' => $data
        ));
    }

    /**
     * @desc 服务器是否为linux系统
     */
    public static function isLinuxSystemOfServer()
    {
        return array(
            'status' => strstr(PHP_OS, 'WIN') ? false : true,
            'details' => PHP_OS
        );
    }

    /**
     * @desc 检查客户端是否为 iphone
     */
    public static function isIphoneDevice()
    {
        $is_phone = false;

        if (preg_match_all('/iphone; ios ([0-9._]*)/i', $_SERVER['HTTP_USER_AGENT'], $match)) {
            $is_phone = true;
        } else {
            preg_match_all('/iphone; ios ([0-9._]*)/i', $_SERVER['HTTP_USER_AGENT'], $match);
        }

        return array(
            'status' => $is_phone,
            'version' => $match['0']['0'],
            'details' => $match['0']['1']
        );
    }

    /**
     * @desc 获取客户端的系统类型
     */
    public static function getRemoteEquipmentType()
    {

    }

    /**
     * @desc 获取代理服务器地址
     */
    public static function getProxyIpAddress()
    {

    }
}