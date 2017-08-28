<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - Manager.php
 * Author:    WuHui
 * Date:      2017-08-28 15:05
 * Desc:      功能描述
 *******************************************************************************/

namespace Engine\Libary;

use ReflectionClass;

class Manager
{

    /**
     * @var array
     */
    static $pool;

    public static function pool()
    {
        $args = func_get_args();

        if (empty($args[0])) {
            throw new \Exception('$class_name empty');
        }

        $class_name = $args[0];
        $handler = self::getPool($class_name);

        if (false !== $handler) {
            return $handler;
        }

        $handler = call_user_func_array(['\Engine\Libary\Manager', 'getDynamic'], $args);

        self::addPool($class_name, $handler);
        return $handler;
    }

    /**
     * @return object
     */
    public static function getDynamic()
    {
        $args = func_get_args();
        $class_name = array_shift($args);
        $gen = new ReflectionClass($class_name);
        return $gen->newInstanceArgs($args);
    }

    public static function getStatic($class_name)
    {
        return $class_name;
    }

    private static function addPool($handler_name, $handler)
    {
        self::$pool[$handler_name] = $handler;
    }

    private static function getPool($handler_name)
    {
        if (empty(self::$pool[$handler_name])) {
            return false;
        }

        return self::$pool[$handler_name];
    }

    public static function destroyObj($handler_name)
    {
        if ($handler_name && isset(self::$pool[$handler_name])) {
            unset(self::$pool[$handler_name]);
        }
    }

    public static function destroyAllObj()
    {
        self::$pool = [];
    }

}