<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - SocketServer.php
 * Author:    WuHui
 * Date:      2018-01-26 19:33
 * Desc:      Socket 服务端类
 ********************************************************************************
 * @socket通信整个过程
 *            @socket_create
 *            @socket_bind
 *            @socket_listen
 *            @socket_accept
 *            @socket_read
 *            @socket_write
 *            @socket_close
 *******************************************************************************/

namespace Engine\PHPLibrary;


use Exception;

class SocketServer
{
    /** @var int  指定哪个协议用在当前套接字上 */
    protected static $socket_domain = AF_INET;
    /** @var int  选择套接字使用的类型 */
    protected static $socket_type = SOCK_STREAM;
    /** @var int  置指定 domain 套接字下的具体协议 */
    protected static $socket_protocol = SOL_TCP;
    /** @var resource socket套接字资源列表 */
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
     * @param  int $backlog
     * @throws Exception
     */
    public static function Instance($ip = '127.0.01', $port = '10001', $backlog = 3)
    {
        try {
            self::$socket = socket_create(self::$socket_domain, self::$socket_type, self::$socket_protocol);

            if (false === self::$socket) {
                throw new Exception('socket_create() failed reason: ' . socket_strerror(socket_last_error(self::$socket)), socket_last_error(self::$socket));
            }

            if (false === socket_bind(self::$socket, $ip, $port)) {
                throw new Exception('socket_connect() failed reason: ' . socket_strerror(socket_last_error(self::$socket)), socket_last_error(self::$socket));
            }

            if (in_array(self::$socket_type, array(SOCK_STREAM, SOCK_SEQPACKET))) {

                if (false === socket_listen(self::$socket, $backlog)) {
                    throw new Exception('socket_listen() failed reason: ' . socket_strerror(socket_last_error(self::$socket)), socket_last_error(self::$socket));
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @desc   accept - 获取最新被创建的 socket 资源
     * @return resource
     * @throws Exception
     */
    public static function SocketAccept()
    {
        $first_resource_socket = socket_accept(self::$socket);

        if (false === $first_resource_socket) {
            throw new Exception('socket_accept() failed reason: ' . socket_strerror(socket_last_error(self::$socket)), socket_last_error(self::$socket));
        }

        return $first_resource_socket;
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
            throw new Exception('socket_write() failed reason: ' . socket_strerror(socket_last_error(self::$socket)), socket_last_error(self::$socket));
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
            throw new Exception('socket_write() failed reason: ' . socket_strerror(socket_last_error(self::$socket)), socket_last_error(self::$socket));
        }

        return $read_rel;
    }

    /**
     * @desc   检查Server Socket函数支持
     * @return bool
     */
    public static function CheckSocketFunctionSupport()
    {
        if (
            function_exists('socket_create') &&
            function_exists('socket_bind') &&
            function_exists('socket_listen') &&
            function_exists('socket_accept') &&
            function_exists('socket_read') &&
            function_exists('socket_write') &&
            function_exists('socket_close')
        ) {
            return true;
        } else {
            return false;
        }
    }
}