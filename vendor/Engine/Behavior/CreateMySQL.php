<?php
/***********************************************************************************************************************
 * Copyright: PhpStorm - PersonageFrame - CreateMySQL.php
 * Author:    WuHui
 * Date:      2017-09-11 14:03
 * Desc:      建库、建表
 *            位： "位(bit)"是电子计算机中最小的数据单位。每一位的状态只能是0或1。数据传输以位为单位
 *            字节：8个二进制位构成1个"字节(Byte)"，它是存储空间的基本计量单位。1个字节可以储存1个英文字母或者半个汉字。
 *            字： "字"由若干个字节构成，字的位数叫做字长，不同档次的机器有不同的字长。字是计算机进行数据处理和运算的单位。
 *
 *            8位/b(数据传输单位) = 1字节/B(存储空间的计量单位)
 *
 *            字符：含义：数字、字母和符号。
 *                 ASCII编码中，一个英文字符存储需要1个字节;
 *                 GB2312编码或者GBK编码中，一个汉字字符存储需要2个字节;
 *                 UTF-8编码中，一个英文字母字符存储需要1个字节，一个汉字字符存储需要3~4个字节;
 *
 *                 表达：字符是可使用多种不同字符方案或代码页来表示的抽象实体。Unicode UTF-8编码则将相同的字符表示为8位字节序列。
 **********************************************************************************************************************/

namespace Engine\Behavior;


class CreateMySQL
{
    const int_type_list = array(
        array(
            'type' => 'tinyint',
            'desc' => '存储范围：-128至127或0至255',
            'example' => 'tinyint(1)'
        ),
        array(
            'type' => 'smallint',
            'desc' => '存储范围：-32768至32767或0至65535',
            'example' => 'smallint(3)'
        ),
        array(
            'type' => 'mediumint',
            'desc' => '存储范围：- 8388608至8388607或0至16777215',
            'example' => 'mediumint(5)'
        ),
        array(
            'type' => 'int',
            'desc' => '存储范围：- 2147483648至2147483647或0至4294967295',
            'example' => 'int(10)'
        ),
        array(
            'type' => 'bigint',
            'desc' => '存储范围：- 9223372036854775808至9223372036854775807或0至18446744073709551615',
            'example' => 'bigint(10)'
        ),
        array(
            'type' => 'float',
            'desc' => '最小非零值：±1.175494351E – 38，同double一样适用于精度要求高的场合',
            'example' => 'float(3,1)'
        ),
        array(
            'type' => 'double',
            'desc' => '最小非零值：±2.2250738585072014E - 308',
            'example' => 'double(10,5)'
        ),
        array(
            'type' => 'decimal',
            'desc' => '取值范围可变，以来括号内的显示尺寸和小数点位数而定，适用于对精度要求不高但准确度要求非常高的场合',
            'example' => 'decimal(10,2)'
        )
    );

    const string_type_list = array(
        array(
            'type' => 'char',
            'desc' => '支持固定长度的字符串, 最大长度是 255 个字符',
            'example' => 'char(100)'
        ),
        array(
            'type' => 'varchar',
            'desc' => '支持可变长度的字符串, 最大长度是 65535 个字符',
            'example' => 'varchar(1000)'
        ),
        array(
            'type' => 'tinytext',
            'desc' => '支持可变长度的字符串，最大长度是 255 个字符',
            'example' => 'tinytext'
        ),
        array(
            'type' => 'text|blob',
            'desc' => '支持可变长度的字符串，最大长度是 65535 个字符',
            'example' => 'text'
        ),
        array(
            'type' => 'mediumtext|mediumblob',
            'desc' => '支持可变长度的字符串，最大长度是 16777215 个字符',
            'example' => 'mediumtext'
        ),
        array(
            'type' => 'longtext|longblob',
            'desc' => '支持可变长度的字符串，最大长度是 4294967295 个字符',
            'example' => 'longtext'
        ),
        array(
            'type' => 'enum',
            'desc' => '枚举类型，可存储最多65535 个成员，常用于取值是有限而且固定的场合',
            'example' => 'enmu("boy","girl")'
        ),
        array(
            'type' => 'set',
            'desc' => '集合类型，可存储最多64个成员',
            'example' => 'set("value1","value2", ...)'
        )
    );

