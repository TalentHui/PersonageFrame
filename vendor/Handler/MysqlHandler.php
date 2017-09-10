<?php
/********************************************************************************
 * Copyright: FreeWorld - PhpStorm - PersonageFrame - MysqlPool.php
 * Author:    吴辉
 * Date:      2017-09-11 00:25
 * Desc:      mysql链接句柄
 *******************************************************************************/

namespace Handler;


use Config\Example\SqlConf;
use Engine\DB\MysqlPool;

class MysqlHandler
{
    public static function LocationMysqlHandler()
    {
        return MysqlPool::getMysqlObject(SqlConf::localhostMysqlConf());
    }
}