<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - EChartsFunction.php
 * Author:    WuHui
 * Date:      2017-12-28 15:10
 * Desc:      功能描述
 *******************************************************************************/

namespace Engine\Common;


class EChartsFunction
{
    /**
     * @desc   拼装 ECharts 需要的数据格式
     * @param  string $title_text
     * @param  string $title_subtext
     * @param  array  $legend_data
     * @param  array  $aAxis_data
     * @param  array  $series_data  （二维数组）
     * @return array
     */
    public static function packEChartsReturnData($title_text = '', $title_subtext = '', array $legend_data = array(), array $aAxis_data = array(), array $series_data = array())
    {
        $title_text = $title_text ? $title_text : '图表标题';
        $title_subtext = $title_subtext ? $title_subtext : '图表详细说明';
        $legend_data = $legend_data ? $legend_data : array('map_one', 'map_two');

        $aAxis_data = $aAxis_data
            ? $aAxis_data
            : array('2017/01', '2017/02', '2017/03', '2017/04', '2017/05', '2017/06', '2017/07', '2017/08', '2017/09', '2017/10', '2017/11', '2017/12',);

        $series_data = $series_data
            ? $series_data
            : array(
                array(
                    'name' => 'data_one',
                    'data' => array(100, 200, 300, 400, 500, 600, 700, 800, 900, 1000, 1100, 1200)
                ),
                array(
                    'name' => 'data_two',
                    'data' => array(100, 200, 300, 400, 500, 600, 700, 800, 900, 1000, 1100, 1800)
                )
            );

        // series 添加元素
        foreach ($series_data as &$series_data_item) {
            // 展示平均值线
            $series_data_item['markLine'] = array(
                'data' => array(
                    array('type' => 'average', 'name' => '平均值')
                )
            );
            // 展示每个图表的统计数量
            $series_data_item['itemStyle'] = array(
                'normal' => array(
                    'label' => array(
                        'show' => true,
                        'position' => 'top',
                        'formatter' =>  '{c}'
                    )
                )
            );
            // 图表类型
            $series_data_item['type'] = 'line';
        }

        $data = array(
            'title' => array(  // 图表标题
                'text' => $title_text,
                'subtext' => $title_subtext
            ),
            'legend' => array( // 数据和图形的关联
                'data' => $legend_data
            ),
            'xAxis' => array(  // x 轴数据
                'type' => 'category',
                'data' => $aAxis_data
            ),
            'series' => $series_data
        );

        return $data;
    }
}