<?php
/***********************************************************************************************************************
 * Platform:  PhpStorm - PersonageFrame - autoload.php
 * User:      吴辉
 * Date:      2017-08-24 22:40
 * Desc:      自动加载类
 * PSR0:      1):命名空间必须与绝对路径一致。
 *            2):类名的首字母必须要大写。
 *            3):除了入口文件以外，其他的.php文件中必须只有一个类，不能有可执行的代码。
 *
 *            PSR-4中，在类名中使用下划线没有任何特殊含义。而PSR-0则规定类名中的下划线_会被转化成目录分隔符。
 ***********************************************************************************************************************/

namespace vendor;


class autoload
{
    /** @var string 工程根目录 */
    protected static $project_root_directory = '..';

    /** @var array 定义类文件的存放目录 */
    protected static $class_root_directory = array();

    /** @var array 注册命名空间列表 */
    protected static $register_namespace_list = array();

    /**
     * @desc  初始化类文件的存放目录
     * @param string $project_root_directory 工程根目录
     * @param string $controller_directory   控制器目录
     * @param string $vendor_directory       工程处理文件目录
     */
    public static function instance($project_root_directory = '..', $controller_directory = '/application/controller/', $vendor_directory = '/vendor/')
    {
        self::$project_root_directory = rtrim($project_root_directory, '\\/');
        $controller_directory = '/' . str_replace(trim($controller_directory, '\\'), '\\', '/') . '/';
        $vendor_directory = '/' . str_replace(trim($vendor_directory, '\\'), '\\', '/') . '/';

        self::$class_root_directory = array(
            'controller' => self::$project_root_directory . $controller_directory,
            'vendor' => self::$project_root_directory . $vendor_directory
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
     * @desc   注册命名空间
     * @param  string $namespace
     * @param  string $extension_directory
     * @return bool
     */
    public static function registerNamespace($namespace = '', $extension_directory = '')
    {
        if (empty($namespace) || !empty(self::$register_namespace_list[$namespace])) {
            return false;
        }

        $namespace = str_replace(trim($namespace, '\\/'), '/', '\\');
        $extension_directory = '/' . str_replace(trim($extension_directory, '\\'), '\\', '/') . '/';

        self::$register_namespace_list[$namespace] = $extension_directory;
        return true;
    }

    /**
     * @desc   删除命名空间
     * @param  string $namespace
     * @return bool
     */
    public static function unsetNamespace($namespace = '')
    {
        if (empty($namespace) || empty(self::$register_namespace_list[$namespace])) {
            return false;
        }

        unset(self::$register_namespace_list[$namespace]);
        return true;
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
    public static function findClassFile($use_class_by_namespace = '')
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

        /** step-4: 将路径中 \ 替换成 / */
        $need_load_class_path = strval(str_replace('\\', '/', $need_load_class_path));

        if (!file_exists($need_load_class_path) && !empty(self::$register_namespace_list[$use_class_directory])) {
            $need_load_class_path = self::$project_root_directory . DIRECTORY_SEPARATOR . trim(self::$register_namespace_list[$use_class_directory], '/') . DIRECTORY_SEPARATOR . $use_class_basename . '.php';
        }

        return $need_load_class_path;
    }
}