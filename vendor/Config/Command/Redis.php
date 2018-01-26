<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - Redis.php
 * Author:    WuHui
 * Date:      2018-01-24 22:22
 * Desc:      Redis 命令
 *******************************************************************************/

namespace Config\Command;


class Redis
{
    public static function AllowRedisCommand()
    {

    }

    public static function LimitRedisCommand()
    {

    }

    protected static function RedisCommand_Key()
    {
        return array(
            'DEL',
            'DUMP',
            'EXISTS',
            'EXPIRE',
            'EXPIREAT',
            'KEYS',
            'MIGRATE',
            'MOVE',
            'OBJECT',
            'PERSIST',
            'PEXPIRE',
            'PEXPIREAT',
            'PTTL',
            'RANDOMKEY',
            'RENAME',
            'RENAMENX',
            'RESTORE',
            'SORT',
            'TTL',
            'TYPE',
            'SCAN',
        );
    }

    protected static function RedisCommand_String()
    {
        return array(
            'APPEND',
            'BITCOUNT',
            'BITOP',
            'BITFIELD',
            'DECR',
            'DECRBY',
            'GET',
            'GETBIT',
            'GETRANGE',
            'GETSET',
            'INCR',
            'INCRBY',
            'INCRBYFLOAT',
            'MGET',
            'MSET',
            'MSETNX',
            'PSETEX',
            'SET',
            'SETBIT',
            'SETEX',
            'SETNX',
            'SETRANGE',
            'STRLEN',
        );
    }

    protected static function RedisCommand_Hash()
    {
        return array(
            'HDEL',
            'HEXISTS',
            'HGET',
            'HGETALL',
            'HINCRBY',
            'HINCRBYFLOAT',
            'HKEYS',
            'HLEN',
            'HMGET',
            'HMSET',
            'HSET',
            'HSETNX',
            'HVALS',
            'HSCAN',
            'HSTRLEN',
        );
    }

    protected static function RedisCommand_List()
    {
        return array(
            'BLPOP',
            'BRPOP',
            'BRPOPLPUSH',
            'LINDEX',
            'LINSERT',
            'LLEN',
            'LPOP',
            'LPUSH',
            'LPUSHX',
            'LRANGE',
            'LREM',
            'LSET',
            'LTRIM',
            'RPOP',
            'RPOPLPUSH',
            'RPUSH',
            'RPUSHX',
        );
    }

    protected static function RedisCommand_Set()
    {
        return array(
            'SADD',
            'SCARD',
            'SDIFF',
            'SDIFFSTORE',
            'SINTER',
            'SINTERSTORE',
            'SISMEMBER',
            'SMEMBERS',
            'SMOVE',
            'SPOP',
            'SRANDMEMBER',
            'SREM',
            'SUNION',
            'SUNIONSTORE',
            'SSCAN',
        );
    }

    protected static function RedisCommand_SortedSet()
    {
        return array(
            'ZADD',
            'ZCARD',
            'ZCOUNT',
            'ZINCRBY',
            'ZRANGE',
            'ZRANGEBYSCORE',
            'ZRANK',
            'ZREM',
            'ZREMRANGEBYRANK',
            'ZREMRANGEBYSCORE',
            'ZREVRANGE',
            'ZREVRANGEBYSCORE',
            'ZREVRANK',
            'ZSCORE',
            'ZUNIONSTORE',
            'ZINTERSTORE',
            'ZSCAN',
            'ZRANGEBYLEX',
            'ZLEXCOUNT',
            'ZREMRANGEBYLEX',
        );
    }

    protected static function RedisCommand_HyperLogLog()
    {
        return array(
            'PFADD',
            'PFCOUNT',
            'PFMERGE',
        );
    }

    protected static function RedisCommand_GEO()
    {
        return array(
            'GEOADD',
            'GEOPOS',
            'GEODIST',
            'GEORADIUS',
            'GEORADIUSBYMEMBER',
            'GEOHASH',
        );
    }

    protected static function RedisCommand_PubAndSub()
    {
        return array(
            'PSUBSCRIBE',
            'PUBLISH',
            'PUBSUB',
            'PUNSUBSCRIBE',
            'SUBSCRIBE',
            'UNSUBSCRIBE',
        );
    }

    protected static function RedisCommand_Transaction()
    {
        return array(
            'DISCARD',
            'EXEC',
            'MULTI',
            'UNWATCH',
            'WATCH',
        );
    }

    protected static function RedisCommand_Script()
    {
        return array(
            'EVAL',
            'EVALSHA',
            'SCRIPT EXISTS',
            'SCRIPT FLUSH',
            'SCRIPT KILL',
            'SCRIPT LOAD',
        );
    }

    protected static function RedisCommand_Connection()
    {
        return array(
            'AUTH',
            'ECHO',
            'PING',
            'QUIT',
            'SELECT',
        );
    }

    protected static function RedisCommand_Server()
    {
        return array(
            'BGREWRITEAOF',
            'BGSAVE',
            'CLIENT GETNAME',
            'CLIENT KILL',
            'CLIENT LIST',
            'CLIENT SETNAME',
            'CONFIG GET',
            'CONFIG RESETSTAT',
            'CONFIG REWRITE',
            'CONFIG SET',
            'DBSIZE',
            'DEBUG OBJECT',
            'DEBUG SEGFAULT',
            'FLUSHALL',
            'FLUSHDB',
            'INFO',
            'LASTSAVE',
            'MONITOR',
            'PSYNC',
            'SAVE',
            'SHUTDOWN',
            'SLAVEOF',
            'SLOWLOG',
            'SYNC',
            'TIME',
        );
    }
}