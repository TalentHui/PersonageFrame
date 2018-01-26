<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - SocketClient.php
 * Author:    WuHui
 * Date:      2018-01-26 19:34
 * Desc:      Socket 客户端类
 ********************************************************************************
 * socket连接整个过程 - 客户端先写再读
 ********************************************************************************
 *            @socket_create
 *            @socket_connect
 *            @socket_write
 *            @socket_read
 *            @socket_close
 *******************************************************************************/

namespace Engine\PHPLibrary;


use Exception;

class SocketClient
{
    /** @var int  指定哪个协议用在当前套接字上 */
    protected static $socket_domain = AF_INET;
    /** @var int  选择套接字使用的类型 */
    protected static $socket_type = SOCK_STREAM;
    /** @var int  置指定 domain 套接字下的具体协议 */
    protected static $socket_protocol = SOL_TCP;
    /** @var object socket套接字资源 */
    protected static $socket;

    /**
     * @desc   set - 套接字使用的协议
     * @param  $domain
     * @return bool
     */
    public static function SetSocketDomain($domain)
    {
        if (!is_numeric($domain) || in_array($domain, array(AF_INET, AF_INET6, AF_UNIX))) {
            return false;
        }

        self::$socket_domain = $domain;

        return true;
    }

    /**
     * @desc   set - 套接字使用的类型
     * @param  $type
     * @return bool
     */
    public static function SetSocketType($type)
    {
        if (!is_numeric($type) || in_array($type, array(SOCK_STREAM, SOCK_DGRAM, SOCK_SEQPACKET, SOCK_RAW, SOCK_RDM))) {
            return false;
        }

        self::$socket_type = $type;

        return true;
    }

    /**
     * @desc   set - 套接字使用的具体协议
     * @param  $protocol
     * @return bool
     */
    public static function SetSocketProtocol($protocol)
    {
        if (!is_numeric($protocol) || in_array($protocol, array(SOL_TCP, SOL_UDP))) {
            return false;
        }

        self::$socket_protocol = $protocol;

        return true;
    }

    /**
     * @desc   初始化方法
     * @param  string $ip
     * @param  string $port
     * @throws Exception
     */
    public static function instance($ip = '127.0.01', $port = '10001')
    {
        try {
            self::$socket = socket_create(self::$socket_domain, self::$socket_type, self::$socket_protocol);

            if (self::$socket === false) {
                throw new Exception('socket_create() failed reason: ' . socket_strerror(socket_last_error(self::$socket)), socket_last_error(self::$socket));
            }

            $connect_rel = socket_connect(self::$socket, $ip, $port);

            if ($connect_rel === false) {
                throw new Exception('socket_connect() failed reason: ' . socket_strerror(socket_last_error(self::$socket)), socket_last_error(self::$socket));
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @desc   write - 写入数据到建立的socket中
     * @param  $socket_resource
     * @param  $msg
     * @param  $msg_length
     * @return int
     * @throws Exception
     */
    public static function SocketWrite($socket_resource, $msg, $msg_length)
    {
        $write_rel = socket_write($socket_resource, $msg, $msg_length);

        if (false === $write_rel) {
            throw new Exception('socket_write() failed reason: ' . socket_strerror(socket_last_error($socket_resource)), socket_last_error($socket_resource));
        }

        return $write_rel;
    }

    /**
     * @desc   read - 读取数据从建立的socket中
     * @param  $socket_resource
     * @param  int $data_length
     * @return string
     * @throws Exception
     */
    public static function SocketRead($socket_resource, $data_length = 10000)
    {
        $read_rel  = socket_read($socket_resource, $data_length);

        if (false === $read_rel) {
            throw new Exception('socket_write() failed reason: ' . socket_strerror(socket_last_error($socket_resource)), socket_last_error($socket_resource));
        }

        return $read_rel;
    }

    /**
     * @desc   close - 关闭socket资源
     * @param  $socket_resource
     * @throws Exception
     */
    public static function SocketClose($socket_resource)
    {
        $close_rel  = socket_close($socket_resource);

        if (false === $close_rel) {
            throw new Exception('socket_close() failed reason: ' . socket_strerror(socket_last_error($socket_resource)), socket_last_error($socket_resource));
        }

        return $close_rel;
    }

    /**
     * @desc   检查Client Socket函数支持
     * @return bool
     */
    public static function CheckSocketFunctionSupport()
    {
        if (
            function_exists('socket_create') &&
            function_exists('socket_connect') &&
            function_exists('socket_write') &&
            function_exists('socket_read') &&
            function_exists('socket_close')
        ) {
            return true;
        } else {
            return false;
        }
    }
}