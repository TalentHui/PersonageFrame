<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - JWT.php
 * Author:    WuHui
 * Date:      2018-01-30 11:25
 * Desc:        JWT是为了在网络应用环境间传递声明而执行的一种基于JSON的开放标准（(RFC 7519).
 *            该token被设计为紧凑且安全的，特别适用于分布式站点的单点登录（SSO）场景。
 * href：      https://www.jianshu.com/p/576dbf44b2ae
 *******************************************************************************/

namespace Engine\Common;


class JWT
{
    const key = '@encrypt*string';

    /**
     * @param  array| string $data 自定义数据
     * @param  string $key 验证key
     * @param  int $timeout  过期时间
     * @param  string $alg 加密类型
     * @return string 数据
     */
    public static function encode($data, $timeout = 36000, $alg = 'SHA256', $key = null)
    {
        $key = md5($key ? $key : self::key);
        $cur_time = time();

        $header_base64 = base64_encode(json_encode([
            'typ' => 'JWT',
            'alg' => $alg
        ]));
        $header_base64 = str_replace('=', '', $header_base64);

        $payload_base64 = base64_encode(json_encode(array(
            "iss" => "FTalk",
            "iat" => $cur_time,
            "exp" => $cur_time + $timeout,
            "data" => $data
        )));
        $payload_base64 = str_replace('=', '', $payload_base64);

        $signature = hash_hmac($alg, $header_base64 . '.' . $payload_base64, $key);

        $jwt = $header_base64 . '.' . $payload_base64 . '.' . $signature;

        return $jwt;
    }

    /**
     * @param $jwt
     * @param null $key
     * @return bool | array | string 失败或数据
     */
    public static function decode($jwt, $key = null)
    {
        $key = md5($key ? $key : self::key);
        $cur_time = time();

        $tokens = explode('.', $jwt);
        if (count($tokens) != 3) return false;

        list($header_base64, $payload_base64, $signature) = $tokens;

        $header = json_decode(base64_decode($header_base64), true);
        $payload = json_decode(base64_decode($payload_base64), true);

        if (!isset($header['alg'])) {
            return false;
        }

        if ($signature !== hash_hmac($header['alg'], $header_base64 . '.' . $payload_base64, $key)) {
            return false;
        }

        if (!isset($payload['iss']) || $payload['iss'] !== 'FTalk') {
            return false;
        }

        if (!isset($payload['iat']) || $payload['iat'] > $cur_time) {
            return false;
        }

        if (!isset($payload['exp']) || $payload['exp'] < $cur_time) {
            return false;
        }

        if (!isset($payload['data'])) {
            return false;
        }

        return $payload['data'];
    }
}