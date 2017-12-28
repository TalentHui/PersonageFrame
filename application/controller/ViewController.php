<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - ViewController.php
 * Author:    WuHui
 * Date:      2017-12-27 17:42
 * Desc:      视图控制器
 *******************************************************************************/

use Engine\Base\BaseController;

class ViewController extends  BaseController
{
    public function processEChartsView()
    {
        if (empty($this->postParams->getItem('step'))) {
            $this->dispatcher->loadTemplateView('View\processEChartsView');
            return;
        }

        $data = array(
            'title' => array(  // 图表标题
                'text' => '图表标题',
                'subtext' => '图表详细说明'
            ),
            'legend' => array( // 数据和图形的关联
                'data' => array(
                    'map_one',
                    'map_two'
                )
            ),
            'xAxis' => array(  // x 轴数据
                'type' => 'category',
                'data' => array(
                    '2017/01',
                    '2017/02',
                    '2017/03',
                    '2017/04',
                    '2017/05',
                    '2017/06',
                    '2017/07',
                    '2017/08',
                    '2017/09',
                    '2017/10',
                    '2017/11',
                    '2017/12',
                )
            ),
            'series' => array(
                array(
                    'name' => 'data_one',
                    'type' => 'bar',
                    'data' => array(100, 200, 300, 400, 500, 600, 700, 800, 900, 1000, 1100, 1200)
                ),
                array(
                    'name' => 'data_two',
                    'type' => 'bar',
                    'data' => array(100, 200, 300, 400, 500, 600, 700, 800, 900, 1000, 1100, 1800)
                )
            )
        );
        \Engine\Common\RequestFunction::ajaxJsonReturn($data);
    }
}