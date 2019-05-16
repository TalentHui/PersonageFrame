<?php
/***********************************************************************************************************************
 * COPYRIGHT: PhpStorm - CurlRequest.php
 * TIMESTAMP: 2019.05.16 21:52
 * AUTHOR:    WuHui
 * DESC:      File Description...
 **********************************************************************************************************************/

namespace Engine\Common;


class CurlRequest
{
    /** @var string 请求类型 - get */
    const val_request_type_get = 'get';

    /** @var string 请求类型 - post */
    const val_request_type_post = 'post';

    protected static $_s_url_request = '';                           // str - 请求接口的链接
    protected static $_s_url_request_type = 'post';                  // str - 请求接口的类型 - 【POST|GET】
    protected static $_s_url_request_params = [];                    // arr - 请求接口的POST参数
    protected static $_s_url_https_key_path = '';                    // str - HTTPS请求，SSL密钥路径
    protected static $_s_url_https_cert_path = '';                   // str - HTTPS请求，SSL证书路径
    protected static $_s_url_https_cert_pwd = '';                    // str - HTTPS请求，使用SSL证书需要的密码

    protected static $_s_curl_connect_timeout_ms = 2;                // int - CURL函数在尝试连接时等待的毫秒数。设置为0，则无限等待
    protected static $_s_curl_run_timeout_ms = 10;                   // int - CURL函数执行的最长毫秒数

    protected static $_s_last_curl_info = [];                        // arr - 最后一次CURL请求的curl_getinfo()信息
    protected static $_s_last_curl_errno = 0;                        // int - 最后一次CURL请求的错误号
    protected static $_s_last_curl_error = '';                       // str - 最后一次CURL请求的错误信息

    /**
     * @desc   设置 - 请求接口的链接
     * @param string $url
     */
    public static function setRequestUrl($url = '')
    {
        self::$_s_url_request = $url;
    }

    /**
     * @desc   设置 - 请求类型 - 【POST|GET】
     * @param string $request_type
     */
    public static function setRequestUrlType($request_type = '')
    {
        self::$_s_url_request_type = $request_type;
    }

    /**
     * @desc   设置 - 请求接口的POST参数
     * @param array $request_params
     */
    public static function setRequestUrlParams(array $request_params = [])
    {
        self::$_s_url_request_params = $request_params;
    }

    /**
     * @desc   设置 - CURL函数在尝试连接时等待的毫秒数。设置为0，则无限等待
     * @param int $m_seconds
     */
    public static function setCurlConnectTimeout($m_seconds = 2000)
    {
        self::$_s_curl_connect_timeout_ms = $m_seconds;
    }

    /**
     * @desc   设置 - CURL函数执行的最长毫秒数
     * @param int $m_seconds
     */
    public static function setCurlRunTimeout($m_seconds = 10000)
    {
        self::$_s_curl_run_timeout_ms = $m_seconds;
    }

    /**
     * @desc   设置 - 最后一次CURL请求的信息
     * @param array $curl_info
     * @param int $curl_errno
     * @param string $curl_error
     */
    public static function setLastCurlInfo(array $curl_info = [], $curl_errno = 0, $curl_error = '')
    {
        self::$_s_last_curl_info = $curl_info;
        self::$_s_last_curl_errno = $curl_errno;
        self::$_s_last_curl_error = $curl_error;
    }

    /**
     * @desc   获取 - 最后一次CURL请求的信息
     * @return array
     */
    public static function getLastCurlInfo()
    {
        return [
            'curl_info' => self::$_s_last_curl_info,
            'curl_errno' => self::$_s_last_curl_errno,
            'curl_error' => self::$_s_last_curl_error,
        ];
    }

    /**
     * @desc   拼接参数并发送请求
     * @param string $url 请求接口的链接
     * @param array $request_params 请求接口的POST参数
     * @param string $request_type 请求接口的类型 - 【POST|GET】
     * @param int $connect_timeout_ms CURL函数在尝试连接时等待的毫秒数。设置为0，则无限等待
     * @param int $run_timeout_ms CURL函数执行的最长毫秒数
     * @return array
     */
    public static function packParamsSendRequest(
        $url = '',
        array $request_params = [],
        $request_type = 'post',
        $connect_timeout_ms = 2000,
        $run_timeout_ms = 10000
    )
    {
        self::setRequestUrl($url);
        self::setRequestUrlParams($request_params);
        self::setRequestUrlType($request_type);
        self::setCurlConnectTimeout($connect_timeout_ms);
        self::setCurlRunTimeout($run_timeout_ms);

        return self::sendRequest();
    }

    /**
     * @desc   发送请求
     * @return array
     */
    public static function sendRequest()
    {
        self::setLastCurlInfo();

        $ch = curl_init();

        // 设置 - 请求的接口
        curl_setopt($ch, CURLOPT_URL, self::$_s_url_request);

        // 设置 - 获取信息的返回方式 【false: 将curl_exec()获取的信息直接输出; true: 将curl_exec()获取的信息以字符串返回，而不是直接输出;】
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // 设置 - 请求类型为POST
        if (strtolower(self::$_s_url_request_type) == self::val_request_type_post && self::$_s_url_request_params) {
            $post_query_params = is_array(self::$_s_url_request_params)
                ? self::$_s_url_request_params
                : self::$_s_url_request_params;

            // 设置 - 发送POST请求，类型为：application/x-www-form-urlencoded。
            curl_setopt($ch, CURLOPT_POST, true);

            /**
             * POST参数形式 STRING | ARRAY
             * STRING - Content-Type头将会被设置成application/x-www-form-urlencoded
             * ARRAY  - Content-Type头将会被设置成multipart/form-data
             */
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_query_params);
        }

        // 设置 - HTTPS请求处理
        $is_https = (substr(self::$_s_url_request, 0, 8) == 'https://') ? true : false;

        if ($is_https) {
            if (self::$_s_url_https_cert_path && self::$_s_url_https_key_path) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_SSLCERT, self::$_s_url_https_cert_path);
                curl_setopt($ch, CURLOPT_SSLKEY, self::$_s_url_https_key_path);

                if (self::$_s_url_https_cert_pwd) {
                    curl_setopt($ch, CURLOPT_SSLCERTPASSWD, self::$_s_url_https_cert_pwd);
                }
            } else {
                /**
                 * 验证对等证书
                 * TRUE  - 启用 CURL 验证对等证书（peer's certificate）
                 * FALSE - 禁止 CURL 验证对等证书（peer's certificate）
                 */
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                /**
                 * 公用名(Common Name)一般来讲就是填写你将要申请SSL证书的域名 (domain)或子域名(sub domain)。
                 * 0 - 检查公用名是否存在，并且是否与提供的主机名匹配。
                 * 1 - 检查服务器SSL证书中是否存在一个公用名(common name)。
                 * 2 - 不检查名称。
                 */
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            }
        }

        // 设置 - 忽略所有的 cURL 传递给 PHP 进行的信号 [true: 忽略;false: 不忽略;]
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);

        // 设置 - 在尝试连接时等待的毫秒数。设置为0，则无限等待。
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::$_s_curl_connect_timeout_ms);

        // 设置 - 允许CURL函数执行的最长毫秒数。
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, self::$_s_curl_run_timeout_ms);

        $response_result = curl_exec($ch);

        self::setLastCurlInfo(curl_getinfo($ch), curl_errno($ch), curl_error($ch));

        curl_close($ch);

        return $response_result;
    }
}