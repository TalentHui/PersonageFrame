<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - TestController.php
 * Author:    WuHui
 * Date:      2017-08-28 10:43
 * Desc:      测试控制器
 *******************************************************************************/

use Engine\Base\BaseController;
use Engine\Behavior\CreateMySQL;
use Engine\Behavior\SqlBaseCommand;
use Handler\MysqlHandler;
use Handler\RedisHandler;

class TestController extends BaseController
{
    public function redisExample()
    {
        $redis = RedisHandler::LocationRedisHandler();
        var_dump($redis->getRedisObject()->keys('*'));
    }

    public function mysqlExample()
    {
        $mysql = MysqlHandler::LocationMysqlHandler();
        $mysql->pdoQuery(SqlBaseCommand::DatabasesShowAll());
        $rel = $mysql->getPdoSqlRelFetchAll();
        var_dump($rel);
    }

    public function createCreate()
    {
        echo iconv('utf-8', 'gbk', CreateMySQL::MySQLDataType());
    }
}