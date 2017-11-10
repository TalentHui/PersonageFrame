<?php

use Engine\Base\BaseController;

/********************************************************************************
 * Copyright: FreeWorld - PhpStorm - PersonageFrame - StringStudy.php
 * Author:    吴辉
 * Date:      2017-10-23 23:16
 * Desc:      File Description
 *******************************************************************************/

class StringStudy extends BaseController
{
    public function index()
    {
        /** step-1：双引号字符串转义序列 */
        echo "\n";                   // 换行符 (ASCII码10)
        echo "\r";                   // 回车符 (ASCII码13)
        echo "\t";                   // 制表符 (ASCII码9)
        echo "\\";                   // 反斜杠
        echo "\$";                   // 美元符号
        echo "\"";                   // 双引号
        echo "\0" . "~" . "\777";    // 八进制数值
        echo "\x0" . "~" . "\xFF";   // 十六进制数值

        /** step-1.1：取得字符串中的个别字符 */
        $neighbor = 'WuHui';
        echo PHP_EOL . $neighbor[0] . PHP_EOL;

        /**
         * step-1.2: 访问子字符串（你想知道一个字符串中是否包含着一个特殊的子字符串）
         * 返回值:
         *      1) 没有找到相应的子字符串，strpos() 就返回 false
         *      2) 如果子字符串位于这个字符串的开始处, strpos() 返回 0，因为位置0表示这个字符串的开始。为了区分返回的0和false值，
         *         必须使用等同于操作符 (===) 或者不等同操作符 (!==)
         */
        if (strpos('frame17pai.com', '@') === false) {
            echo PHP_EOL . 'There was no @ in the e-mail address' . PHP_EOL;
        }

        /**
         * step-1.3：提取子字符串 (想从字符串的一个特定的位置开始，提取字符串的一部分。例如：你想取得表单中用户名的前八个字符)
         * substr($string, $start, $length)
         *
         * $start的值大于字符串的长度，substr()返回 false
         * $start 默认值为0
         *
         * 返回值：截取的子字符串
         */

        /** 反转字符串 */
        strrev('this is not a palindrome.');

        /** 反转数组 */
        array_reverse(array(
            1,
            2
        ));

        /** 将字符串分解成数组 */
        $string = 'once upon a time there was a turtle';
        $string_arr = explode(' ', $string);

        /** 将数组拼装成字符串 */
        implode(' ', $string_arr);

        /** 控制大小写 */
        ucfirst('how do you do today');          // 将字符串首个单词字母转为大写
        ucwords('how do you do today');          // 将字符串每个单词首字母转为大写
        strtoupper('how do you do today');       // 将字符串每个字母转为大写
        strtolower('HOW DO YOU DO TODAY');       // 将字符串每个字母转为小写

        /**
         * pack() 把一个数据的数组转换成了一个固定宽度的记录
         * print pack('A25A15A4', ’第一列‘， ’第二列‘， ’第三列‘);
         */

        str_split('你好', 3);                     // 把一个字符串分割成3个字节一组的片段， 返回数组

        wordwrap('wo shi yi ge bing', 10);       // 文本在特定长度处自动换行(按照每行10个字符来自动将文本换行)
    }

    /**
     * @desc 读取post请求的主体
     */
    public function readPostRequestMainContent()
    {
        /** 方案一: （建议） */
        file_get_contents('php://input');

        /** 方案二: */
        $fp = fopen('php://input', 'r');
        fread($fp, 1024);
        fclose($fp);
    }

    /**
     * @desc 使用HTTP的基本或摘要认真
     */
    public function httpBaseAuth()
    {
        $user = $_SERVER['PHP_AUTH_USER'];    // 用户名
        $pass = $_SERVER['PHP_AUTH_PW'];      // 密码

        /** 实施基本认证 */
        if (empty($user) || empty($pass)) {
            header('WWW-Authenticate: Basic realm="My Website"');
            header('HTTP/1.0 401 Unauthorized');
            echo "You need to enter a valid user ans password";
            exit();
        }
    }

    /**
     * @desc 把输出冲刷 (Flushing) 到浏览器
     */
    public function flushingToBrowser()
    {
        /** 强制让IE立即显示内容 */
        echo str_repeat(' ', 300);             // 把字符串重复指定的次数
        echo 'Finding identical snowflakes';
        flush();
    }
}