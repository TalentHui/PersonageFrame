<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - TestController.php
 * Author:    WuHui
 * Date:      2017-08-28 10:43
 * Desc:      测试控制器
 *******************************************************************************/

use Engine\Base\BaseController;

class TestController extends BaseController
{
    public function connectRedis()
    {
        $redis = \Handler\RedisHandler::LocationRedisHandler();

        $redis->getRedisObject()->set('string:test:key', 'test');
        $redis->getRedisObject()->sAdd('set:test:key', 1, 2);
        $redis->getRedisObject()->lPush('list:test:key', '735833564');
        $redis->getRedisObject()->zAdd('zset:test:key', time(), '735833564');
        $redis->getRedisObject()->hset('hash:test:key', 'field1', 'values');

        $keys_list = $redis->getRedisObject()->keys('*');

        var_dump($keys_list);

        foreach ($keys_list as $key_name) {
            $del_rel = $redis->getRedisObject()->del($key_name);
            var_dump(array(
                'key_name' => $key_name,
                'del_result' => $del_rel
            ));
        }
    }

    public function getMethodName()
    {
        var_dump(__METHOD__);
    }
}