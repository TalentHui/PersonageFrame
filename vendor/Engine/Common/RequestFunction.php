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
     * @desc   通过 curl 发送 post 请求
     * @param  string $url     请求的Url路径
     * @param  array  $data    POST参数
     * @param  int    $timeout 超时处理
     * @param  bool   $CA      是否验证CA
     * @param  string $ca_file CA证书的存放位置
     * @return mixed
     */
    public static function curlPost($url = '', $data = array(), $timeout = 30, $CA = false, $ca_file = '')
    {

        /* CA根证书 */
        $ca_cert = getcwd() . $ca_file;
        $SSL = substr($url, 0, 8) == "https://" ? true : false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout - 2);

        if ($SSL && $CA) {

            /* 只信任CA颁布的证书 */
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            /* CA根证书（用来验证的网站证书是否是CA颁布）*/
            curl_setopt($ch, CURLOPT_CAINFO, $ca_cert);

            /**
             * 检查证书中是否设置域名，并且是否与提供的主机名匹配
             *
             * 设为0表示不检查证书
             * 设为1表示检查证书中是否有CN(common name)字段
             * 设为2表示在1的基础上校验当前的域名是否与CN匹配
             */
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        } else if ($SSL && !$CA) {

            /* 信任任何证书 */
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            /* 检查证书中是否设置域名 */
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        curl_setopt($ch, CURLOPT_POST, true);

        /* 避免data数据过长问题 */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
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
    public static function AjaxJsonReturn($data = array(), $desc = 'Success Response.', $status = 200)
    {
        echo json_encode(array(
            'status' => intval($status),
            'desc' => $desc,
            'data' => $data
        ));
    }
}