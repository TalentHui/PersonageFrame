<?php
/********************************************************************************
 * Platform:  PhpStorm - PersonageFrame - index.php
 * User:      吴辉
 * Date:      2017-08-25 21:09
 * Desc:      入口文件
 *******************************************************************************/

define('ROOT_DIR', realpath(dirname(__FILE__) . '/../') . DIRECTORY_SEPARATOR);

/** 载入自动加载函数 */
include "../vendor/autoload.php";
\vendor\autoload::instance(ROOT_DIR);
\vendor\autoload::register();

/** 载入引擎文件 */
$engine = new \Engine\Libary\Engine(ROOT_DIR);
$engine->run();