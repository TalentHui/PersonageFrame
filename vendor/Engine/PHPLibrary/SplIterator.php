<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - SplIterator.php
 * Author:    WuHui
 * Date:      2018-01-24 20:54
 * Desc:      测试使用
 *******************************************************************************/

namespace Engine\PHPLibrary;


use Iterator;

class SplIterator implements Iterator
{

    /*
     * 定义要进行迭代的数组
     */
    private $_test = array('dog', 'cat', 'pig');

    /*
     * 索引游标
     */
    private $_key = 0;

    /*
     * 执行步骤
     */
    private $_step = 0;

    /**
     * 将索引游标指向初始位置
     *
     * @see TestIterator::rewind()
     */
    public function rewind()
    {
        echo iconv('utf-8' ,'gbk' , '第' . ++$this->_step . '步：执行 ' . __METHOD__ . PHP_EOL);
        $this->_key = 0;
    }

    /**
     * 判断当前索引游标指向的元素是否设置
     *
     * @see TestIterator::valid()
     * @return bool
     */
    public function valid()
    {
        echo iconv('utf-8' ,'gbk' , '第' . ++$this->_step . '步：执行 ' . __METHOD__ . PHP_EOL);
        return isset($this->_test[$this->_key]);
    }

    /**
     * 将当前索引指向下一位置
     *
     * @see TestIterator::next()
     */
    public function next()
    {
        echo iconv('utf-8' ,'gbk' , '第' . ++$this->_step . '步：执行 ' . __METHOD__ . PHP_EOL);
        $this->_key++;
    }

    /**
     * 返回当前索引游标指向的元素的值
     *
     * @see TestIterator::current()
     */
    public function current()
    {
        echo iconv('utf-8' ,'gbk' , '第' . ++$this->_step . '步：执行 ' . __METHOD__ . PHP_EOL);
        return $this->_test[$this->_key];
    }

    /**
     * 返回当前索引值
     *
     * @return int
     * @see TestIterator::key()
     */
    public function key()
    {
        echo iconv('utf-8' ,'gbk' , '第' . ++$this->_step . '步：执行 ' . __METHOD__ . PHP_EOL);
        return $this->_key;
    }
}