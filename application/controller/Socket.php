<?php

use Engine\Base\BaseController;
use Engine\PHPLibrary\SocketClient;
use Engine\PHPLibrary\SocketServer;

/***********************************************************************************************************************
 * COPYRIGHT: PhpStorm - PersonageFrame - Socket.php
 * TIMESTAMP: 2018/07/10 19:33
 * AUTHOR:    WuHui
 * DESC:      functional description...
 * ```
 *
 * ```
 ***********************************************************************************************************************/

class Socket extends BaseController
{
    public function server()
    {
        set_time_limit(0);

        try {
            SocketServer::Instance();

            do {
                if (SocketServer::SocketAccept() < 0) {
                    echo "socket_accept() failed: reason: ";
                    break;
                }

            } while (true);

        } catch (Exception $e) {
            \Engine\Common\HelpFunction::EchoSelf($e->getMessage());
        }
    }

    public function client()
    {
        set_time_limit(0);

        try {
            SocketClient::Instance();

        } catch (Exception $e) {
            \Engine\Common\HelpFunction::EchoSelf($e->getMessage());
        }
    }
}