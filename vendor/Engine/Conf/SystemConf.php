<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - SystemConf.php
 * Author:    WuHui
 * Date:      2017-08-26 14:49
 * Desc:      功能描述
 *******************************************************************************/

namespace Engine\Conf;


class SystemConf
{
    public static function SystemDefaultConf()
    {
        return array(
            'time_zone' => 'Asia/Shanghai',                                                                             // 系统默认时区

            'default_view_path' => '../application/templates/',                                                         // 默认试图文件夹位置
            'default_page' => '/Error/404',                                                                             // 默认的错误页面

            'default_controller_path' => '../application/controller/',                                                  // 默认控制器文件夹位置
            'default_controller' => 'Index',                                                                            // 默认控制器
            'default_action' => 'run',                                                                                  // 默认方法

            'open_error_flush' => false,                                                                                // 开启错误输出 true 开启 false 关闭
        );
    }
}