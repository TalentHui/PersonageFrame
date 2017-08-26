<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - Dispatcher.php
 * Author:    WuHui
 * Date:      2017-08-26 14:00
 * Desc:      模板加载
 *******************************************************************************/

namespace Engine\Libary;


use Engine\Conf\SystemConf;

final class Dispatcher
{
    /** @var string 默认的模板文件路径 默认是相对路径 */
    protected $templates_directory = '';

    /**
     * Dispatcher constructor.
     * @param string $roo_directory 默认为空获取相对路径，传入项目跟目录则获取绝对路径
     */
    public function __construct($roo_directory = '')
    {
        if (file_exists($roo_directory)) {
            $this->templates_directory = rtrim($roo_directory, '\\/') . DIRECTORY_SEPARATOR . trim($this->templates_directory, '.\\/') . DIRECTORY_SEPARATOR;
        }

        $this->templates_directory = SystemConf::SystemDefaultConf()['default_view_path'];
    }

    /**
     * @desc  载入模板
     * @param string $template_file 模板文件存试图文件夹中的路径
     * @param array $params 模板参数
     */
    public function loadTemplateView($template_file = '', array $params = array())
    {
        $view_file_path = $this->getDispatcherPath($template_file);

        if (file_exists($view_file_path)) {

            foreach ($params as $key => $val) {

                $$key = $val;
            }

            include "{$view_file_path}";
        }

        exit();
    }

    /**
     * @desc  拼接模板路径
     * @param string $templates_path
     * @return string
     */
    protected function getDispatcherPath($templates_path = '')
    {
        if (empty($templates_path)) {
            $templates_path = SystemConf::SystemDefaultConf()['default_page'];
        }

        return str_replace('\\', '/', $this->templates_directory . trim($templates_path, '.\\/')) . '.html';
    }
}