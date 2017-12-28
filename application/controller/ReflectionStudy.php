<?php
/********************************************************************************
 * Copyright: FreeWorld - PhpStorm - PersonageFrame - ReflectionStudy.php
 * Author:    吴辉
 * Date:      2017-10-22 17:48
 * Desc:      反射类学习
 *******************************************************************************/

use Engine\Base\BaseController;

class ReflectionStudy extends BaseController
{
    /**
     * @desc 获取一个类的有关信息
     */
    public function index()
    {
        /**
         * @desc   建立类的反射类
         * @notice 如果类是通过命名空间加载，切要加载的文件不在当前命名空间下，加载的类名前需要 + 命名空间
         */
        $reflection_class = new ReflectionClass('Engine\DB\MysqlPDO');

        /**
         * @desc   实例化一个反射类，相当于 new MysqlPDO();
         */
        $instance_reflection_class = $reflection_class->newInstanceArgs();

        /**
         * @desc   获取类的属性
         * @notice ReflectionClass会获取到所有的属性
         */
        $class_properties = $reflection_class->getProperties();

        foreach ($class_properties as $properties_item) {

            $properties_type = '共有';

            if ($properties_item->isProtected()) {
                $properties_type = '私有';
            }

            if ($properties_item->isProtected()) {
                $properties_type = '保护';
            }

            if ($properties_item->isStatic()) {
                $properties_type = '保护';
            }

            echo iconv('UTF-8', 'GB2312', '属性名: ') . str_pad($properties_item->getName(), 25, ' ') .
                 iconv('UTF-8', 'GB2312', "类型: {$properties_type}   ") .
                 iconv('UTF-8', 'GB2312', "注释: " . $properties_item->getDocComment()) .
                 PHP_EOL;
        }

        $reflection_class->getProperties(ReflectionProperty::IS_PUBLIC);     // 获取共有属性
        $reflection_class->getProperties(ReflectionProperty::IS_PRIVATE);    // 获取私有属性
        $reflection_class->getProperties(ReflectionProperty::IS_PROTECTED);  // 获取受保护的属性
        $reflection_class->getProperties(ReflectionProperty::IS_STATIC);     // 获取静态属性

        /**
         * @desc   获取类的方法
         */
        $class_method_list = $reflection_class->getMethods();

        foreach ($class_method_list as $method_item) {
            $method_type = '共有';

            if ($method_item->isProtected()) {
                $method_type = '私有';
            }

            if ($method_item->isProtected()) {
                $method_type = '保护';
            }

            if ($method_item->isStatic()) {
                $method_type = '保护';
            }

            /**
             * @desc   检测是否存在某个方法
             */
            if ($reflection_class->hasMethod($method_item->getName())) {
                echo iconv('UTF-8', 'GB2312', '方法名: ') . str_pad($method_item->getName(), 30, ' ') .
                     iconv('UTF-8', 'GB2312', "类型: {$method_type}   ") . PHP_EOL .
                     '   ' . iconv('UTF-8', 'GB2312', $method_item->getDocComment()) .
                     PHP_EOL;
            }
        }

        /**
         * @desc   执行类的方法
         */
        var_dump($instance_reflection_class->getPdoConnectStatus());                            // 方法一

        $get_pdo_connect_status_method = $reflection_class->getMethod('getPdoConnectStatus');   // 方法二
        var_dump($get_pdo_connect_status_method->invoke($instance_reflection_class));

        $get_prepare_pdo_sql_method = $reflection_class->getMethod('preparePdoSql');         // 方法二 - 带参数
        var_dump($get_prepare_pdo_sql_method->invoke($instance_reflection_class), array('show tables;'));
    }

    public function getMethodInfoByReflection()
    {
        $method = new ReflectionMethod('Engine\DB\MysqlPDO', 'preparePdoSql');

        var_dump(array(
            $method->isStatic(),
            $method->isPublic(),
            $method->isProtected(),
            $method->isPrivate(),
            $method->getNumberOfParameters(),       // 参数个数
            $method->getParameters(),               // 参数对象数组
        ));
    }

    /**
     * @desc   获取格式化时需要的空格数量
     * @param  $string
     * @return float|int
     */
    protected function getBlankNum($string)
    {
        $character_length = strlen($string);
        $chinese_character_length = mb_strlen($string);

        $chinese_character_num = ($chinese_character_length - $character_length) / 2;

        if (!$chinese_character_num) {
            $chinese_character_num = 0;
        }

        $character_num = $character_length - $chinese_character_num;

        return ($character_num + $chinese_character_num * 3 + 1);
    }
}