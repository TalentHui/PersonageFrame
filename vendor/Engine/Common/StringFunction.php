<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - StringFunction.php
 * Author:    WuHui
 * Date:      2017-08-26 00:00
 * Desc:      功能描述
 *******************************************************************************/

namespace Engine\Common;


class StringFunction
{
    /**
     * @desc   将字符串转换为数字
     * @param  $string
     * @return int
     */
    public static function stringExchangeNumber($string)
    {
        /**
         * crc32() 函数计算字符串的 32 位 CRC（循环冗余校验）。
         *
         * 该函数可用于验证数据完整性。
         *
         * 提示：为了确保从 crc32() 函数中获得正确的字符串表示，您需要使用 printf() 或 sprintf() 函数的 %u 格式符。
         * 如果未使用 %u 格式符，结果可能会显示为不正确的数字或者负数。
         */
        $number = printf('%u', crc32($string));
        return $number;
    }

    /**
     * @desc   按照需要的长度从头截取字符串
     * @param  string $content
     * @param  int    $length
     * @return int|string
     */
    public static function subStrChineseCharacter($content = '', $length = 10)
    {
        $content_len = mb_strlen($content);

        if ($content_len <= $content_len) {

            return $content_len;
        }

        return mb_substr($content, 0, $length);
    }

    /**
     * @desc   转码 - CP936 转 UTF-8
     * @param  $string
     * @return false|string
     */
    public static function Cp936IconUtf8($string)
    {
        $string_encode = mb_detect_encoding($string, array("ASCII", "UTF-8", "GB2312", "GBK", "BIG5"));

        if (strtolower($string_encode) == "cp936") {
            $string = iconv('utf-8', 'latin1//IGNORE', $string);
        }

        return $string;
    }
}