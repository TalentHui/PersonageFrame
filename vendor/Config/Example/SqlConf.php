<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - SqlConf.php
 * Author:    WuHui
 * Date:      2017-08-30 17:17
 * Desc:      sql连接配置
 *            远程连接Mysql指定ip和端口：mysql -u root -p -h 10.154.0.43 -P 3341
 *******************************************************************************/

namespace Config\Example;


class SqlConf
{
    public static function localhostMysqlConf()
    {
        return array(
            'sql_type' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'db_name' => '',
            'user' => 'root',
            'pwd' => 'root',
            'charset' => 'UTF8',
        );
    }
}