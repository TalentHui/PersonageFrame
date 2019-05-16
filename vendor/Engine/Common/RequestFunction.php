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
     * @desc   发送 post 请求 - file_get_contents
     * @param  $url
     * @param  $post_data
     * @param  int $timeout_seconds
     * @return bool|string
     */
    public static function sendPostByFileGetContents($url, $post_data, $timeout_seconds =3)
    {
        $context_options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($post_data) . "\r\n",
                'content' => $post_data,
                'timeout' => $timeout_seconds,
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
}