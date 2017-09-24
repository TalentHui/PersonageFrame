### 「PSR 规范」PSR-1 基础编码规范

[原文鏈接，站点不错](https://laravel-china.org/topics/2078/psr-specification-psr-1-basic-coding-specification)

* 所有 PSR 规范请见：https://psr.phphub.org/

#### 基本代码规范

##### 1. 概览

* PHP代码文件 必须 以 <?php 或 <?= 标签开始；

* PHP代码文件 必须 以 不带 BOM 的 UTF-8 编码；

* PHP代码中 应该 只定义类、函数、常量等声明，或其他会产生 副作用 的操作（如：生成文件输出以及修改 .ini 配置文件等），二者只能选其一；

* 命名空间以及类 必须 符合 PSR 的自动加载规范：PSR-4 中的一个；

* 类的命名 必须 遵循 StudlyCaps 大写开头的驼峰命名规范；

* 类中的常量所有字母都 必须 大写，单词间用下划线分隔；

* 方法名称 必须 符合 camelCase 式的小写开头驼峰命名规范。

##### 2. 文件
###### 2.1. PHP标签

* PHP代码 必须 使用 <?php ?> 长标签 或 <?= ?> 短输出标签； 一定不可 使用其它自定义标签。

###### 2.2. 字符编码

* PHP代码 必须 且只可使用 不带BOM的UTF-8 编码。

##### 3.命名空间和类

* 命名空间以及类的命名必须遵循 PSR-4。

* 根据规范，每个类都独立为一个文件，且命名空间至少有一个层次：顶级的组织名称（vendor name）。

* 类的命名 必须 遵循 StudlyCaps 大写开头的驼峰命名规范。

* PHP 5.3 及以后版本的代码 必须 使用正式的命名空间。

* 例如：


    <?php
    // PHP 5.3及以后版本的写法
    namespace Vendor\Model;
    
    class Foo
    {
    }
    
*5.2.x 及之前的版本 应该 使用伪命名空间的写法，约定俗成使用顶级的组织名称（vendor name）如 Vendor_ 为类前缀。

    <?php
    // 5.2.x及之前版本的写法
    class Vendor_Model_Foo
    {
    }
    
##### 4. 类的常量、属性和方法

* 此处的「类」指代所有的类、接口以及可复用代码块（traits）。

###### 4.1. 常量

* 类的常量中所有字母都 必须 大写，词间以下划线分隔。

* 参照以下代码：


     <?php
    namespace Vendor\Model;
    
    class Foo
    {
        const VERSION = '1.0';
        const DATE_APPROVED = '2012-06-01';
    }

    
##### 4.2. 属性

* 类的属性命名 可以 遵循：

1. 大写开头的驼峰式 ($StudlyCaps)

2. 小写开头的驼峰式 ($camelCase)

3. 下划线分隔式 ($under_score)

##### 4.3. 方法

方法名称 必须 符合 camelCase() 式的小写开头驼峰命名规范。

[所有 PSR 规范请见](https://psr.phphub.org/)