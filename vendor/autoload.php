<?php
/********************************************************************************
 * Platform:  PhpStorm - PersonageFrame - autoload.php
 * User:      吴辉
 * Date:      2017-08-24 22:40
 * Desc:      自动加载类
 *******************************************************************************/

namespace vendor;


class autoload
{
    /** @var array 定义类文件的存放目录 */
    protected static $class_root_directory = array();

    /**
     * @desc  初始化类文件的存放目录
     * @param string $project_root_directory 工程根目录
     */
    public static function instance($project_root_directory = '..')
    {
        $project_root_directory = rtrim($project_root_directory, '\\/');

        self::$class_root_directory = array(
            'controller' => $project_root_directory . '/application/controller/',
            'vendor' => $project_root_directory . '/vendor/'
        );
    }

    /**
     * @desc  register autoload function
     * @param bool $prepend
     */
    public static function register($prepend = false)
    {
        spl_autoload_register("vendor\autoload::UserAutoloadFunction", true, $prepend);
    }

    /**
     * @param string $use_class_by_namespace
     * @des   约定目录结构作为文件的命名空间
     */
    public static function UserAutoloadFunction($use_class_by_namespace = '')
    {
        $find_class_file_path = self::findClassFile($use_class_by_namespace);
        include "$find_class_file_path";
    }

    /**
     * @desc  package class file path
     * @param string $use_class_by_namespace
     * @return string
     */
    public static function findClassFile($use_class_by_namespace ='')
    {
        /** step-1: 命名空间是通过 \ 做分割符的, 将 \ 替换成 / 表示路径 */
        $use_class_by_namespace = str_replace('\\', '/', $use_class_by_namespace);

        /** step-2: 拼接文件路径 */
        $file_path_info = pathinfo($use_class_by_namespace);
        $use_class_directory = $file_path_info['dirname'];
        $use_class_basename = $file_path_info['basename'];

        if ($use_class_directory === '.') {
            $need_load_class_path = self::$class_root_directory['controller'] . $use_class_basename . '.php';
        } else {
            $need_load_class_path = self::$class_root_directory['vendor'] . trim($use_class_directory, '/') . DIRECTORY_SEPARATOR . $use_class_basename . '.php';
        }

        /** step-3: 将路径中 \ 替换成 / */
        $need_load_class_path = strval(str_replace('\\', '/', $need_load_class_path));

        return $need_load_class_path;
    }
}