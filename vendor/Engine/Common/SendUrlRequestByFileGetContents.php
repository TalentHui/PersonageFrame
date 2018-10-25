<?php
/***********************************************************************************************************************
 * COPYRIGHT: PhpStorm - PersonageFrame - SendUrlRequestByFileGetContents.php
 * TIMESTAMP: 2018/10/25 20:52
 * AUTHOR:    吴辉
 * DESC:      functional description...
 * ```
 *
 * ```
 ***********************************************************************************************************************/

namespace Engine\Common;


class SendUrlRequestByFileGetContents
{
    /** @var string 请求的连接 */
    protected static $_s_url = '';

    /** @var string 请求链接的请求类型，默认为GET */
    protected static $_s_http_request_type = 'GET';

    /** @var array  请求链接的参数 */
    protected static $_s_url_params = array();

    /** @var array  最后一次请求的信息 */
    protected static $_s_last_request_info = array();

    /**
     * @desc   设置 - 请求链接
     * @param  string $s_url
     */
    public static function setSUrl(string $s_url)
    {
        self::$_s_url = $s_url;
    }

    /**
     * @desc   设置 - 请求类型
     * @param  string $s_http_request_type
     */
    public static function setSHttpRequestType(string $s_http_request_type = 'POST')
    {
        self::$_s_http_request_type = $s_http_request_type;
    }

    /**
     * @desc   设置 - 请求链接参数
     * @param  array $s_url_params
     */
    public static function setSUrlParams(array $s_url_params)
    {
        self::$_s_url_params = $s_url_params;
    }

    /**
     * @desc   设置 - 最后一次请求的信息
     */
    public static function setSLastRequestInfo()
    {
        self::$_s_last_request_info = array(
            'url' => self::$_s_url,
            'type' => self::$_s_http_request_type,
            'params' => self::$_s_url_params,
        );
    }

    /**
     * @desc   获取 - 最后一次请求的信息
     * @return array
     */
    public static function getSLastRequestInfo()
    {
        return self::$_s_last_request_info;
    }

    public static function sendHttpRequest()
    {
        $response_string = '';

        try {
            do {
                self::setSLastRequestInfo();

                switch (true) {
                    case empty(self::$_s_url) && $response_string = 'Request Url Is Null':
                    case !in_array(self::$_s_http_request_type, array('GET', 'POST')) && $response_string = 'Request Type Error':
                    case !is_array(self::$_s_url_params) && $response_string = 'Request Params Not Array':
                        break 2;
                }

                $http_build_query = http_build_query(self::$_s_url_params);

                $context = array(
                    'http' => array(
                        'method' => self::$_s_url,
                        'header' => 'Content-type: application/x-www-form-urlencoded\r\n' .
                            'Content-length: ' . strlen($http_build_query) . '\r\n',
                        'content' => $http_build_query,
                        'timeout' => 1,
                    )
                );

                $response_string = file_get_contents(
                    self::$_s_url,
                    false,
                    stream_context_create($context)
                );


            } while (false);
        } catch (\Exception $e) {

        }

        return $response_string;
    }
}