    const time_type_list = array(
        array(
            'type' => 'date',
            'desc' => 'YYYY-MM-DD 格式表示的日期值',
            'example' => 'date'
        ),
        array(
            'type' => 'time',
            'desc' => 'hh:mm:ss 格式表示的时间值',
            'example' => 'time'
        ),
        array(
            'type' => 'datetime',
            'desc' => 'YYYY-MM-DD hh:mm:ss 格式表示的日期和时间值',
            'example' => 'datetime'
        ),
        array(
            'type' => 'timestamp',
            'desc' => 'YYYYMMDDhhmmss 格式表示的时间戳值',
            'example' => 'timestamp'
        ),
        array(
            'type' => 'year',
            'desc' => 'YYYY 格式表示的年份值',
            'example' => 'year'
        )
    );

    public static function MySQLDataType()
    {
        $string = 'MySQL 数据（字段）类型' . PHP_EOL;
        $string .= '|-在创建表的时候，要明确定义字段对应的数据类型。MySQL 主要的数据类型分为数值类型、字符串（文本）类型、时间日期类型和其他类型几类。' . PHP_EOL;
        $string .= '|-数值类型(数值类型说明：)' . PHP_EOL;
        $string .= '|--类型------|--例子------------|--说明------------------------------------------------------------------------------------------------' . PHP_EOL;

        foreach (self::int_type_list as $int_type_list_item) {
            $string .= '|--' . str_pad($int_type_list_item['type'], 10) . '|' . str_pad($int_type_list_item['example'], 18) .  '|' . $int_type_list_item['desc'] . PHP_EOL;
        }

        $string .= '|------------|------------------|------------------------------------------------------------------------------------------------------' . PHP_EOL;

        $string .= '|-补充说明' . PHP_EOL;
        $string .= '|-1、在 int（integer） 系列中，只能存储整型值，且可以在后面用括号指定显示的尺寸（M），如果不指定则会默认分配。如果实际值的显示宽度大于' . PHP_EOL;
        $string .= '|---设定值，将会显示实际值而不会截断以适应显示尺寸。如 smallint(3) 中的 3 即为显示尺寸，即显示三位的数值（不包括 - 号' . PHP_EOL;
        $string .= '|-2、int 类型可以指定 UNSIGNED 属性，即无符号（非负），所以存储范围有两种' . PHP_EOL;
        $string .= '|-3、在 float、double 及 decimal 类型中，不能指定 UNSIGNED 属性，其显示尺寸包含了小数点精度（D），即 float(3,1) 保存范围为 -99.9 至 99.9' . PHP_EOL;
        $string .= '|-4、在可能涵盖取值范围的基础上，尽可能选择较小的类型以提高效率和节约存储空间，如年龄，就选择 tinyint(3) 。该原则对于字符类型同样适用' . PHP_EOL;

        $string .= '|------------|------------------|------------------------------------------------------------------------------------------------------' . PHP_EOL;
        $string .= '|-字符串（文本）类型(字符串（文本）类型说明：)' . PHP_EOL;
        $string .= '|--类型------------------|--例子------------------------|--说明------------------------------------------------------------------------' . PHP_EOL;

        foreach (self::string_type_list as $string_type_list_item) {
            $string .= '|--' . str_pad($string_type_list_item['type'], 22) . '|' . str_pad($string_type_list_item['example'], 30) .  '|' . $string_type_list_item['desc'] . PHP_EOL;
        }

        $string .= '|--------------------------------------------------------------------------------------------------------------------------------------' . PHP_EOL;
        $string .= '|-补充说明' . PHP_EOL;
        $string .= '|-1、char 和 varchar 需要指定长度，不同的是，char 存储时总是按照指定的长度储存，而 varchar 则根据实际字符串长度再加上一个字节分配空间' . PHP_EOL;

        $string .= '|------------|------------------|------------------------------------------------------------------------------------------------------' . PHP_EOL;
        $string .= '|-时间日期类型(时间日期类型说明：)' . PHP_EOL;
        $string .= '|--类型------|--例子------------|--说明------------------------------------------------------------------------------------------------' . PHP_EOL;

        foreach (self::time_type_list as $time_type_list_item) {
            $string .= '|--' . str_pad($time_type_list_item['type'], 10) . '|' . str_pad($time_type_list_item['example'], 18) .  '|' . $time_type_list_item['desc'] . PHP_EOL;
        }

        $string .= '|------------|------------------|------------------------------------------------------------------------------------------------------' . PHP_EOL;
        $string .= '|-提示' . PHP_EOL;
        $string .= '|-1、在 PHP 中，一般情况下对于时间都是按照 UNIX 时间戳以 int 类型存储于表中，再根据实际需要用 PHP 的时间函数进行处理，但不完全都是这样。' . PHP_EOL;

        return $string;
    }
}