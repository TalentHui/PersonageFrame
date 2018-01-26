<?php

use Engine\Base\BaseController;
use Engine\PHPLibrary\SplIterator;

/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - Spl.php
 * Author:    WuHui
 * Date:      2018-01-24 20:55
 * Desc:      Spl 标准库
 *******************************************************************************/

class Spl extends BaseController
{
    public function run()
    {

        $iterator = new SplIterator();
        foreach ($iterator as $key => $value) {
            echo iconv('utf-8', 'gbk', "输出索引为{$key}的元素" . ":$value" . PHP_EOL);
        }

        foreach ($iterator as $key => $value) {
            echo iconv('utf-8', 'gbk', "输出索引为{$key}的元素" . ":$value" . PHP_EOL);
        }
    }
}