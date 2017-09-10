<?php
/********************************************************************************
 * Copyright: FreeWorld - PhpStorm - PersonageFrame - SqlBaseCommand.php
 * Author:    吴辉
 * Date:      2017-09-11 01:02
 * Desc:      sql 基本命令
 * Example:   http://www.cnblogs.com/yunf/archive/2011/04/12/2013448.html
 *******************************************************************************/

namespace Engine\Behavior;


class SqlBaseCommand
{
    /** 导出数据库 %s 用户名 %s 数据库名 %s 导出的文件名 */
    const dump_database = 'mysqldump -u %s -p %s > %s';

    /** 导出表 %s 用户名 %s 数据库名 %s 表名 %s 导出的文件名 */
    const dump_table = 'mysqldump -u %s -p %s %s > %s';

    /** 导出表结构 %s 用户名 %s 数据库名 %s 表名 %s 导出的文件名 */
    const dump_table_structure = 'mysqldump -u %s -p  -d -add %s %s > %s';

    /** 显示所有的数据库命令 */
    const sql_database_show = 'show databases';

    /** 创建一个数据库 */
    const sql_database_create = 'create database %s';

    /** 选择一个数据库 */
    const sql_database_select = 'use %s';

    /** 查看当前所使用的数据库 */
    const sql_database_current_area = 'show database()';

    /** 删除一个数据库 */
    const sql_database_drop = 'drop database if exists %s';

    /** 显示当前数据库下的所有表 */
    const sql_table_show_all = 'show tables';

    /** 查看表结构 */
    const sql_table_structure = array(
        'desc  %s',
        'show columns from %s',
        'describe %s'
    );

    /** 给表重命名 */
    const sql_table_rename = 'alter table %s rename %s';

    /** 清空表结构 notice: 主键从1开始 */
    const sql_table_truncate = 'truncate table %s';

    /** 查看表的创建语句 */
    const show_table_create_sentence = 'show create table %s';

    /** 添加列 @example alter table 表名 add 字段名 字段属性 after 欲在哪个字段后加入 */
    const sql_column_add = 'alter table %s add %s %s after %s';

    /** 删除列 @example alter table 表名 drop column 字段名 */
    const sql_column_drop = 'alter table %s drop column $s';

    /** 更改列 @example alter table 表名 change 原列名 更改后列名 字段属性 */
    const sql_column_modify = 'alter table %s change %s %s %s';

    /**
     * @desc   显示所有的数据库命令
     * @return string
     */
    public static function DatabasesShowAll()
    {
        return self::sql_database_show;
    }

    /**
     * @desc   创建一个数据库
     * @param  string $database_name
     * @return string
     */
    public static function DatabaseCreate($database_name = 'mysql')
    {
        return sprintf(self::sql_database_create, $database_name);
    }

    /**
     * @desc   选择一个数据库
     * @param  string $database_name
     * @return string
     */
    public static function DatabaseSelect($database_name = 'mysql')
    {
        return sprintf(self::sql_database_select, $database_name);
    }

    /**
     * @desc   查看当前所使用的数据库
     * @return string
     */
    public static function DatabaseCurrentArea()
    {
        return self::sql_database_current_area;
    }

    /**
     * @desc   删除一个数据库
     * @param  string $database_name
     * @return string
     */
    public static function DatabaseDrop($database_name = 'mysql')
    {
        return sprintf(self::sql_database_drop, $database_name);
    }

    /**
     * @desc   显示当前数据库下的所有表
     * @return string
     */
    public static function TablesShowAll()
    {
        return self::sql_table_show_all;
    }

    /**
     * @desc   查看表结
     * @param  $table_name
     * @param  int $type
     * @return string
     */
    public static function TableShowStructure($table_name, $type = 0)
    {
        $type = intval($type);

        if (empty(self::sql_table_structure[$type])) {
            $type = 0;
        }

        return sprintf(self::sql_table_structure[$type], $table_name);
    }

    /**
     * @desc   给表重命名
     * @param  $old_name
     * @param  $new_name
     * @return string
     */
    public static function TableRename($old_name, $new_name)
    {
        return sprintf(self::sql_table_rename, $old_name, $new_name);
    }

    /**
     * @desc   清空表数据
     * @param  $table_name
     * @return string
     */
    public static function TableTruncate($table_name)
    {
        return sprintf(self::sql_table_truncate, $table_name);
    }

    /**
     * @desc   查看表的创建语句
     * @param  $table_name
     * @return string
     */
    public static function TableShowCreateSentence($table_name)
    {
        return sprintf(self::show_table_create_sentence, $table_name);
    }

    /**
     * @desc   添加列
     * @param  $table_name
     * @param  $column_name
     * @param  $column_attr
     * @param  $before_column
     * @return string
     */
    public static function column_add($table_name, $column_name, $column_attr, $before_column)
    {
        return sprintf(self::sql_column_add, $table_name, $column_name, $column_attr, $before_column);
    }

    /**
     * @desc   删除列
     * @param  $table_name
     * @param  $column_name
     * @return string
     */
    public static function column_drop($table_name, $column_name)
    {
        return sprintf(self::sql_column_drop, $table_name, $column_name);
    }

    /**
     * @desc   更改列
     * @param  $table_name
     * @param  $old_column_name
     * @param  $new_column_name
     * @param  $new_column_attr
     * @return string
     */
    public static function column_modify($table_name, $old_column_name, $new_column_name, $new_column_attr)
    {
        return sprintf(self::sql_column_modify, $table_name, $old_column_name, $new_column_name, $new_column_attr);
    }
}