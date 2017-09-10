<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - MongoConf.php
 * Author:    WuHui
 * Date:      2017-08-30 16:06
 * Desc:      mongo 配置
 *******************************************************************************/

namespace Example\Config;


class MongoConf
{
    /**
     * @desc   本机配置
     * @return array
     */
    public static function localhostMongoConf()
    {
        return array(
            'host' => '127.0.0.1',
            'port' => '27017'
        );
    }
}