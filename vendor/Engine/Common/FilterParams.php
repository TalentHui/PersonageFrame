<?php
/***********************************************************************************************************************
 * COPYRIGHT: PhpStorm - ServerTest - FilterParams.php
 * TIMESTAMP: 2018/07/09 20:32
 * AUTHOR:    WuHui
 * DESC:      过滤参数...
 * ```
 *
 * ```
 ***********************************************************************************************************************/
namespace Engine\Common;


class FilterParams
{
    static $params_list = array();

    /**
     * @desc   参数列表 - 初始化
     */
    public static function InitParamsList()
    {
        self::$params_list = array();
    }

    /**
     * @desc   参数列表 - 赋值
     * @param  array $params
     */
    public static function EvaluationParamsList(array $params = array())
    {
        if (is_array($params)) {
            self::$params_list = $params;
        }
    }

    /**
     * @desc   参数列表元素 - 获取
     * @param  $field_name
     * @param  string $field_type
     * @return mixed
     */
    public static function GetParamsItem($field_name, $field_type = '')
    {
        $field_value = empty(self::$params_list[$field_name]) ? '' : self::$params_list[$field_name];

        return $field_type ? self::ProcessParamsItem($field_value, $field_type) : $field_value;
    }

    /**
     * @desc   参数列表元素 - 处理
     * @param  $field_value
     * @param  string $field_type
     * @return mixed
     */
    public static function ProcessParamsItem($field_value, $field_type)
    {
        switch ($field_type) {
            case 'int':
                $field_value = intval($field_value);
                break;
            case 'string':
                $field_value = strval($field_value);
                break;
            case 'bool':
                $field_value = boolval($field_value);
                break;
            case 'array':
                $field_value = (array) $field_value;
                break;
        }

        return $field_value;
    }
}