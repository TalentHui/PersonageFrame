<?php

use Engine\Base\BaseController;

/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - TestController.php
 * Author:    WuHui
 * Date:      2017-08-28 10:43
 * Desc:      测试控制器
 *******************************************************************************/

class TestController extends BaseController
{
    public function redisExample()
    {
        $redis = \Handler\RedisHandler::LocationRedisHandler();
        var_dump($redis->getRedisObject()->keys('*'));
    }

    public function mysqlExample()
    {
        $mysql = \Handler\MysqlHandler::LocationMysqlHandler();
        $mysql->pdoQuery(\Engine\Behavior\SqlBaseCommand::DatabasesShowAll());
        $rel = $mysql->getPdoSqlRelFetchAll();
        var_dump($rel);
    }

    public function createCreate()
    {
        echo iconv('utf-8', 'gbk', \Engine\Behavior\CreateMySQL::MySQLDataType());
    }
}