<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - FileFunction.php
 * Author:    WuHui
 * Date:      2017-08-25 23:51
 * Desc:      文件
 *******************************************************************************/

class FileFunction
{
    /**
     * @desc   获取系统默认文件存放目录
     * @param  string $project_root_directory
     * @return string
     */
    public static function getSystemFileDirectory($project_root_directory = '')
    {
        $project_root_directory = rtrim(str_replace('\\', '/', $project_root_directory), '/') . '/';
        $file_directory = $project_root_directory . DIRECTORY_SEPARATOR . 'file' . DIRECTORY_SEPARATOR;
        return $file_directory;
    }

    /**
     * @desc   读取文件 - 按照行为单位的任何类型文件
     * @param  string $file_default_directory
     * @param  string $file_name
     * @param  string $file_type
     * @return array
     */
    public static function readFileDataLevelLine($file_default_directory = '', $file_name = '', $file_type = 'csv')
    {
        $file_default_directory = rtrim(str_replace('\\', '/', $file_default_directory), '/') . '/';
        $file_path = $file_default_directory . $file_name . '.' . $file_type;
        $save_data_container = array();

        if (!file_exists($file_path)) {
            return $save_data_container;
        }

        $fp_source = fopen($file_path, 'r');

        while (!feof($fp_source)) {

            $line_contents = fgets($fp_source);

            if (!empty($line_contents)) {
                $save_data_container[] = $line_contents;
            }
        }

        fclose($fp_source);
        return $save_data_container;
    }

    /**
     * @desc   读取文件 - CSV
     * @param  string   $file_default_directory  文件目录
     * @param  string   $file_name               要读区的文件名
     * @param  array    $need_column_list        需要获取的列
     * @param  string   $filter_string           需要过滤掉的元素
     * @param  bool|int $filter_line             文件需要舍弃的前几行
     * @param  string   $char
     * @return array
     */
    public static function readFileDataFromCsvFle($file_default_directory = '', $file_name = '', $need_column_list = array(), $filter_string = '', $filter_line = false, $char = ',')
    {
        $save_data_container = array();
        $init_line = 1;

        $file_default_directory = rtrim(str_replace('\\', '/', $file_default_directory), '/') . '/';
        $file_path = $file_default_directory . $file_name . '.' . 'csv';

        if (!file_exists($file_path)) {
            return $save_data_container;
        }

        $fp_source = fopen($file_path, 'r');

        while ($line_record = fgetcsv($fp_source, 0, $char)) {

            if (empty($line_record['0'])) {
                break;
            }

            if ($filter_line) {

                if ($init_line <= $filter_line) {
                    continue;
                }
            }

            $tmp_array = array();

            foreach ($need_column_list as $need_item) {

                if ($filter_string) {
                    $tmp_array[] = empty($line_record[$need_item]) ? '' : trim($line_record[$need_item], $filter_string);
                } else {
                    $tmp_array[] = $line_record[$need_item];
                }
            }

            array_push($package_data, $tmp_array);
            $init_line++;
        }

        return $save_data_container;
    }

    /**
     * @desc   读取文件 - JSON
     * @param  string $file_default_directory
     * @param  string $file_name
     * @return array|mixed
     */
    public static function readFileDataFromJsonFle($file_default_directory = '', $file_name = '')
    {
        $file_default_directory = rtrim(str_replace('\\', '/', $file_default_directory), '/') . '/';
        $json_file_path = $file_default_directory . $file_name . '.' . 'json';
        $json_data = array();

        if (!file_exists($json_file_path)) {
            return $json_data;
        }

        try {
            $json_data = json_decode(file_get_contents($json_file_path), true);
        } catch (\Exception $e) {
            $json_data = array();
        }

        return $json_data;
    }

    /**
     * @desc  保存数据 - CSV
     * @param string $file_default_directory
     * @param string $file_name
     * @param array  $title_array
     * @param array  $content_array
     */
    public static function saveDataToCsvFile($file_default_directory = '', $file_name = '', array $title_array = array(), array $content_array = array())
    {
        $file_default_directory = rtrim(str_replace('\\', '/', $file_default_directory), '/') . '/';
        $file_path = $file_default_directory . $file_name . '.' . 'csv';
        $file_name = $file_name . '_' . date('Ymd.s', $_SERVER['REQUEST_TIME']);
        $csv_file_path = $file_path . $file_name . '.csv';

        $fp = fopen($csv_file_path, 'w');

        /* 为了防止中文乱码先写入 */
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));

        if ($title_array) {
            fputcsv($fp, $title_array);
        }

        foreach ($content_array as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
    }

    /**
     * @desc  保存数据 - 任何格式文件 默认 PHP
     * @param string  $file_default_directory
     * @param string  $file_name 文件名
     * @param array   $pack_data 数据
     * @param string  $file_ext 文件后缀名
     */
    public static function exportDataToFile($file_default_directory = '', $file_name = '', array $pack_data = array(), $file_ext = 'php')
    {
        $file_default_directory = rtrim(str_replace('\\', '/', $file_default_directory), '/') . '/';
        $file_name = date('His', $_SERVER['REQUEST_TIME']) . $file_name;
        $save_file_path = $file_default_directory . $file_name . '.' . $file_ext;

        if ($file_ext == 'php') {
            $text_content = '<?php' . PHP_EOL;
            $text_content .= var_export($pack_data, true) . ';' . PHP_EOL .'?>';
        } else {
            $text_content = is_array($pack_data) ? json_encode($pack_data) : $pack_data;
        }

        file_put_contents($save_file_path, $text_content);
    }
}