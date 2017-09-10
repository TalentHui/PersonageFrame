<?php
/********************************************************************************
 * Copyright: FreeWorld - PhpStorm - PersonageFrame - MysqlPool.php
 * Author:    吴辉
 * Date:      2017-09-11 00:26
 * Desc:      mysql 链接池
 *******************************************************************************/

namespace Engine\DB;


class MysqlPool
{
    /** @var MysqlPDO[] mysql连接池容器 */
    protected static $mysql_pool = array();

    public static function getMysqlObject(array $mysql_conf = array())
    {
        $connect_md5_sign = md5(json_encode($mysql_conf));

        if (empty(self::$mysql_pool[$connect_md5_sign])) {
            $redis_object = new MysqlPDO($mysql_conf);
            self::$mysql_pool[$connect_md5_sign] = $redis_object;
        }

        return self::$mysql_pool[$connect_md5_sign];
    }

    /**
     * @desc   获取mysql链接池
     * @return MysqlPDO[]
     */
    public static function getMysqlPool()
    {
        return self::$mysql_pool;
    }

    /**
     * @desc  从链接池中销毁特定mysql对象
     * @param array $mysql_conf
     * @return bool
     */
    public static function destroySinglePool(array $mysql_conf = array())
    {
        $connect_md5_sign = md5(json_encode($mysql_conf));

        if (!empty(self::$mysql_pool[$connect_md5_sign])) {
            unset(self::$mysql_pool[$connect_md5_sign]);
        }

        return true;
    }

    /**
     * @desc   销毁整个链接池
     * @return bool
     */
    public static function destroyAllPool()
    {
        foreach (self::$mysql_pool as $connect_md5_sign => $redis_object) {
            unset(self::$mysql_pool[$connect_md5_sign]);
        }

        return true;
    }
}